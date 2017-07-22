<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App;

class DatabaseController extends Controller
{
	public $config = '';
	public $database = '';
	public $dataname = '';
	public $db_pre = 'li_';
	public function __construct(){
		$this->config = config('database.default');
		$this->database = config('database.connections');
		$this->dataname = $this->database[$this->config]['database'];
		$this->db_pre = $this->database[$this->config]['prefix'];
	}
	/**
	 * 数据库导出
	 */
	public function getExport() {
		$title = '备份数据表';
		// 先把所有表列出来
		$alltables = DB::select("SHOW TABLE STATUS FROM `".$this->dataname."`");
		return view('admin.database.export',compact('title','alltables'));
	}
	public function postExport(Request $req)
	{
		if (is_array($req->tables)) {
			$res = $this->export_database($req->tables,$req->fileid,$req->random,$req->tableid,$req->startfrom,$req->tabletype);
			if ($res) {
				return redirect('console/database/import');
			}
			else
			{
				return back()->with('message', '备份失败！');
			}
		}
		else
		{
			return back()->with('message', '请先选择要备份的数据表！');
		}
	}
	// 数据库恢复
	public function getImport(Request $req,$pre = '')
	{
		$title = '恢复数据库';
		// 列出文件
		$allfiles = glob(storage_path('backup/*.sql'));
		if (is_array($allfiles)) {
			asort($allfiles);
			$prepre = '';
			$info = $infos = array();
			foreach($allfiles as $id=>$sqlfile) {
				if(preg_match("/([0-9a-z]{20}_+[0-9]{8}_)([0-9]+)\.sql/i",basename($sqlfile),$num)) {
					$info['filename'] = basename($sqlfile);
					$info['filesize'] = filesize($sqlfile);
					$info['maketime'] = date('Y-m-d H:i:s', filemtime($sqlfile));
					$info['pre'] = $num[1];
					$info['number'] = $num[2];
					$prepre = $info['pre'];
					$infos[] = $info;
				}
			}
		}
		if (!is_null($pre) && $pre != '') {
			if($this->import_database($pre))
			{
				return back()->with('message','恢复成功！');
			}
		}
		$infos = collect($infos)->sortByDesc('maketime');
		return view('admin.database.import',compact('title','infos'));
	}
	// 删除备份的文件
	public function postDelfile(Request $req)
	{
		$filenames = $req->tables;
		$bakfile_path = storage_path('backup/');
		if($filenames) {
			if(is_array($filenames)) {
				foreach($filenames as $filename) {
					if($this->fileext($filename)=='sql') {
						@unlink($bakfile_path.$filename);
					}
				}
				return back()->with('message','删除成功！');
			}
		} else {
			return back()->with('message','请选择文件！');
		}
	}
	/**
	 * 数据库导出方法
	 * @param unknown_type $tables 数据表数据组
	 * @param unknown_type $sizelimit 卷大小
	 * @param unknown_type $fileid 卷标
	 * @param unknown_type $random 随机字段
	 * @param unknown_type $tableid 
	 * @param unknown_type $startfrom 
	 */
	private function export_database($tables,$fileid,$random,$tableid,$startfrom) {
		$limitsize = 104857600; // 100M分卷大小，104857600

		$sqlcharset = 'utf8';

		$fileid = ($fileid != '') ? $fileid : 1;		
		if($fileid==1 && $tables) {
			$random = app('com')->random(20, 'abcdefghigklmzopqrstuvwxyz0123456789');
		}

		DB::select("SET SQL_MODE=''");
		
		$tabledump = '';
		$tableid = ($tableid!= '') ? $tableid - 1 : 0;
		$startfrom = ($startfrom != '') ? intval($startfrom) : 0;
		for($i = $tableid; $i < count($tables) && strlen($tabledump) < $limitsize; $i++) {
			global $startrow;
			$offset = 100;
			if($startfrom == 0) {
				if($tables[$i]!=$this->db_pre.'sessions') {
					$tabledump .= "DROP TABLE IF EXISTS `$tables[$i]`;\n";
				}
				$createtable = DB::select("SHOW CREATE TABLE `$tables[$i]` ");
				$tabledump .= ((array) $createtable[0])['Create Table'].";\n\n";
				
				$tabledump = preg_replace("/(DEFAULT)*\s*CHARSET=[a-zA-Z0-9]+/", "DEFAULT CHARSET=".$sqlcharset, $tabledump);

				if($tables[$i]==$this->db_pre.'sessions') {
					$tabledump = str_replace("CREATE TABLE `".$this->db_pre."sessions`", "CREATE TABLE IF NOT EXISTS `".$this->db_pre."sessions`", $tabledump);
				}
			}

			$numrows = $offset;
			while(strlen($tabledump) < $limitsize && $numrows == $offset) {
				if($tables[$i]==$this->db_pre.'sessions') break;
				$sql = "SELECT * FROM `$tables[$i]` LIMIT $startfrom, $offset";
				$tablename = str_ireplace($this->db_pre,'',$tables[$i]);
				$numrows = DB::table($tablename)->count();
				$data = DB::select($sql);
				// 有返回数据开始拼接字符串
				if (count($data) > 0) {
					foreach ($data as $v) {
						$tabledump .= "INSERT INTO `$tables[$i]` VALUES(";
						$tmpdata = '';
						foreach($v as $a)
						{
							$tmpdata .= "'".$a."',";
						}
						$tmpdata = trim($tmpdata,',');
						$tmpdata .= '),';
						$tmpdata = trim($tmpdata,',');
						$tabledump .= $tmpdata;
						$tabledump .= ";\n";
					}
				}
				$startfrom += $offset;
			}
			$tabledump .= "\n";
			$startrow = $startfrom;
			$startfrom = 0;
		}

		if(trim($tabledump)) {
			$tableid = $i;
			$filename = $random.'_'.date('Ymd').'_'.$fileid.'.sql';
			$fileid++;
			$bakfile = storage_path('backup'.DIRECTORY_SEPARATOR.$filename);
			file_put_contents($bakfile, $tabledump);
			@chmod($bakfile, 0777);
			$this->export_database($tables,$fileid,$random,$tableid,$startrow);
		}
		return true;
	}
	/**
	 * 数据库恢复
	 * @param unknown_type $filename
	 */
	private function import_database($filename,$fileid = 1) {
		$fileid = $fileid ? $fileid : 1;
		$pre = $filename;
		$filename = $filename.$fileid.'.sql';
		$filepath = storage_path('backup/'.$filename);
		if(file_exists($filepath)) {
			$sql = file_get_contents($filepath);
			$this->sql_execute($sql);
			$fileid++;
			$this->import_database($pre,$fileid);
			return true;
		} else {
			return true;
		}
	}
	// 取文件扩展名
	private function fileext($filename) {
		return strtolower(trim(substr(strrchr($filename, '.'), 1, 10)));
	}
	/**
	 * 执行SQL
	 * @param unknown_type $sql
	 */
 	private function sql_execute($sql) {
	    $sqls = $this->sql_split($sql);
		if(is_array($sqls)) {
			foreach($sqls as $sql) {
				if(trim($sql) != '') {
					DB::statement($sql);
				}
			}
		} else {
			DB::statement($sqls);
		}
		return true;
	}
	

 	private function sql_split($sql) {
		$sql = str_replace("\r", "\n", $sql);
		$ret = array();
		$num = 0;
		$queriesarray = explode(";\n", trim($sql));
		unset($sql);
		foreach($queriesarray as $query) {
			$ret[$num] = '';
			$queries = explode("\n", trim($query));
			$queries = array_filter($queries);
			foreach($queries as $query) {
				$str1 = substr($query, 0, 1);
				if($str1 != '#' && $str1 != '-') $ret[$num] .= $query;
			}
			$num++;
		}
		return($ret);
	}	
}

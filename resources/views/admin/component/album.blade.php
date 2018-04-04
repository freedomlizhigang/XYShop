<!-- 缩略图 -->
<div id="album_img" class="wu-example">
    <!--用来存放文件信息-->
    <div id="album_list" class="uploader-list">
    	@if($slot != '')
        @foreach(explode(',',$slot) as $s)
    	<div class="file-item">
            <div class="file-panel"><span class="cancel">×</span></div>
			<img src="{{ $s }}" width="108" height="90" alt="">
            <div class="info"></div>
		</div>
        @endforeach
    	@endif
    </div>
    <div class="clearfix">
        <div id="album_btn" class="btn btn-sm btn-success">上传文件</div>
        <!-- <div id="album_clt" class="btn btn-sm btn-warning">删除一张</div> -->
    </div>
</div>
<p class="input-info">图片类型jpg/jpeg/gif/png，宽*高：750*635px，单个大小不超过2M</p>
<textarea class="hidden" id="album" name="data[{{ $filed_name }}]" >{{ $slot }}</textarea>
<script>
    // 缩略图
    var $list_album = $("#album_list");
    var album_src = [
        @if($slot != '')
        @foreach(explode(',',$slot) as $s)
            '{{ $s }}'
            @if (!$loop->last)
                ,
            @endif
        @endforeach
        @endif
    ];
    var album = WebUploader.create({
        // 自动上传
        auto: true,
        // 控制数量
        fileNumLimit:5,
        // 文件接收服务端。
        server : "{{ url('api/common/upload') }}",
        // 选择文件的按钮。可选。
        pick: '#album_btn',
        // 不压缩image, 默认如果是jpeg，文件上传前会压缩一把再上传！
        // resize: false,
        compress: false,
        // inputime 字段名，检查上传字段用的，十分重要
        fileVal:'imgFile',
        // 只允许选择图片文件。
        accept: {
           title: 'Images',
           extensions: 'gif,jpg,jpeg,bmp,png',
           mimeTypes: 'image/*'
        },
        // 开起分片上传。
        // chunked: true,
        formData:{
            thumb : 1,
            thumbWidth:750,
            thumbHeight:635
        },
        thumb: {
            width: 108,
            height: 90,
            quality: 70,
            allowMagnify: true,
            crop: true,
            preserveHeaders: false,
            // 为空的话则保留原有图片格式。
            // 否则强制转换成指定的类型。
            // IE 8下面 base64 大小不能超过 32K 否则预览失败，而非 jpeg 编码的图片很可
            // 能会超过 32k, 所以这里设置成预览的时候都是 image/jpeg
            type: ''
        }
    });
    // 成功以后加入input中
    album.on('uploadSuccess',function(file,req){
        // console.log(req);
        album_src.push(req.url);
        console.log(album_src);
        $('#album').text(album_src);
    });
    $('#album_list').delegate('.file-panel', 'click', function() {
        // 找出来索引
        var thisIndex = $(this).parent('.file-item').index();
        // 删除src
        album_src.splice(thisIndex, 1);
        $('#album').text(album_src);
        // 从预览里删除
        $("#album_list > .file-item").eq(thisIndex).remove();
        // console.log(album_src);
    })
    // 当有文件被添加进队列的时候
    album.on( 'fileQueued', function( file ) {
        var $li = $(
                '<div id="' + file.id + '" class="file-item">' +
                    '<img>' +
                    '<div class="info">' + file.name + '</div>' +
                '</div>'
                ),
            $btns = $('<div class="file-panel"><span class="cancel">×</span></div>').appendTo( $li ),
            $img = $li.find('img');
        // $list_album为容器jQuery实例
        $list_album.append( $li );
        // 绑定删除
        $li.delegate('.cancel', 'click', function() {
            // 找出来索引
            var thisIndex = file.id.substr(8);
            // 删除src
            album_src.splice(thisIndex, 1);
            $('#album').text(album_src);
            // 从预览里删除
            $("#" + file.id).remove();
            // console.log(thisIndex);
            album.removeFile(file);
        })
        // 创建缩略图
        // 如果为非图片文件，可以不用调用此方法。
        // thumbnailWidth x thumbnailHeight 为 100 x 100
        album.makeThumb( file, function( error, src ) {
            if ( error ) {
                $img.replaceWith('<span>不能预览</span>');
                return;
            }

            $img.attr( 'src', src );
        }, '', '' );
    });
</script>
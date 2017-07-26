<!-- 加载编辑器的容器 -->
<script id="{{ $id }}" name="data[{{ $filed_name }}]" class="data_content" type="text/plain">
    {{ $slot }}
</script>
<script>
    // 实例化编辑器
    var ue = UE.getEditor('{{ $id }}',{
        autoHeight: false,
        initialFrameWidth : '100%',
        initialFrameHeight: 400,
        serverUrl:"{{ url('api/common/ueditor_upload') }}"
    });
</script>
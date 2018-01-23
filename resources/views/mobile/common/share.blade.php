<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" charset="utf-8">
  wx.config(<?php echo $wechat_js->buildConfig(array('onMenuShareTimeline', 'onMenuShareAppMessage'), false) ?>);
  $(function(){
    // 分享
    setTimeout(function(){
        wx.onMenuShareAppMessage({
            title: "{{ $title }}-{{ cache('config')['title'] }}", // 分享标题
            desc: "{{ isset($describe) ? $describe : cache('config')['describe'] }}", // 分享描述
            link: "{{ url()->full() }}", // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
            imgUrl: "{{ $sites['static']}}mobile/images/logo_share.png", // 分享图标
            type: 'link', // 分享类型,music、video或link，不填默认为link
            dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
            success: function () { 
                // 用户确认分享后执行的回调函数
            },
            cancel: function () { 
                // 用户取消分享后执行的回调函数
            }
        });
        wx.onMenuShareTimeline({
            title: "{{ $title }}-{{ cache('config')['title'] }}", // 分享标题
            link: "{{ url()->full() }}", // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
            imgUrl: "{{ $sites['static']}}mobile/images/logo_share.png", // 分享图标
            success: function () { 
                // 用户确认分享后执行的回调函数
            },
            cancel: function () { 
                // 用户取消分享后执行的回调函数
            }
        });
    },500);
  })
</script>
@extends('mobile.layout')

@section('content')
  <div class="share-font mt20">
    <h2 class="color_shenred tc pd10">点击右上角分享到朋友圈或发送给朋友，邀请好友注册有好礼相送！</h2>
  </div>
  <script src="http://res.wx.qq.com/open/js/jweixin-1.2.0.js" type="text/javascript" charset="utf-8"></script>
  <script type="text/javascript" charset="utf-8">
    wx.config(<?php echo $wechat_js->buildConfig(array('onMenuShareTimeline', 'onMenuShareAppMessage'), false) ?>);
    $(function(){
    // 分享
    setTimeout(function(){
        wx.onMenuShareAppMessage({
            title: "{{ cache('config')['title'] }}", // 分享标题
            desc: "{{ cache('config')['describe'] }}", // 分享描述
            link: "{{ $url }}", // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
            imgUrl: "{{ $sites['static']}}mobile/images/logo_share.png", // 分享图标
            type: 'link', // 分享类型,music、video或link，不填默认为link
            dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
            success: function () { 
              $('.alert_msg').text('分享成功，过几天就可以看到您的收益了！').slideToggle().delay(1500).slideToggle();
            },
            cancel: function () { 
              $('.alert_msg').text('为什么取消分享呢！').slideToggle().delay(1500).slideToggle();
            }
        });
        wx.onMenuShareTimeline({
            title: "{{ cache('config')['title'] }}", // 分享标题
            link: "{{ $url }}", // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
            imgUrl: "{{ $sites['static']}}mobile/images/logo_share.png", // 分享图标
            success: function () { 
              $('.alert_msg').text('分享成功，过几天就可以看到您的收益了！').slideToggle().delay(1500).slideToggle();
            },
            cancel: function () { 
              $('.alert_msg').text('为什么取消分享呢！').slideToggle().delay(1500).slideToggle();
            }
        });
    },500);
  })
  </script>
@endsection
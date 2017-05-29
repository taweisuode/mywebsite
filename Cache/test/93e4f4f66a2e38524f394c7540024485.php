<html>
<head>
<title>欢迎使用ppf-可能是最简单的php框架</title>
</head>
<body bgcolor="white">
<center><h1><?php echo $result;?> </h1></center>
</body>
<input type="hidden" data-appId="<?php echo $signArr['appId'];?>" data-timestamp="<?php echo $signArr['timestamp'];?>" data-nonceStr="<?php echo $signArr['nonceStr'];?>" data-signature="<?php echo $signArr['sign'];?>" id="signArr" >
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>
    var appId = $("#signArr").attr("data-appId");
    var timestamp = $("#signArr").attr("data-timestamp");
    var nonceStr = $("#signArr").attr("data-nonceStr");
    var signature = $("#signArr").attr("data-signature");
    wx.config({
        debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
        appId: appId, // 必填，公众号的唯一标识
        timestamp: timestamp, // 必填，生成签名的时间戳
        nonceStr: nonceStr, // 必填，生成签名的随机串
        signature: signature,// 必填，签名，见附录1
        jsApiList: [
            'checkJsApi',
            'onMenuShareTimeline',
            'onMenuShareAppMessage',
            'onMenuShareQQ',
            'onMenuShareWeibo'
        ] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
    });
    wx.ready(function () {
        wx.onMenuShareAppMessage({
            title: '机票行程单分享',
            desc: '机票行程单分享',
            link: 'http://movie.douban.com/subject/25785114/',
            imgUrl: 'http://img3.douban.com/view/movie_poster_cover/spst/public/p2166127561.jpg',
            trigger: function (res) {
                //alert('用户点击发送给朋友');
            },
            success: function (res) {
                //alert('已分享');
            },
            cancel: function (res) {
                //alert('已取消');
            },
            fail: function (res) {
                //alert(JSON.stringify(res));
            }
        });
        wx.onMenuShareTimeline({
            title: '机票行程单分享',
            link: 'http://movie.douban.com/subject/25785114/',
            imgUrl: 'http://img3.douban.com/view/movie_poster_cover/spst/public/p2166127561.jpg',
            trigger: function (res) {
                //alert('用户点击分享到朋友圈');
            },
            success: function (res) {
                //alert('已分享');
            },
            cancel: function (res) {
                //alert('已取消');
            },
            fail: function (res) {
                //alert(JSON.stringify(res));
            }
        });
    });
</script>

</html>

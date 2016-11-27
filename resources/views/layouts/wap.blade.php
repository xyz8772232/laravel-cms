<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    @section('title')
        <title>财经头条-为您发掘海量信息中的投资价值</title>
    @show
    @section('description')
        <meta name="description" content="财经头条-为您发掘海量信息中的投资价值">
    @show

    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <!-- 强制竖屏 -->
    <meta name="screen-orientation" content="portrait">
    <meta name="x5-orientation" content="portrait">
    <!-- 关闭电话识别 -->
    <meta name="format-detection" content="telephone=no">
    <!-- 百度禁止转码 -->
    <meta http-equiv="Cache-Control" content="no-siteapp">
    <!-- dns预解析 -->
    <link rel="dns-prefetch" href="//n.sinaimg.cn">

    @yield('css')

    @section('pageConfig')
    @show
</head>

<body>
<script>
    (function () {
        // 重置字体大小
        var docEl = document.documentElement;
        var resizeEvt = 'orientationchange' in window ? 'orientationchange' : 'resize';
        var recalc = function () {
            var clientWidth = docEl.clientWidth;
            if (!clientWidth) return;
            docEl.style.fontSize = 625 * (clientWidth / 750) + '%';
        };
        window.addEventListener(resizeEvt, recalc, false);
        recalc();

        // 是否开启兼容模式
        if (!window.navigator.userAgent.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/)) {
            window.compatibilityMode = true;
            document.body.classList.add('compatibility-mode');
        } else {
            window.compatibilityMode = false;
        }

        // 初始化字体大小
        document.body.classList.add('font-size-' + localStorage.getItem('fontSize') || 1);
        })();
</script>
@yield('main')
@yield('js')
</body>
</html>
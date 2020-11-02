<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>工业魔方开放接口</title>

    <!-- Fonts -->
{{--        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">--}}

<!-- Styles -->
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Nunito', sans-serif;
            font-weight: 200;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 74px;
        }

        .links > a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .m-b-md {
            margin-bottom: 30px;
        }

        .footer {
            width: 100%;
            position: absolute;
            text-align: center;
            left: 0;
            bottom: 0;
        }

        .footer div {
            width: 100%;
            text-align: center;
            font-weight: bold;
            font-size: 14px;
            line-height: 25px;
        }
    </style>
</head>
<body>
<div class="flex-center position-ref full-height">

    <div class="content">
        <div class="title m-b-md">
            工业魔方开放接口
        </div>

        <div class="links">
            <a href="{{env('OFFICIAL_WEBSITE')}}">官网入口</a>
            <a href="{{env('PARTNER_CONTACT')}}">合作联系</a>
            <a href="javascript:void(0)" onclick="contactDeveloper('{{env('CONTACT_DEVELOPER')}}')">联系开发者</a>
            <a href="javascript:void(0)" onclick="aboutDeveloper('{{env('ABOUT_DEVELOPER')}}','{{env('CONTACT_DEVELOPER')}}')">关于开发者</a>
            <a href="{{env('ABOUT_US','https://blog.laravel.com')}}">关于我们</a>
            {{--                    <a href="https://forge.laravel.com">Forge</a>--}}
            {{--                    <a href="https://vapor.laravel.com">Vapor</a>--}}
            <a href="{{env('OPEN_SOURCE_ADDRESS','https://github.com/laravel/laravel')}}">开源地址</a>
        </div>
    </div>
</div>
<div class="footer">
    <div>&copy;&nbsp;{{date('Y')}} -- {{env('ORGANIZER')}} 网站备案号 {{env('RECORD_NO')}}</div>
</div>
<script>
    function contactDeveloper(phone) {
        alert('联系人微信：' + phone)
    }

    function aboutDeveloper(content, phone) {
        alert(`${content}--联系人微信：${phone}`)
    }
</script>
</body>
</html>

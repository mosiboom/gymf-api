<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
    <title>API测试工具</title>
    <style>
        #result-container {
            width: 100%;
            height: 300px;
            background: black;
            color: white;
            font-family: 'Source Code Pro', serif;
            padding: 20px;
            box-sizing: border-box;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="card mt-3">
        <div class="card-header">
            <b>API测试工具</b>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label for="url">请求地址：</label>
                <input type="text" class="form-control" id="url" aria-describedby="emailHelp">
                <small id="urlHelp" class="form-text text-muted">输入请求的接口地址...</small>
            </div>
            <div class="form-group">
                <label for="header">请求头部（JSON）：</label>
                <input type="text" class="form-control" id="header" aria-describedby="emailHelp">
                <small id="headerHelp" class="form-text text-muted">输入请求的接口头部...</small>
            </div>
            <div class="form-group">
                <label for="param">请求参数（JSON）：</label>
                <input type="text" class="form-control" id="param" aria-describedby="emailHelp">
                <small id="paramHelp" class="form-text text-muted">输入请求的接口参数...</small>
            </div>
            <div class="form-group">
                <label for="method">请求方法：</label>
                <div class="custom-control custom-control-inline custom-radio mb-2">
                    <input type="radio" checked class="custom-control-input" id="inlineRadio1" name="method"
                           value="GET">
                    <label class="custom-control-label" for="inlineRadio1">GET</label>
                </div>
                <div class="custom-control custom-control-inline custom-radio mb-2">
                    <input type="radio" class="custom-control-input" id="inlineRadio2" name="method"
                           value="POST">
                    <label class="custom-control-label" for="inlineRadio2">POST</label>
                </div>
                <div class="custom-control custom-control-inline custom-radio mb-2">
                    <input type="radio" class="custom-control-input" id="inlineRadio3" name="method" value="PUT">
                    <label class="custom-control-label" for="inlineRadio3">PUT</label>
                </div>
                <div class="custom-control custom-control-inline custom-radio mb-2">
                    <input type="radio" class="custom-control-input" id="inlineRadio4" name="method"
                           value="PATCH">
                    <label class="custom-control-label" for="inlineRadio4">PATCH</label>
                </div>
                <div class="custom-control custom-control-inline custom-radio mb-2">
                    <input type="radio" class="custom-control-input" id="inlineRadio5" name="method"
                           value="DELETE">
                    <label class="custom-control-label" for="inlineRadio5">DELETE</label>
                </div>
            </div>
            <button class="btn btn-primary" id="submit">提交</button>
            <div>
                <div class="mt-3">返回结果：</div>
                <pre id="result-container" class="mt-3"></pre>
            </div>
        </div>
    </div>
</div>
<script src="{{asset('js/jquery-3.5.0.min.js')}}"></script>
<script src="{{asset('js/popper.min.js')}}"></script>
<script src="{{asset('js/bootstrap.min.js')}}"></script>
<script>
    $(function () {
        const getData = function () {
            let param = $('#param').val()
            let url = $('#url').val()
            let method = $('input[name=method]:checked').val()
            let header = $('#header').val()
            if (!url) {
                return alert('url不能为空！')
            }
            return {param, url, method, header}
        }
        $('#submit').on('click', function () {
            let data = getData()
            $.ajax({
                url: '/test/api',
                type: "post",
                data: data,
                dataType: 'json',
                success: (res) => {
                    console.log(res);
                    /*JSON.stringify(res, null, "\t"); // 缩进一个tab
                    JSON.stringify(res, null, 4);    // 缩进4个空格*/
                    $("#result-container").text(JSON.stringify(res, null, 4));
                }
            })
        })
    })
</script>
</body>
</html>

<!DOCTYPE html>
<html lang="ko">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
        <title>HONOR LiNK DEMO</title>
        <link rel="stylesheet" href="{{asset('/app.css')}}">
        <link href='//spoqa.github.io/spoqa-han-sans/css/SpoqaHanSansNeo.css' rel='stylesheet' type='text/css'>
    </head>
    <body>
        <div class="login-form">
            <div>
                <h1>LOGIN</h1>
                <form method="GET" action="signin" autocomplete="off">
                    <div>
                        <input type="text" name="id" placeholder="ID" required/>
                    </div>
                    <div>
                        <input type="password" name="password" placeholder="PASSWORD" required/>
                    </div>
                    <div><button type="submit" class="login-btn">Login</button></div>
                    @if(session()->has('message'))
                        <script>
                            alert("{{ session('message') }}");
                        </script>
                    @endif
                </form>
            </div>
        </div>
    </body>
</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

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
                font-size: 84px;
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
        </style>
    </head>
    <body>

        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/dashboard') }}">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">Register</a>
                        @endif
                    @endauth
                </div>
            @endif

            <div class="content">
                <div class="title m-b-md">
                    pixelate
                </div>

{{--                <form action="{{route('look')}}" method="post" autocomplete="off">
                    {!! csrf_field() !!}

                    <div class="form-group">
                        <div class="input-group">
                            <input type="text" class="form-control" name="term">

                            <span class="input-group-btn">
                                <button class="btn btn-secondary" >
                                    Search
                                    <i class="fa fa-fw fa-search"></i>
                                </button>
                            </span>
                        </div>
                    </div>
                </form>--}}
                <div class="links">
                    <a href="{{env('QUASAR_URL')}}" target="_blank">Pixelate</a>
                    <a href="{{env('CLIENT_URL')}}/#/login" target="_blank">Deja-vu</a>
                    <a href="https://github.com/Atemndobs/pixelate/tree/master" target="_blank">Github</a>
                    <a href="{{env('APP_URL')}}/api/docs" target="_blank">API DOCS</a>
                    <a href="{{env('APP_URL')}}/laravel-websockets" target="_blank">WEB SOCKETS</a>
                    <a href="{{env('APP_URL')}}/telescope" target="_blank">telescope</a>
                </div>
            </div>
        </div>
    </body>
</html>

<script>

/*    var channel = Echo.channel('comment-channel');
    channel.listen('.CommentCreatedEvent', function(data) {
        alert(JSON.stringify(data));
    });*/

</script>

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

        </div>
    @endif

    <div class="content">
        <div class="title m-b-md">
            pixelate
        </div>
{{--
        <form action="{{route('look')}}" method="post" autocomplete="off">
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
        </form>
        <div class="links">
            Search Results
            <table class="table table-striped table-inverse table-responsive">
                <thead class="thead-inverse">
                <tr>
                    <th>titlesn </th>
                    <th>descriptions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($designs as $design)
                <tr>

                    <td scope="row">
                        {{$design->title}}
                    </td>
                    <td scope="row">
                        {{$design->description}}
                    </td>
                </tr>
                <tr>

                </tr>
                @endforeach
                </tbody>
            </table>
            <table>
                <tr>
                    <td></td>
                </tr>
            </table>
        </div>--}}
    </div>
</div>
</body>
</html>
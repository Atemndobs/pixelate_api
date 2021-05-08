@extends('layouts.app')

@section('content')
    <div class="container">
        <div >
            @foreach ($groups as $group)
                <section class="content-header pb-md-5 " >
                    <h4 class="pull-right" onclick="openGroupPage({{json_encode($group)}})">
                        <a class="btn btn-info pull-right text-uppercase" style="margin-top: -10px;margin-bottom: 5px"
                        >Set {{$group}} Variables</a>
                    </h4>
                </section>
            @endforeach
            </div>
        </div>
@endsection

<script type="text/javascript">
    function openGroupPage(group){
        window.location.href = "/settings/"+group
    }
</script>

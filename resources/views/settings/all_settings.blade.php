@extends('layouts.app')

@section('content')
    <div class="container">
        <div >

            <th colspan="3">
                <a href="{{ route('settings') }}" class="btn btn-default text-black-50 bg-info"> <- Back to Menu</a>
            </th>

                <section class="content-header pb-md-5 " >
                    <div class="table-responsive">
                        <table class="table" id="posts-table">
                            <thead>
                            <tr>
                                <th>Group</th>
                                <th>Name</th>
                                <th>Payload</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach ($settings as $setting)

                                <tr>
                                    <td>{{ $group }}</td>
                                    <td>{{ $setting['name'] }}</td>
                                    <td>{{ $setting['payload']  }}</td>

                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
    </div>

@endsection

<script type="text/javascript">
    function openGroupPage(group){
        window.location.href = "/settings/"+group
    }
</script>

@extends('layouts.app')

@section('content')
    <section class="content-header container">
        <h1>
            Add new setting
        </h1>
    </section>
    <div class="content container">

        <div class="box box-primary container" >
            <div class="box-body">
                <div class="row ">
                    <div class="container"></div>
                    @foreach ($groups as $group)
                        {!! Form::open(['route' => 'settings.store', 'method' => 'post']) !!}
                    <div class="form-group col-lg-12  text-uppercase text-black-50" >
                        {!! Form::label('general', "{$group} Config Settings") !!}
                    </div>
                        @if($group === 'general')
                            @include('settings.genaral')
                        @endif
                        @if($group === 'pusher')
                            @include('settings.pusher')
                        @endif
                        @if($group === 'algolia')
                            @include('settings.algolia')
                        @endif
                        @if($group === 'weather')
                            @include('settings.weather')
                        @endif
                        @if($group === 'aws')
                            @include('settings.aws')
                        @endif
                </div>
            {!! Form::close() !!}
                    @endforeach


                <!-- Submit Field -->


                </div>
            </div>
        </div>
    </div>
@endsection



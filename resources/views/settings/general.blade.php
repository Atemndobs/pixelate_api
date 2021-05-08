@extends('layouts.app')

@section('content')
    <section class="content-header container">
        <h4>
            {!! Form::label('general', "Edit {$group} settings") !!}
        </h4>
    </section>
    <div class="content container">

        <div class="box box-primary container" >
            <div class="box-body">
                <div class="row ">
                    <div class="container"></div>
                        {!! Form::open(['route' => 'settings.store', 'method' => 'post']) !!}

                        <!-- General Field -->
                        <div class="container">
                            @foreach ($names[$group] as $field)
                                {!! Form::label('general', $field) !!}
                                {!! Form::text($field, null, ['class' => 'form-control']) !!}

                            @endforeach
                                {!! Form::hidden('group', $group, ['class' => 'form-control']) !!}
                        </div>


                        <div class="form-group col-sm-12">
                            {!! Form::submit('Save',['class' => 'btn btn-primary']) !!}
                            <a href="{{ route('settings') }}" class="btn btn-default">Cancel</a>
                        </div>
                </div>
            {!! Form::close() !!}
            </div>
        </div>
    </div>
    </div>
@endsection



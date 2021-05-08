<div class="table-responsive">
    <table class="table" id="posts-table">
        <thead>
        <tr>
            <th>Group</th>
            <th>Name</th>
            <th>Locked</th>
            <th>Payload</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>

        @foreach($settings ?? '' as $setting)
               <tr>
                   <td>{{ $setting->group }}</td>
               <td>{{ $setting->name  }}</td>
               <td>{{ $setting->locked  }}</td>
               <td>{{ $setting->payload  }}</td>
                   <td>
                 {!! Form::open(['route' => ['settings.destroy', 1], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <div class='btn-group'>
                            <a href="{{ route('settings.show', [1]) }}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                            <a href="{{ route('settings.edit', [1]) }}" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                            {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                        </div>
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach

        </tbody>
    </table>
</div>

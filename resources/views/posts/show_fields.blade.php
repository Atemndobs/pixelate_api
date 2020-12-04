<!-- Caption Field -->
<div class="form-group">
    {!! Form::label('caption', 'Caption:') !!}
    <p>{{ $post->caption }}</p>
</div>

<!-- Imageurl Field -->
<div class="form-group">
    {!! Form::label('imageUrl', 'Imageurl:') !!}
    <p>{{ $post->imageUrl }}</p>
</div>

<!-- Location Field -->
<div class="form-group">
    {!! Form::label('location', 'Location:') !!}
    <p>{{ $post->location }}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', 'Created At:') !!}
    <p>{{ $post->created_at }}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', 'Updated At:') !!}
    <p>{{ $post->updated_at }}</p>
</div>


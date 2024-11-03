<div class="form-group">
    {{Form::label('name', 'Title')}}
    {{Form::text('name', null, ['class'=>'form-control'])}}
</div>
<div class="form-group">
    {{Form::label('description', 'Description')}}
    {{Form::textarea('description', null, ['class'=>'form-control'])}}
</div>
<div class="form-group">
    {{Form::label('status', 'Status')}}
    {{Form::select('status', config('constants.weekly_challenge_statuses'), null, ['class'=>'form-control select'])}}
</div>
<div class="form-group">
    {{Form::label('end', 'Deadline')}}
    {{Form::text('end', null, ['class'=>'form-control'])}}
</div>

<div class="form-group">
    {{Form::label('parent_class', 'Challenge type')}}
    {{Form::select('parent_class', [
        'Step'=>'Step',
        'Combination'=>'Combination',
        'Choreography'=>'Choreography'
    ], null, ['class'=>'form-control select'])}}
</div>
<div class="form-group">
    {{Form::label('parent_id', 'Status')}}
    {{Form::select('parent_id', [], null, ['class'=>'form-control'])}}
</div>

@extends('layouts.fullwidthv1')
@section('content')
<div class="row">
	<div class="col-lg-6 col-lg-offset-3">
		<div class="card card-solid">
			{{Form::open(['url'=>route('choreographies.store'), 'files' => true, 'method' => 'POST'])}}
			{{Form::hidden('dance_id', $dance_id)}}
			<div class="card-body">
				@include('choreographies.partials._form')
			</div>
			<div class="card-footer">
				{{Form::submit('Create', ['class' => 'btn btn-success'])}}
			</div>
			{{Form::close()}}
		</div>
	</div>
</div>
@stop

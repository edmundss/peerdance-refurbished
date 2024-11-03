@extends('layouts.fullwidthv1')
@section('content')
<div class="row">
	<div class="col-lg-6 col-lg-offset-3">
		<div class="card card-solid">
			{{Form::model($step, ['url'=>route('steps.update', $step), 'files' => true, 'method' => 'PUT'])}}
			{{Form::hidden('dance_id', $dance_id)}}
			<div class="card-body">
				@include('steps.partials._form')
			</div>
			<div class="card-footer">
				{{Form::submit('Save', ['class' => 'btn btn-success'])}}
			</div>
			{{Form::close()}}
		</div>
	</div>
</div>
@stop

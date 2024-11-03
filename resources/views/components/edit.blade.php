@extends('layouts.fullwidthv1')
@section('content')
<div class="row">
	<div class="col-lg-6 col-lg-offset-3">
		<div class="card card-solid">
			{{Form::model($component, ['url'=>route('component.update', $component), 'files' => true, 'method' => 'PUT'])}}
			<div class="card-body">
				@include('components.partials._form')
			</div>
			<div class="card-footer">
				{{Form::submit('Save', ['class' => 'btn btn-success'])}}
			</div>
			{{Form::close()}}
		</div>
	</div>
</div>
@stop
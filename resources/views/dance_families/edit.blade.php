@extends('layouts.fullwidthv1')
@section('content')
<div class="row">
	<div class="col-lg-4 col-lg-offset-4">
		<div class="card card-solid">
			{{Form::model($dance_family, ['url'=>route('dance_family.update', $dance_family), 'method' => 'PUT'])}}
			<div class="card-body">
				@include('dance_families.partials._form')
			</div>
			<div class="card-footer text-right">
				{{Form::submit('Save', ['class' => 'btn btn-success'])}}
			</div>
			{{Form::close()}}
		</div>
	</div>
</div>
@stop
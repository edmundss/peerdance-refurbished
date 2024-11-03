@extends('layouts.fullwidthv1')
@section('content')
<div class="row">
	<div class="col-lg-4 col-lg-offset-4">
		<div class="card card-solid">
			{{Form::open(['url'=>route('dances.store'), 'files' => true, 'method' => 'POST'])}}
			<div class="card-body">
				@include('dances.partials._form')
			</div>
			<div class="card-footer text-right">
				{{Form::submit('Create', ['class' => 'btn btn-success'])}}
			</div>
			{{Form::close()}}
		</div>
	</div>
</div>
@stop

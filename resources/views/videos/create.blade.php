@extends('layouts.fullwidthv1')

@section('content')
	<div class="row">
		<div class="col-lg-6 col-lg-offset-3">
			<div>
				<div class="card card-solid">
					{{Form::open(['url' => route('videos.store')])}}
					{{Form::hidden('parent_class', 'Choreography')}}
					{{Form::hidden('parent_id', $parent->id)}}
					<div class="card-body">
						@include('videos.partials._form')
					</div>
					<div class="card-footer text-right">
						{{Form::submit('Add', ['class' =>'btn btn-success'])}}
					</div>
					{{Form::close()}}
				</div>
			</div>
		</div>
	</div>
@stop

@extends('layouts.fullwidthv1')

@section('content')
	<div class="row">
		<div class="col-lg-6 col-lg-offset-3">
			<div>
				<div class="card card-solid">
					{{Form::model($video, ['url' => route('video.update', $video), 'method' => 'PUT'])}}
					{{Form::hidden('parent_class', null)}}
					{{Form::hidden('parent_id', null)}}
					<div class="card-body">
						@include('videos.partials._form')
					</div>
					<div class="card-footer text-right">
						{{Form::submit('Save', ['class' =>'btn btn-success'])}}
					</div>
					{{Form::close()}}
				</div>
			</div>
		</div>
	</div>
@stop
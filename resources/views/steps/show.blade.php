@extends('layouts.fullwidthv1')

@section('content')
<div class="row">
	<div class="col-lg-4">
		<div class="card card-solid">
			<header class="card-heading">
				<h1 class="card-title">Step info</h1>
				<ul class="card-actions icons right-top">
                    <li>
                      <a href="{{route('steps.edit', $step->id)}}">
                        <i class="zmdi zmdi-edit"></i>
                      </a>
                    </li>
                </ul>
			</header>
				<table class="table no-margin">
					<tr>
						<th class="col-lg-3" ="col-lg-2">Name</th>
						<td>{{$step->title}}</td>
					</tr>
					<tr>
						<th class="col-lg-3" ="col-lg-2"> <nobr>Alternate Names
						<button class="btn btn-xs  btn-primary m-0 p-5" data-toggle="modal" data-target="#alternate-name-modal"><i class="zmdi zmdi-plus"></i></button></nobr></th>

						<td>
							@forelse($step->alternate_names as $n)
								{{$n->name}}@if(!$loop->last),@endif
							@empty
								Unknown
							@endforelse
						</td>
					</tr>
					<tr>
						<th>Dance</th>
						<td><a href="{{route('dances.show', $step->dance_id)}}">{{$step->dance->title}}</a></td>
					</tr>
					<tr>
						<th colspan="2">Description</th>
					</tr>
				</table>
			<div class="card-body" id='description'>
				<p>
					{!!(strlen($step->description) >0)?$step->description:'No description'!!}
				</p>
			</div>
		</div>
    @include('layouts.partials._comments')
	</div>
	<div class="col-lg-8">
		<div class="card card-heading-right video-list-card">
          <header class="card-heading md-bg-grey-900 p-0">
            <h2 class="card-title m-t-15 m-b-15 m-l-20">Videos</h2>
            <ul class="nav nav-pills nav-stacked">
            	@forelse($step->videos as $v)
		        	<li class="{{($v->id == $video->id)?'active':''}} m-0 p-0"><a href="?vid={{$v->id}}">{{$v->title}}</a></li>
		        @empty
		        	<li class="m-0 p-0"><a href="javascript:void(0)">No videos here</a></li>
		        @endforelse
		    </ul>
          </header>
          <section>
          	@if($video)
          		<div class="embed-responsive embed-responsive-16by9">
          			<iframe class="embed-responsive-item" src="https://www.youtube.com/embed/{{$video->video_id}}?start={{$video->start}}&end={{$video->end}}" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
          		</div>
			@else
            	<div class="card-body">
            		<img src="{{ asset('assets/img/icons/misc/Logo_Youtube_Error.png') }}">
              		<p>I'm hungry! Feed me some videos!</p>
              	</div>
			@endif
              <div class="card-footer border-top">
                <ul class="card-actions icons fab-action left">
                  <li>
                    <button class="btn btn-primary btn-fab" data-toggle="modal" data-target="#video-modal"><i class="zmdi zmdi-plus"></i></button>
                  </li>
                </ul>
                @if($video)
                <ul class="card-actions icons right-bottom">
                  <li>
                    <a href="javascript:void(0)" data-toggle="tooltip" data-original-title="Like video">
                      <i class="zmdi zmdi-favorite"></i>
                    </a>
                  </li>
                  <li>
                    <a href="{{ route('videos.edit', $video) }}">
                      <i class="zmdi zmdi-edit"></i>
                    </a>
                  </li>
                  <li>
                    <a href="javascript:void(0)">
                      <i class="zmdi zmdi-delete"></i>
                    </a>
                  </li>
                </ul>
                @endif
              </div>
              @if($video)
              <table class="table info-table" style="margin-top: -59px">
              	<tr>
              		<th>Video type</th>
              		<td>{{config('constants.video_types')[$video->type]}}</td>
              	</tr>
				@if($video->creator)
				<tr>
					<th class="col-lg-3">Created</th>
					<td><a href="{{ route('users.show', $video->creator) }}">{{$video->creator->name}}</a>, {{date('d-m-Y', strtotime($video->created_at))}}</td>
				</tr>
				@endif
				@if($video->editor)
				<tr>
					<th class="col-lg-3">Last updated</th>
					<td><a href="{{ route('users.show', $video->editor) }}">{{$video->editor->name}}</a>, {{date('d-m-Y', strtotime($video->updated_at))}}</td>
				</tr>
				@endif
              	<tr>
              		<th>Timeframe</th>
              		<td>{{isset($video->start)?$video->start.'s':'Start not set'}} - {{isset($video->end)?$video->end.'s':'End not set'}}</td>
              	</tr>

              </table>
              @endif
            </section>
        </div>
	</div>
</div>
@stop

@section('scripts')
	<script type="text/javascript">
		var protocol = (location.protocol == 'https:') ? 'https' : 'http';

		var videoEmbed = {
		    invoke: function(){

		        $('#description').html(function(i, html) {
		            return videoEmbed.convertMedia(html);
		        });

		    },
		    convertMedia: function(html){
		        var pattern1 = /(?:http?s?:\/\/)?(?:www\.)?(?:vimeo\.com)\/?(.+)/g;
		        var pattern2 = /(?:http?s?:\/\/)?(?:www\.)?(?:youtube\.com|youtu\.be)\/(?:watch\?v=)?(.+)/g;
		        var pattern3 = /([-a-zA-Z0-9@:%_\+.~#?&//=]{2,256}\.[a-z]{2,4}\b(\/[-a-zA-Z0-9@:%_\+.~#?&//=]*)?(?:jpg|jpeg|gif|png))/gi;

		        if(pattern1.test(html)){
		           var replacement = '<iframe width="420" height="345" src="//player.vimeo.com/video/$1" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';

		           var html = html.replace(pattern1, replacement);
		        }


		        if(pattern2.test(html)){
		              var replacement = '<div class="embed-responsive embed-responsive-16by9"><iframe class="embed-responsive-item" src="'+protocol+'://www.youtube.com/embed/$1" frameborder="0" allowfullscreen></iframe></div>';
		              var html = html.replace(pattern2, replacement);
		        }


		        if(pattern3.test(html)){
		            var replacement = '<a href="$1" target="_blank"><img class="sml" src="$1" /></a><br />';
		            var html = html.replace(pattern3, replacement);
		        }

		        return html;
		    }
		}
		$(function(){
    		videoEmbed.invoke();
		})
	</script>
@stop

@section('modals')
  <div id="alternate-name-modal" class="modal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
          <h4 class="modal-title">Add alternate name</h4>
          </div>
            {{Form::open(array('url'=>route('alternate-names.store'), 'method' => 'POST'))}}
            {{Form::hidden('parent_id', $step->id)}}
            {{Form::hidden('parent_class', 'Step')}}
          <div class="modal-body">
            <div class="form-group">
              {{Form::label('name', 'Alternate name for this step')}}
              {{Form::text('name', null, ['class' => 'form-control'])}}
            </div>
          </div>
          <div class="modal-footer">
              {{Form::submit('Add', array('class'=>'btn btn-primary'))}}
            </div>
          {{Form::close()}}
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
  </div>

  <div id="video-modal" class="modal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
          <h4 class="modal-title">Add video</h4>
          </div>
            {{Form::open(['url' => route('videos.store')])}}
			{{Form::hidden('parent_class', 'Step')}}
			{{Form::hidden('parent_id', $step->id)}}
        	<div class="modal-body">
				@include('videos.partials._form')
        	</div>
          <div class="modal-footer">
              {{Form::submit('Add', array('class'=>'btn btn-primary'))}}
            </div>
          {{Form::close()}}
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
  </div>
@stop

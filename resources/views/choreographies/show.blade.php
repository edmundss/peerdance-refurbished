@extends('layouts.fullwidthv1')

@section('content')
<div class="row">
	<div class="col-lg-6">
		<div class="card card-solid">
			<header class="card-heading">
				<h2 class="card-title">About Routine</h2>
				<ul class="card-actions icons right-top">
                    <li>
                      <a href="{{route('choreographies.edit', $choreography->id)}}">
                        <i class="zmdi zmdi-edit"></i>
                      </a>
                    </li>
                </ul>
			</header>
				<table class="table no-margin">
					<tr>
						<th class="col-lg-3" ="col-lg-2">Name</th>
						<td>{{$choreography->title}}</td>
					</tr>
					<tr>
						<th>Dance</th>
						<td><a href="{{route('dances.show', $choreography->dance_id)}}">{{$choreography->dance->title}}</a></td>
					</tr>
					<tr>
						<th>Author</th>
						<td>{{$choreography->author ?? 'unknown'}}</td>
					</tr>
					<tr>
						<th colspan="2">Description</th>
					</tr>
				</table>
				<div class="card-body" id='description'>
					<p>
						{!!(strlen($choreography->description) >0)?$choreography->description:'Nav'!!}
					</p>
				</div>
		</div>
		<div class="card card-solid">
			<header class="card-heading">
				<h2 class="card-title">List of videos</h2>
				<ul class="card-actions icons right-top">
                    <li>
                      <a href="{{route('videos.create')}}?parent_id={{$choreography->id}}&parent_class=Choreography">
                        <i class="zmdi zmdi-plus"></i>
                      </a>
                    </li>
                </ul>
			</header>
			<table class="table no-margin">
				<thead>
					<tr>
						<th>Name</th>
						<th>Type</th>
						<th>Created</th>
						<th>Date</th>
					</tr>
				</thead>
				<tbody>
					@foreach($choreography->videos as $v)
					<tr>
						<td><a href="?vid={{$v->id}}">{{$v->title}}</a></td>
						<td>{{$video_types[$v->type]}}</td>
						<td>--</td>
						<td>{{$v->created_at}}</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>

    @include('layouts.partials._comments')
	</div>
	<div class="col-lg-6">
		<div class="card card-solid">
			<header class="card-heading">
				<h2 class="card-title"><strong>Video:</strong> {{($video)?$video->title:'Nav video'}}</h2>
			</header>
			@if($video)
				<div class="embed-responsive embed-responsive-16by9">
					<div id="player" class=""></div>
				</div>
			@endif
			<div class="card-body">
				<strong>Step stranscription</strong>
				<button class="btn btn-xs btn-primary m-0" data-toggle="modal" data-target="#component-form">Add</button>
				<div style="max-height: 400px !important; overflow-y: scroll; overflow-x: hidden;">

				@if($video)
				<table class="table no-margin">
					<thead>
						<tr>
							<th></th>
							<th>#</th>
							<th>Step</th>
							<th class="col-lg-1">Start</th>
							<th class="col-lg-1">End</th>
							<th></th>
						</tr>
					</thead>
					<tbody id="component-table-body">
					@forelse($choreography->components()->orderBy('order_number')->get() as $c)
					<tr>
						{{Form::open(['url' => route('videos.component')])}}
						{{Form::hidden('video_id', $video->id)}}
						{{Form::hidden('component_id', $c->id)}}
						<td>
							@if($video->components->contains($c->id))
							<input type="checkbox" data-id="{{$c->id}}">
							@endif
						</td>
						<td class="order-number" data-id="{{$c->id}}">
							{{$c->order_number}}
						</td>
						<td>@if($c->step_id)<a href="{{route('steps.show', $c->step_id)}}">{{$c->step->title}}</a>@if($c->description) - @endif @endif{{$c->description}}</td>
						<td>{{Form::text('start_'. $c->id, ($video->components->contains($c->id))?$video->components->find($c->id)->pivot->start:null, ['class'=>'input-sm start-seconds second-input-' . $c->id])}}</td>
						<td>
							{{Form::text('end_' .$c->id, ($video->components->contains($c->id))?$video->components->find($c->id)->pivot->end:null, ['class'=>'input-sm end-seconds second-input-' . $c->id ])}}
						</td>
						<td>
						<nobr>
							<button class="btn btn-sm btn-primary p-5 m-0"><i class="zmdi zmdi-save"></i></button>
							<a href="{{route('components.edit', $c->id)}}" class="btn btn-sm btn-primary p-5 m-0"><i class="zmdi zmdi-edit"></i></a>
							<a href="{{route('components.delete', $c->id)}}" class="btn btn-sm btn-primary p-5 m-0"><i class="zmdi zmdi-delete"></i></a>

						</nobr>
						</td>
						{{Form::close()}}
					</tr>
					@empty
						<p>This video doesn't step transcription </p>
					@endforelse
					</tbody>
				</table>
				@else
				<table class="table no-margin">
					<thead>
						<tr>
							<th>#</th>
							<th>Solis</th>
						</tr>
					</thead>
					<tbody id="component-table-body">
					@forelse($choreography->components as $c)
					<tr>
						<td>{{$c->order_number}}</td>
						<td>@if($c->step_id)<a href="{{route('step.show', $c->step_id)}}">{{$c->step->title}}</a>@if($c->description) - @endif @endif{{$c->description}}</td>
					</tr>
					@empty
						<p>No video selected</p>
					@endforelse
					</tbody>
				</table>
				@endif
				</div>
			</div>
			<div class="card-footer text-right">
				<button class="btn btn-primary" onclick="play()">Start 'Dance along'</button>
			</div>
		</div>
	</div>
</div>
@stop

@section('scripts')
	<script src="{{asset('plugins/select2/dist/js/select2.min.js')}}"></script>
	<script src="{{asset('plugins/jQueryUI/jquery-ui.min.js')}}"></script>
	<script type="text/javascript">

		$(function(){

			$("#step_id").select2({
			    placeholder: "Select the step from library",
			    ajax: {
				    dataType: 'json',
				    url: "{{route('steps.select2')}}?dance_id={{$choreography->dance_id}}",
				    delay: 250,
				    selectOnBlur: true,
				  	data: function(params) {
				    	return {q: params.term};
				    },
				    processResults: function(data)
				    {
				        return { results: data }
				    },
			    },
			});

	    	$('#component-table-body').sortable({
	    		axis:'y',
	    		stop: function() {
	    			var i = 0;
	    			var order = [];
	    			$('.order-number').each(function(){
	    				i++;

	    				$(this).html(i);

	    				order.push({
	    					id: $(this).attr('data-id'),
	    					order_number: i,
	    				});
	    			})

	    			$.get(
	    				"{{route('components.update-order')}}", {items:order}, function(){
	    					alertify.success('Yey, The new order is saved!');
	    				})
	    				.fail(function(){
	    					alertify.error('Something went wrong. The new sequence is not saved.');
	    				});
	    		}
	    	});

		});
	</script>

	<script>
var startSeconds = 0;
var endSeconds = 0;

$(function() {
console.log('hello?');
    $('input[type=checkbox]').on('click', function() {
        var data_id = $(this).attr('data-id');

        startSeconds = 0;
        endSeconds = 0;

        if ($(this).is(':checked')) {
            $('.second-input-' + data_id).addClass('use-time');
        } else {
            $('.second-input-' + data_id).removeClass('use-time');
        }

        $('.start-seconds.use-time').each(function() {
            var value = parseInt($(this).val());
            if (startSeconds == 0) {
                startSeconds = value;
            }
            startSeconds = (value < startSeconds) ? value : startSeconds;
        });

        $('.end-seconds.use-time').each(function() {
            var value = parseInt($(this).val());
            endSeconds = (value > endSeconds) ? value : endSeconds;
        });

        console.log('start:' + startSeconds + ' end:' + endSeconds);
    })
})

// 2. This code loads the IFrame Player API code asynchronously.
var tag = document.createElement('script');

tag.src = "https://www.youtube.com/iframe_api";
var firstScriptTag = document.getElementsByTagName('script')[0];
firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

// 3. This function creates an <iframe> (and YouTube player)
//    after the API code downloads.
var player;

function onYouTubeIframeAPIReady() {
    player = new YT.Player('player', {
        height: '390',
        width: '640',
        videoId: '{{($video)?$video->video_id:""}}',
        events: {
            'onReady': onPlayerReady,
            'onStateChange': onPlayerStateChange
        },
    });

}


function play() {
    player.loadVideoById({
        videoId: '{{($video)?$video->video_id:""}}',
        startSeconds: startSeconds,
        endSeconds: endSeconds
    });
    player.playVideo();
}
// 4. The API will call this function when the video player is ready.
function onPlayerReady(event) {
    //event.target.playVideo();
}

// 5. The API calls this function when the player's state changes.
//    The function indicates that when playing a video (state=1),
//    the player should play for six seconds and then stop.
var done = false;

function onPlayerStateChange(event) {
    //console.log(event);
    if (event.data == 0) {
        play();

    }
}

function stopVideo() {
    player.stopVideo();
}
    </script>
	@if(false)
    @endif
@stop


@section('modals')
  <div id="component-form" class="modal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
          <h4 class="modal-title">Add transcript to this routine</h4>
          </div>
            {{Form::open(array('url'=>route('components.store'), 'method' => 'POST'))}}
            {{Form::hidden('parent_id', $choreography->id)}}
            {{Form::hidden('parent_class', 'Choreography')}}
          <div class="modal-body">
            @include('components.partials._form')
          </div>
          <div class="modal-footer">
              {{Form::submit('Add', array('class'=>'btn btn-primary'))}}
            </div>
          {{Form::close()}}
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
  </div>
@stop

@section('css')
	<link rel="stylesheet" href="{{asset('plugins/select2/dist/css/select2.min.css')}}">
@stop

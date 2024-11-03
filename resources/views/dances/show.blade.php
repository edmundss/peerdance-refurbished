@extends('layouts.fullwidthv1')

@section(Config::get('chatter.yields.head'))
    <link href="{{ url('/vendor/devdojo/chatter/assets/vendor/spectrum/spectrum.css') }}" rel="stylesheet">
  <link href="{{ url('/vendor/devdojo/chatter/assets/css/chatter.css') }}" rel="stylesheet">
  @if($chatter_editor == 'simplemde')
    <link href="{{ url('/vendor/devdojo/chatter/assets/css/simplemde.min.css') }}" rel="stylesheet">
  @elseif($chatter_editor == 'trumbowyg')
    <link href="{{ url('/vendor/devdojo/chatter/assets/vendor/trumbowyg/ui/trumbowyg.css') }}" rel="stylesheet">
    <style>
      .trumbowyg-box, .trumbowyg-editor {
        margin: 0px auto;
      }
    </style>
  @endif
@stop

@section('content')
<div class="row">
	<div class="col-md-3">
  <!-- Widget: user widget style 1 -->
  <div class="card card type--profile">
    <!-- Add the bg color to the header using any of the bg-* classes -->
    <header class="card-heading">
      <img class="img-circle" src="{{asset('image/dance/'.$dance->id.'/logo.jpg')}}" alt="User Avatar">
      <ul class="card-actions icons right-top">
                            <li class="dropdown">
                              <a href="javascript:void(0)" data-toggle="dropdown">
                                <i class="zmdi zmdi-more-vert"></i>
                              </a>
                              <ul class="dropdown-menu dropdown-menu-right btn-primary">
                                <li>
                                  <a href="{{route('dances.edit', $dance)}}">Edit</a>
                                </li>
                              </ul>
                            </li>
                          </ul>
    </header>
    <div class="card-body">
      <h3 class="name">{{$dance->title}}</h3>
      <span class="title">{{$dance->description}}</span>
      <div class="btn-group">
        <button type="button" class="btn btn-primary dropdown-toggle btn-round btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <nobr>
                    @if(Auth::check())
                      @if($dance->users->contains($session_owner->id))
                        In your repertoire
                      @else
                        Not in your repertoire
                      @endif
                    @endif <i class="zmdi zmdi-caret-down"></i></nobr>
                <div class="ripple-container"></div></button>
                <ul class="dropdown-menu">
                  <li><a href="{{route('dances.toggle', $dance->id)}}">

                    @if(Auth::check())
                      @if($dance->users->contains($session_owner->id))
                        Remove from your repertoire
                      @else
                        Add to your repertoire
                      @endif
                    @else
                        Add to your repertoire
                    @endif
                </a></li>
                </ul>
              </div>
        </div>
        <div class="card-footer">
          <div class="fb-like" data-href="{{Request::url()}}" data-layout="standard" data-action="like" data-size="small" data-show-faces="true" data-share="true"></div>
        </div>
      </div><!-- /.row -->
    @include('layouts.partials._comments')
    </div>

    <div class="col-lg-9">
        <div class="card">
          <header class="card-heading p-0">
            <div class="tabpanel m-b-30">
              <ul class="nav nav-tabs nav-justified">
                <li class="" role="presentation">
                  <a href="#chatter" data-toggle="tab" aria-expanded="true">
                    Discussions
                    <div class="ripple-container"></div>
                  </a>
                </li>

                <li class="active" role="presentation">
                  <a href="#steps" data-toggle="tab" aria-expanded="true">
                    Steps
                    <div class="ripple-container"></div>
                  </a>
                </li>

                <li role="presentation" class="">
                  <a href="#combos" data-toggle="tab" aria-expanded="false">
                    Combos
                    <div class="ripple-container"></div>
                  </a>
                </li>

                <li role="presentation" class="">
                  <a href="#routines" data-toggle="tab" aria-expanded="false">
                    Routines
                    <div class="ripple-container"></div>
                  </a>
                </li>

              </ul>
            </div>
            <ul class="card-actions icons right-top">
          <li class="dropdown">
            <a href="javascript:void(0)" data-toggle="dropdown" aria-expanded="false">
              <i class="zmdi zmdi-plus"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-right btn-primary">
              <li role="presentation"><a role="menuitem" tabindex="-1" href="{{route('steps.create')}}?dance_id={{$dance->id}}">Step</a></li>
              <li role="presentation"><a role="menuitem" tabindex="-1" href="{{route('combinations.create')}}?dance_id={{$dance->id}}">Combo</a></li>
              <li role="presentation"><a role="menuitem" tabindex="-1" href="{{route('choreographies.create')}}?dance_id={{$dance->id}}">Routine</a></li>
              <li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:void(0)" data-toggle="modal" data-target="#discussion-form">Discussion</a></li>
            </ul>
          </li>
        </ul>
          </header>
            <div class="card-body">
              <div class="tab-content">
                <div class="tab-pane fadeIn" id="chatter">
                  <div class="panel">
                    <ul class="discussions">
                      @foreach($discussions as $discussion)
                        <li>
                          <a class="discussion_list" href="/{{ Config::get('chatter.routes.home') }}/{{ Config::get('chatter.routes.discussion') }}/{{ $discussion->category->slug }}/{{ $discussion->slug }}">
                            <div class="chatter_avatar">
                                 @if($discussion->user->avatar)
                                      <img src="{{$discussion->user->getAvatar('thumb')}}" alt="" class="circle lg"><br>
                                  @else
                                      <div class="discussion-avatars" style="height:60px; width:60px;">
                                          {{$discussion->user->name}}
                                      </div>
                                  @endif

                            </div>

                            <div class="chatter_middle">
                              <h3 class="chatter_middle_title">{{ $discussion->title }} <div class="chatter_cat" style="background-color:{{ $discussion->category->color }}">{{ $discussion->category->name }}</div></h3>
                              <span class="chatter_middle_details">@lang('chatter::messages.discussion.posted_by') <span data-href="/user">{{ ucfirst($discussion->user->{Config::get('chatter.user.database_field_with_user_name')}) }}</span> {{ \Carbon\Carbon::createFromTimeStamp(strtotime($discussion->created_at))->diffForHumans() }}</span>
                              @if($discussion->post[0]->markdown)
                                <?php $discussion_body = GrahamCampbell\Markdown\Facades\Markdown::convertToHtml( $discussion->post[0]->body ); ?>
                              @else
                                <?php $discussion_body = $discussion->post[0]->body; ?>
                              @endif
                              <p>{{ substr(strip_tags($discussion_body), 0, 200) }}@if(strlen(strip_tags($discussion_body)) > 200){{ '...' }}@endif</p>
                            </div>

                            <div class="chatter_right">

                              <div class="chatter_count"><i class="chatter-bubble"></i> {{ $discussion->postsCount[0]->total }}</div>
                            </div>

                            <div class="chatter_clear"></div>
                          </a>
                        </li>
                      @endforeach
                    </ul>
                  </div>

                  <div id="pagination">
                    {{ $discussions->links() }}
                  </div>
                </div>

                <div class="tab-pane fadeIn active" id="steps">
                  <table class="table" id="step-table">
                    <thead>
                      <tr>
                        <th>Name</th>
                        <th>Alternate Names</th>
                        <th>Difficulty</th>
                        <th>Your usage</th>
                        <th>Dancers</th>
                      </tr>
                    </thead>
                  </table>
                </div>
                <div class="tab-pane fadeIn" id="combos">
                  <table class="mdl-data-table no-footer table" id="combination-table">
                    <thead>
                      <tr>
                        <th>Name</th>
                        <th>Difficulty</th>
                        <th>Your usage</th>
                        <th>Dancers</th>
                      </tr>
                    </thead>
                  </table>
                </div>
                <div class="tab-pane fadeIn" id="routines">
                  <table class="table" id="choreography-table">
                    <thead>
                      <tr>
                        <th>Name</th>
                        <th>Difficulty</th>
                        <th>Your usage</th>
                        <th>Dancers</th>
                      </tr>
                    </thead>
                  </table>
                </div>
              </div>
            </div>
        </div>
      <div class="row">
        <div class="col-lg-6">
          <div class="card card-solid">
            <div class="card-heading">
              <h2 class="card-title">Dancers</h2>
            </div>
            <div class="card-body">
              <ul class="users-list p-0 clearfix">
              @foreach($dance->users as $u)
                <li class="text-center col-lg-3 dancers">
                  @if($u->avatar)
                      <img src="{{$u->getAvatar('thumb')}}" alt="" class="circle md m-b-5"><br>
                  @else
                      <div class="default-avatar-top avatar dancer-avatars m-b-5" style="height:40px; width:40px;">
                          {{$u->name}}
                      </div>
                  @endif
                  <a class="users-list-name" href="{{route('users.show', $u->id)}}">{{$u->name}}</a><br>
                  <span class="users-list-date">{{$u->created_at->format('d.M')}}</span>
                </li>
              @endforeach
              </ul><!-- /.users-list -->
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="card card-solid card-success">
            <header class="card-heading border-bottom card-blue">
              <h2 class="card-title">Step quiz</h2>
              <ul class="card-actions right-top">
                <li>
                  <button class="btn btn-xs pull-right btn-flat m-0 text-white" data-toggle="modal" data-target="#song-form">Add song</button>
                </li>
              </ul>
            </header>
            <div class="card-body">
            @if($dance->songs)
              <table class="table">
                <thead>
                  <tr>
                    <th></th>
                    <th>Song</th>
                    <th>BPM</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($dance->songs as $s)
                  <tr>
                    <td>{{Form::radio('spotify_song_id', $s->spotify_id)}}</td>
                    <td>{{$s->name}}</td>
                    <td>{{$s->tempo}}</td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            @else

            @endif
            </div>
            @if($session_owner)
              @if($session_owner->spotify_token)
          			<div class="card-footer">
          				<p><strong>Steps</strong></p>
          				<ul class="step-list">
          				</ul>
                  <div class="alert alert-info" role="alert" id="step-alert">
                    <strong>Get some moves!</strong> Ctrl+click on step in table above to add some moves to quiz.
                  </div>
          			</div>
                <div class="card-footer border-top">
                  <div class="form-group is-empty">
                      <div class="input-group">
                        <label class="control-label">Select playback device</label>
                        {{Form::select('player', $spotify_playback_devices, null, ['class' => 'form-control select'])}}
                        <span class="input-group-btn">
                          <button class="btn btn-info btn-fab" type="button" id="play"><i class="fa fa-play"></i></button>

                        </span>
                      </div>
                    </div>
                </div>
              @endif
            @endif
          </div>
        </div>
      </div>
    </div>
</div>
@stop


@section('modals')
  <div id="kantor" class="modal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
          <h4 class="modal-title">Step caller</h4>
          </div>
          <div class="modal-body">
            <div>Started: <span id="start"></span></div>
			      <div>BPM: <span id="bpm"></span></div>
            <div>BPM: <span id="count"></span></div>

            <h1 id="call"></h1>
            <h1><span id="progress"></span>/<span id="duration"></span></h1>

          </div>
          <div class="modal-footer">

          </div>
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
  </div>


  <div id="song-form" class="modal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
          <h4 class="modal-title">Add song</h4>
          </div>
            {{Form::open(array('url'=>route('songs.store'), 'method' => 'POST'))}}

          <div class="modal-body">
            @if($session_owner)
              @if($session_owner->spotify_token)
              <div class="form-group">
                {{Form::hidden('dance_id', $dance->id)}}
                {{Form::label('spotify_song_id', 'Search Spotify')}}
                {{Form::select('spotify_song_id', [], null, ['class' => 'form-control'])}}
                </div>
              @else
                To add song
                <a href="{{route('spotify.initial-authorization')}}?uri={{route('dances.show', $dance->id)}}"><img style="height:30px" src="{{asset('image/assets/spotify_connect.png')}}"></a>

              @endif
            @else
              You need to be logged in to add song
            @endif

          </div>
          <div class="modal-footer">
            @if($session_owner)
              @if($session_owner->spotify_token)

                {{Form::submit('Add', array('class'=>'btn btn-primary'))}}
              @endif
            @endif
            </div>
          {{Form::close()}}
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
  </div>

  <div id="discussion-form" class="modal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
          <h4 class="modal-title">Start new discussion</h4>
          </div>
            {{Form::open(array('url'=>route('chatter.discussion.store'), 'method' => 'POST'))}}
            {{Form::hidden('chatter_category_id', $dance->forum_category->id)}}
          <div class="modal-body">
            <div class="form-group">
              {{Form::label('title', 'Title')}}
              <input type="text" class="form-control" id="title" name="title" placeholder="@lang('chatter::messages.editor.title')" value="{{ old('title') }}" >
            </div>
            <div class="form-group">
              @if( $chatter_editor == 'tinymce' || empty($chatter_editor) )
                <label id="tinymce_placeholder">@lang('chatter::messages.editor.tinymce_placeholder')</label>
                <textarea id="body" class="richText" name="body" placeholder="">{{ old('body') }}</textarea>
              @elseif($chatter_editor == 'simplemde')
                <textarea id="simplemde" name="body" placeholder="">{{ old('body') }}</textarea>
              @elseif($chatter_editor == 'trumbowyg')
                <textarea class="trumbowyg" name="body" placeholder="@lang('chatter::messages.editor.tinymce_placeholder')">{{ old('body') }}</textarea>
              @endif
            </div>
          </div>
          <div class="modal-footer">
            {{Form::submit('Create', array('class'=>'btn btn-primary'))}}
          </div>
          {{Form::close()}}
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
  </div>
@stop

@section('css')
    <link rel="stylesheet" href="{{asset('plugins/select2/dist/css/select2.min.css')}}">


  <style type="text/css">
    .page-profile #header_wrapper.profile-header {
      background-image: -webkit-gradient(linear,left top,left bottom,color-stop(0,transparent),color-stop(30%,transparent),color-stop(100%,rgba(0,0,0,.45))),url({{asset('image/dance/'.$dance->id.'/cover.jpg')}})!important;
    }

    .dancer-avatars {
      margin: 0 auto;
    }

    .dancers {
      font-size: 14px;
      line-height: 1;
    }
    .dancers > .users-list-date {
      font-size: 10px;
    }
  </style>

@stop

@section('scripts')
<script src="{{asset('plugins/select2/dist/js/select2.min.js')}}"></script>

<script type="text/javascript">
  $(function(){

            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                var target = $(e.target).attr("href");
                if ((target == '#chatter')) {
                    $('.discussion-avatars').materialAvatar({
                        shape: 'circle'
                    });
                }
            });
  })
</script>

<script type="text/javascript">

  var bpm;
  var start; // cikos sāka atskaņošanu
  var duration;
  var steps = [];

  //SĀK SOĻU PIEVIENOŠANU VIKTORĪNAI
  var select_step = function (event, element)
  {
	  if(event.ctrlKey){
		  event.preventDefault();
		  $('.step-list').append('<li>'+element.html()+'</li>');
      $('#step-alert').hide();
		  steps.push(element.html());
	  }
  }
  //BEIDZ SOĻU PIEVIENOŠANU VIKTORĪNAI

	var call_move = function() {
		var rand_number = Math.floor(Math.random()* steps.length);
		$('#call').html(steps[rand_number]);
	}

  var ms_to_time = function(ms) {
    x = ms / 1000
    seconds = x % 60
    x /= 60
    minutes = x % 60

    return Math.floor(minutes)+':'+Math.floor(seconds);
  }

  var timer = function() {
    $('#progress').html(ms_to_time(Date.now() - start));
    if(Date.now() - start >= duration){
      clearInterval(timer);
    }
  }

  var i = 1;
  var count = function(ms_per_beat) {
    setInterval(function(){
      $('#count').html(i);
      if (i ==3 )
      {
        call_move();
      }
      if(i == 8)
        {i = 1}
        else
        {i++};

    }, ms_per_beat);
  };

  $(function () {
    $('#play').on('click', function(){
      var spotify_song_id = $('input[name=spotify_song_id]:checked').val();
      var spotify_player_id = $('select[name=player]').val();

      $.get(
        "{{route('spotify.play')}}",
        {
          device_id: spotify_player_id,
          spotify_song_id: spotify_song_id
        },
        function(data){
          bpm = data;
          $('#bpm').html(bpm);

          $.get("{{route('spotify.get-playback-info')}}", function(playback_data){
            start = playback_data.timestamp - playback_data.progress_ms;
            duration = playback_data.item.duration_ms;

            $('#start').html(ms_to_time(start));
            $('#duration').html(ms_to_time(duration));

            setInterval(timer, 1000);
            count(60000 / bpm);

            $('#kantor').modal('show');
          })
        });

    });

    $("select[name=spotify_song_id]").select2({
          placeholder: "Select song from Spotify",
          ajax: {
            dataType: 'json',
            url: "{{route('spotify.song-search')}}",
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



        $('#step-table').DataTable({
            processing: true,
          serverSide: true,
          bfilter: false,
          ajax: "{{route('steps.datatable')}}?dance_id={{$dance->id}}",
          columns: [
              { data: 'title', name: 'steps.title'},
              { data: 'alternate_names', name: 'alternate_names.name'},
              { data: 'difficulties', name: 'difficulties.title'},
              { data: 'repertuar'},
              { data: 'dancers', searchable:false},
          ],
          "fnDrawCallback": function(settings, json) {
            $('.step-repertuar').change(function(){
                var step_id = $(this).attr('data-id');
                var relation_type = $(this).val();

                $.get("{{route('steps.user-toggle')}}",
                {
                  step_id: step_id,
                  relation_type: relation_type,
                },
                function(message){
                  alertify.success(message);
                });
            });

			$('.step-title').on('click', function(){
				select_step(event, $(this));
			})
          }
        });
        $('#combination-table').DataTable({
            processing: true,
          serverSide: true,
          bfilter: false,
          ajax: "{{route('combinations.datatable')}}?dance_id={{$dance->id}}",
          columns: [
              { data: 'title', name: 'combinations.title'},
              { data: 'difficulties', name: 'difficulties.title'},
              { data: 'repertuar'},
              { data: 'dancers', searchable:false},
          ],
          "fnDrawCallback": function(settings, json) {
            $('.combination-repertuar').change(function(){
                var combination_id = $(this).attr('data-id');
                var relation_type = $(this).val();

                $.get("{{route('combinations.user-toggle')}}",
                {
                  combination_id: combination_id,
                  relation_type: relation_type,
                },
                function(message){
                      $.notify({
                        // options
                        message: message
                      },{
                        // settings
                        type: 'info'
                      });
                });
            });
          }
        });
        $('#choreography-table').DataTable({
            processing: true,
          serverSide: true,
          bfilter: false,
          ajax: "{{route('choreographies.datatable')}}?dance_id={{$dance->id}}",
          columns: [
              { data: 'title', name: 'choreographies.title'},
              { data: 'difficulties', name: 'difficulties.title'},
          ]
        });
    });
</script>
@stop

@section(Config::get('chatter.yields.head'))
    <link href="{{ url('/vendor/devdojo/chatter/assets/vendor/spectrum/spectrum.css') }}" rel="stylesheet">
  <link href="{{ url('/vendor/devdojo/chatter/assets/css/chatter.css') }}" rel="stylesheet">
  @if($chatter_editor == 'simplemde')
    <link href="{{ url('/vendor/devdojo/chatter/assets/css/simplemde.min.css') }}" rel="stylesheet">
  @elseif($chatter_editor == 'trumbowyg')
    <link href="{{ url('/vendor/devdojo/chatter/assets/vendor/trumbowyg/ui/trumbowyg.css') }}" rel="stylesheet">
    <style>
      .trumbowyg-box, .trumbowyg-editor {
        margin: 0px auto;
      }
    </style>
  @endif
@stop

@section(Config::get('chatter.yields.footer'))


@if( $chatter_editor == 'tinymce' || empty($chatter_editor) )
  <script src="{{ url('/vendor/devdojo/chatter/assets/vendor/tinymce/tinymce.min.js') }}"></script>
  <script src="{{ url('/vendor/devdojo/chatter/assets/js/tinymce.js') }}"></script>
  <script>
    var my_tinymce = tinyMCE;
    $('document').ready(function(){
      $('#tinymce_placeholder').click(function(){
        my_tinymce.activeEditor.focus();
      });
    });
  </script>
@elseif($chatter_editor == 'simplemde')
  <script src="{{ url('/vendor/devdojo/chatter/assets/js/simplemde.min.js') }}"></script>
  <script src="{{ url('/vendor/devdojo/chatter/assets/js/chatter_simplemde.js') }}"></script>
@elseif($chatter_editor == 'trumbowyg')
  <script src="{{ url('/vendor/devdojo/chatter/assets/vendor/trumbowyg/trumbowyg.min.js') }}"></script>
  <script src="{{ url('/vendor/devdojo/chatter/assets/vendor/trumbowyg/plugins/preformatted/trumbowyg.preformatted.min.js') }}"></script>
  <script src="{{ url('/vendor/devdojo/chatter/assets/js/trumbowyg.js') }}"></script>
@endif

<script src="{{ url('/vendor/devdojo/chatter/assets/vendor/spectrum/spectrum.js') }}"></script>
<script src="{{ url('/vendor/devdojo/chatter/assets/js/chatter.js') }}"></script>



<script type="text/javascript">
  $(function(){
    $('.discussion-avatars').materialAvatar({
        shape: 'circle'
    });
  })
</script>

<script>


  $('document').ready(function(){

    $('.chatter-close, #cancel_discussion').click(function(){
      $('#new_discussion').slideUp();
    });
    $('#new_discussion_btn').click(function(){
      @if(Auth::guest())
        window.location.href = "{{ route('login') }}";
      @else
        $('#new_discussion').slideDown();
        $('#title').focus();
      @endif
    });

    $("#color").spectrum({
        color: "#333639",
        preferredFormat: "hex",
        containerClassName: 'chatter-color-picker',
        cancelText: '',
        chooseText: 'close',
        move: function(color) {
        $("#color").val(color.toHexString());
      }
    });

    @if (count($errors) > 0)
      $('#new_discussion').slideDown();
      $('#title').focus();
    @endif


  });
</script>
@stop

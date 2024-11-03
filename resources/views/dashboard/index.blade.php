@extends('layouts.rightsidenav2')

@section(Config::get('chatter.yields.head'))
    <link href="{{ url('/vendor/devdojo/chatter/assets/vendor/spectrum/spectrum.css') }}" rel="stylesheet">
    <link href="{{ url('/vendor/devdojo/chatter/assets/css/chatter.css') }}" rel="stylesheet">
@stop

@section('content')
<div class="row">
    <div class="col-md-8">

        <div class="card card-add-note">
            <div id="note-color-wrapper">
                <div class="preview-gallery text-center">

                </div>
                <div class="card-heading">
                    <div class="card-title">
                        Say what's on your mind ;)
                    </div>
                </div>
                <div id="note_form">
                        {{Form::open(['url' => route('comments.store'), 'files' => true, 'id'=>'story-form'])}}
                    <div class="card-body">
                        {{Form::hidden('parent_class', 'Dashboard')}}
                        {{Form::hidden('parent_id', 1)}}
                        <div id="add_textarea_wrapper">
                            <div class="form-group label-floating is-empty p-0">
                                <label for="textArea" class="control-label" for="noteBody">Your story...</label>
                                <textarea class="form-control" rows="3" id="noteBody" name="comment"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-left">
                        <ul class="card-actions right-bottom">
                            <li>
                                {{Form::submit('Share', ['class' => 'btn btn-primary'])}}
                            </li>
                        </ul>
                    </div>
                {{Form::close()}}
                </div>
            </div>
        </div>
        <div class="content">
          <div class="content-body">
            <section id="meeting-timeline" class="timeline-container ">
              <div class="card card-date">
                <div class="form-group is-empty">
                  <div class="input-group">
                    <span class="input-group-addon"><i class="zmdi zmdi-calendar"></i></span>
                    <input type="text" class="form-control datepicker" id="timeline-date" type="date" value="">
                  </div>
                </div>
              </div>
              <div id="timeline-elements">

              </div>



              <div class="text-center">
                  <button class="btn btn-primary" onclick="get_timeline_elements()">Load more</button>
              </div>



            </section>
          </div>
        </div>
    </div>
    <div class="col-md-4">
        <div data-intro="These are top dances" data-step="1">
            <h1>Top dances</h1>
            @foreach($top_dances as $d)
                <div class="card card-timeline" style="background-image: -webkit-gradient(linear,left top,left bottom,color-stop(0,transparent),color-stop(0%,transparent),color-stop(0%,rgba(0,0,0,.45))),url({{asset('image/dance/'.$d->id.'/cover.jpg')}})!important;">
                    <a href="{{ route('dances.show', $d) }}">
                        <div class="card-body p-0" >
                            <div style="display: inline-block;" class="pull-left">
                                <img src="{{asset('image/dance/'.$d->id.'/logo.jpg')}}" class="lg circle m-10 shadow-3dp">
                            </div>
                            <div style="display: inline-block;">
                                <h1 class="text-white m-b-0">{{$d->title}}</h1>
                                <small class="title text-white">{{$d->family->name ?? '---'}}</small>

                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>


        <h1>Latest discussions</h1>
        <div id="chatter" class="chatter_home">
            <div class="col-md-12 p-0 right-column">
                <div class="panel">
                    <ul class="discussions">
                        @foreach($last_discussions as $discussion)
                            <li>
                                <a class="discussion_list" href="/{{ Config::get('chatter.routes.home') }}/{{ Config::get('chatter.routes.discussion') }}/{{ $discussion->category->slug }}/{{ $discussion->slug }}">

                                    <div class="chatter_middle m-r-0 m-l-0" style="width:100%">
                                        <h3 class="chatter_middle_title">
                                            {{ $discussion->title }}
                                            <div class="chatter_cat" style="background-color:{{ $discussion->category->color }}">
                                                <nobr>{{ $discussion->category->name }}</nobr>
                                            </div>
                                        </h3>
                                        <span class="chatter_middle_details">
                                            Last reply by
                                            <span data-href="/user">
                                                {{ ucfirst($discussion->lastPost->user?->{Config::get('chatter.user.database_field_with_user_name')}) }}
                                                {{-- {{ ucfirst($discussion->lastPost->user->{Config::get('chatter.user.database_field_with_user_name')}) }} --}}
                                            </span>
                                             {{ \Carbon\Carbon::createFromTimeStamp(strtotime($discussion->last_reply_at))->diffForHumans() }}</span>
                                        @if($discussion->lastPost->markdown)
                                            <?php $discussion_body = GrahamCampbell\Markdown\Facades\Markdown::convertToHtml( $discussion->lastPost->body ); ?>
                                        @else
                                            <?php $discussion_body = $discussion->lastPost->body; ?>
                                        @endif
                                    </div>

                                    <div class="chatter_clear"></div>
                                        <p class="m-t-5">{{ substr(strip_tags($discussion_body), 0, 200) }}@if(strlen(strip_tags($discussion_body)) > 200){{ '...' }}@endif</p>


                                    <div class="chatter_clear"></div>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
    <style type="text/css">
        .card.type--profile .card-body .checklist {
            text-align: left;
        }
        .card.type--profile .card-body .checklist span {
            display: inline-block;
        }
        .card.card-timeline .card-heading .card-title {
            line-height: 15.4px;
        }
        body {
            background-color: #eef5f9;
        }
    </style>
@stop

@section('rightsidenav')
    <div class="card type--profile">
        <header class="card-heading card-background" style="background-image: url({{(isset($session_owner->cover))?asset('image/covers/'.$session_owner->cover->id.'/md.jpg'):'assets/img/headers/header-md-02.jpg'}});background-repeat: no-repeat;background-size: cover; background-position: center">
            @if(Auth::check())
                @if($session_owner->avatar)
                    <img src="{{$session_owner->getAvatar('thumb')}}" alt="" class="img-circle ">
                @else
                    <div class="default-avatar right-side-avatar avatar" style="height:120px; width:120px;">
                        {{$session_owner->name}}
                    </div>
                @endif
            @else
                    <div class="default-avatar right-side-avatar avatar" style="height:120px; width:120px;">
                        Guest
                    </div>
            @endif
            {{--
            <ul class="card-actions icons  right-top">
                <li class="dropdown">
                    <a href="javascript:void(0)" data-toggle="dropdown">
                        <i class="zmdi zmdi-more-vert text-white"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-right btn-primary">
                        <li>
                            <a href="javascript:void(0)">Option One</a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">Option Two</a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">Option Three</a>
                        </li>
                    </ul>
                </li>
            </ul>
            --}}
        </header>
        <div class="card-body">
            @if(Auth::check())
                <h3 class="name">{{$session_owner->name}}</h3>
            @else
                <h3 class="name">Guest</h3>
            @endif
        </div>
        <div class="card card-task transparent m-t-30">
            @if(Auth::check())
            <header class="card-heading">
                <h5>Tracked steps</h5>
                <ul class="card-actions icons  right-top">
                    <li>
                        <a href="javascript:void(0)" class="animate_plus_x" data-toggle="input"><i class="zmdi zmdi-plus"></i> </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)" data-toggle="collapse"><i class="zmdi zmdi-chevron-down"></i> </a>
                    </li>
                </ul>
            </header>
            <div class="card-body">
                <form>
                    <div class="form-group">
                        <input type="text" value="" placeholder="Add task" class="form-control" />
                    </div>
                </form>

                <ul class="checklist">
                    @foreach ($sorted_steps as $dance => $steps)
                        {{$dance}}
                        @foreach($steps as $s)
                            <li><span class="checkbox">
                                <label>
                                    <input type="checkbox" value="">
                                    <i class="input-helper"></i> <a href="{{ route('steps.show', $s->id) }}">{{$s->step}}</a>
                                </label>
                            </span></li>
                        @endforeach()
                    @endforeach()
                </ul>
            </div>
        </div>
    @else
        <div class="card-body">

            {{Form::open(['url'=> route('login') , 'aria-label'=> __('Login') ])}}
            <div class="form-group is-empty m-t-15">
                <label class="sr-only" for="email">Username</label>
                <input type="text" name="email" class="form-control" id="email" placeholder="E-mail" autocomplete="off">
            </div>
            <div class="form-group is-empty m-t-15">
                <label class="sr-only" for="password">Password</label>
                <input type="password" name="password" class="form-control" id="password" placeholder="Password" autocomplete="off">
            </div>
            <div class="form-group m-t-15">
                <button type="submit" class="btn btn-sm btn-primary">Login</button>  ??  <a href="{{url('/register')}}" class="btn btn-sm btn-primary register">Register</a>
            </div>
            {{Form::close()}}
        </div>
    @endif
    </div>
@endsection


@section('scripts')

<script>

  $(function(){
    $('.discussion-avatars').materialAvatar({
        shape: 'circle'
    });
  })

    $(function(){
        get_timeline_elements();
    })

    var offset = 0;
    var get_timeline_elements = function() {
        console.log('lick');
        $.get("{{route('home.get-timeline-elements')}}",
        {
            offset : offset
        },
        function(data){
            $('#timeline-elements').append(data);
            $('.default-avatar-top').materialAvatar({
                shape: 'circle'
            });
            offset = offset + 5;
        });
    }

</script>
    <script>
        $(function(){
            $('.right-side-avatar').materialAvatar({
                shape: 'circle'
            });
        })
    </script>
@endsection

@section('css')
<style type="text/css">

    .dz-default.dz-message {
        display: none;
    }
</style>
@stop

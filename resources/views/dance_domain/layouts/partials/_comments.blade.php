    <div class="box-title">
        <h2><strong>Chat</strong> box</h2>
    </div>
		<div class="card">
            @if(isset($session_owner))
            <div class="card-heading p-t-0 p-b-0">
                {{Form::open(array('url' => route('comment.store')))}}
                {{ Form::hidden('parent_id', $parent_id) }}
							{{ Form::hidden('parent_class', $parent_class) }}
                    <div class="input-group">
                        {{Form::text('comment', null, array('class' => 'form-control', 'placeholder' => 'Type a message'))}}
                        <div class="input-group-btn">
                            <button class="btn btn-primary btn-fab btn-fab-sm m-b-0"><i class="zmdi zmdi-mail-send"></i><div class="ripple-container"></div></button>
                        </div>
                    </div>
                {{Form::close()}}
            </div>
            <hr style="margin-top:0px">
            @endif
            <!-- Most Viewed Courses Title -->
			<div class="card-body">
				<div class="row">
					<div class="box-body chat" id="chat-box">
                  @if(count($comments) > 0)
                    <ul class="list-group ">
                    @foreach($comments as $c)
                      <li class="list-group-item ">
                        <span class="pull-left">
                          <div class="circle md md-bg-pink-50 m-r-10 course-cover-frame">
                            @if($c->user->avatar)
                              <img src="{{$c->user->getAvatar('xs')}}" alt="" class="">
                            @else
                              <div class="default-avatar-top avatar dancer-avatars" style="height:40px; width:40px;">
                                  {{$c->user->name}}
                              </div>
                            @endif
                          </div>
                        </span>
                        <div class="list-group-item-body">
                          <div class="list-group-item-heading">{{$c->user->name}} <small class="text-muted"><i class="zmdi zmdi-clock"></i> {{date_format($c->created_at, "H:i d-m-Y")}}</small></div>
                          <div class="list-group-item-text">
                              <em class="disabled">{!! $c->comment !!}</em>
                          </div>
                        </div>
                      </li>
                @endforeach
                </ul>
              @else
                <p>No comments</p>
              @endif
            </div><!-- /.chat -->
				</div>
			</div>
		</div>

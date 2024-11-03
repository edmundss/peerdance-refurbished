<div class="meeting-timeline-block">
  <div class="meeting-timeline-icon animated bounceIn">
    <i class="zmdi zmdi-comment-outline"></i>
  </div>
  <div class="card card-timeline">
    @if(strlen($data['description']) > 0)
    <header class="card-heading border-bottom">
      <h5 class="card-title m-0">{!!$data['description']!!}</h5>
      <div>

      </div>
    </header>
    @endif
    <div class="card-body p-0">
      <ul class="list-group">
          <li class="list-group-item ">
              <span class="pull-left">
                  <div class="circle md md-bg-pink-50 m-r-10 course-cover-frame">
                      @if($data['avatar'])
                      {{--CHECK IF USER HAS AVATAR--}}
                          <img src="{{asset('/image/avatars/'.$data['avatar'].'/xs.jpg')}}" alt="" class="img-circle ">
                      @else
                          <div class="default-avatar-top avatar " style="height:40px; width:40px;">
                              {{$data['user']['name']}}
                          </div>
                      @endif
                  </div>
              </span>
              <div class="list-group-item-body">
                <div class="list-group-item-heading">{{$data['user']['name']}}</div>
                <div class="list-group-item-text" style="white-space: unset">
                    {{$data['subject']['comment']}}
                </div>
              </div>
          </li>
      </ul>
    </div>
    <span class="meeting-time">{{$data['lapse']}}</span>
  </div>
</div>
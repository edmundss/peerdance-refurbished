<div class="meeting-timeline-block">
  <div class="meeting-timeline-icon animated bounceIn">
    <i class="zmdi zmdi-swap-alt"></i>
  </div>
  <div class="card card-timeline">
    <header class="card-heading border-bottom">
      <h5 class="card-title m-0">{!!$data['description']!!}</h5>
      <div>

      </div>
    </header>
    <div class="card-body p-0">
      <ul class="list-group" style="background-image: -webkit-gradient(linear,left top,left bottom,color-stop(0,transparent),color-stop(30%,transparent),color-stop(100%,rgba(0,0,0,.45))),url({{asset('image/dance/'.$data['subject']['id'].'/cover.jpg')}})!important;">
          <img src="{{asset('image/dance/'.$data['subject']['id'].'/logo.jpg')}}" class="lg circle m-10 shadow-3dp">
      </ul>
    </div>
    <span class="meeting-time">{{$data['lapse']}}</span>
  </div>
</div>
<div class="meeting-timeline-block">
  <div class="meeting-timeline-icon animated bounceIn">
    <i class="fa fa-shoe-prints"></i>
  </div>
  <div class="card card-timeline">
    <header class="card-heading border-bottom">
      <h5 class="card-title m-0">{!!$data['description']!!}</h5>
      <div>

      </div>
    </header>
    <div class="card-body p-0">
      <ul class="list-group">
        <li class="list-group-item "><a href="{{ route('step.show', $data['subject']['id']) }}">{{$data['subject']['title']}}</a></li>
      </ul>
    </div>
    <span class="meeting-time">{{$data['lapse']}}</span>
  </div>
</div>
<div class="meeting-timeline-block">
  <div class="meeting-timeline-icon animated bounceIn">
    <i class="fa fa-music"></i>
  </div>
  <div class="card card-timeline">
    <header class="card-heading border-bottom">
      <h5 class="card-title m-0">{!!$data['description']!!}</h5>
      <div>

      </div>
    </header>
    <div class="card-body p-0">
      <ul class="list-group">
          <li class="list-group-item p-0">
              <iframe src="https://open.spotify.com/embed/track/{{$data['subject']['spotify_id']}}" width="100%" height="80" frameborder="0" allowtransparency="true" allow="encrypted-media"></iframe>
          </li>
      </ul>
    </div>
    <span class="meeting-time">{{$data['lapse']}}</span>
  </div>
</div>
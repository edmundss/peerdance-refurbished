<div class="meeting-timeline-block">
  <div class="meeting-timeline-icon animated bounceIn">
    <i class="zmdi zmdi-comments"></i>
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
          @if($data['dance_id'])
            <img src="{{ asset('image/dance/'.$data['dance_id'].'/logo.jpg') }}" class="col-xs-3 p-0 ">
          @endif
          <div class="p-10 col-xs-9" style="display: inline;">
                          <?php
                            $discussion = \DevDojo\Chatter\Models\Discussion::find($data['subject']['id']);
                            $discussion_body = $discussion->post_newest[0]->body;
                          ?>

            <h2 class="m-t-0 m-l-5 m-b-0">
              <a href="{{ route('chatter.discussion.showInCategory', [$discussion->category->slug, $discussion->slug]) }}">{{$discussion->title}}</a>
            </h2>
            <p class="m-l-5 m-t-0 m-b-0">{{ substr(strip_tags($discussion_body), 0, 80) }}@if(strlen(strip_tags($discussion_body)) > 80){{ '...' }}@endif</p>
          </div>
        </li>
      </ul>
    </div>
    <span class="meeting-time">{{$data['lapse']}}</span>
  </div>
</div>
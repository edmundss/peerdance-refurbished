@extends('layouts.rightsidenav2')

@section('page_title_buttons')
	<a href="{{route('dances.create')}}" class="btn btn-primary btn-sm">Create New Dance</a>
@stop

@section('content')
	<div class="row">
		<div class="col-lg-12">
      <h1>{{(Auth::check())?'Your dances':'Most popular dances'}}</h1>
		</div>
	</div>
	<br>
	<div class="row">
    @forelse($content_dances as $d)
    <div class="col-md-4 col-lg-3">
      <a href="{{route('dances.show', $d->id)}}">
        <div class="card type--profile">
          <header class="card-heading card-background" style="    background-image: url({{asset('image/dance/'.$d->id.'/cover.jpg')}});" id="card_img_02">
            <img src="{{asset('image/dance/'.$d->id.'/logo.jpg')}}" alt="" class="img-circle">
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
          </header>
          <div class="card-body">
            <h3 class="name">{{$d->title}}</h3>
            <span class="title">{{$d->family->name ?? '---'}}</span>
          </div>
          <footer class="card-footer border-top">
            <div class="row row p-t-10 p-b-10">
              <div class="col-xs-4"><span class="count">{{$d->steps()->count()+$d->combinations()->count() + $d->choreographies->count()}}</span><span>Move elements</span></div>
              <div class="col-xs-4"><span class="count">{{$d->songs()->count()}}</span><span>Songs</span></div>
              <div class="col-xs-4"><span class="count">{{$d->users()->count()}}</span><span>Dancers</span></div>
            </div>
          </footer>
        </div>
      </a>
        </div>
        @empty
        <div class="col-md-4 col-lg-3">
        Let us know which dances do you dance!
        </div>
        @endforelse
	</div>
@stop

@section('scripts')
<script type="text/javascript">
  $(function(){
    var $openSearch = $('[data-dancesearch-open]'),
          $closeSearch = $('[data-navsearch-close]'),
          $navbarForm = $('#dancesearch_form'),
          $navbarSearch = $('#dance_search'),
          $document = $(document);
      $openSearch.on('click', function (e) {
          console.log('searching')
          e.stopPropagation();
          $navbarForm.addClass('open');
          $navbarSearch.focus();
      });
      $closeSearch.on('click', function (e) {
          e.stopPropagation();
          $navbarForm.removeClass('open');
          $navbarSearch.val('');
          $('.dance-card').show();
      });
      $document.on('click', function (e) {
          e.stopPropagation();
          if (e.target !== $('#dance_search')) {
              $navbarForm.removeClass('open');
              $navbarSearch.val('');
              $('.dance-card').show();
          }
      });
      $navbarSearch.on('click', function (e) {
          e.stopPropagation();
      });

     resize_dance_list();

     $('#dance_search').keyup(function(){
      $search = $(this).val().toLowerCase();
      $('.dance-card').each(function(){
        $term = $(this).find('.dance-title').html().toLowerCase();

        if($term.indexOf($search) > -1){
          $(this).show();
        }else{
          $(this).hide();
        }

      })
    });
  })

    var resize_dance_list = function ()
    {
      console.log($('.dance-list').height());

      var document_height = $(document).height()
      $('.dance-list').height(document_height - 210);
      console.log($('.dance-list').height());
    }

</script>
@stop

@section('rightsidenav')
<nav role="navigation" class="">
   <div class="nav-wrapper">
      <ul class="nav navbar-nav pull-left" style="height: 70px">
         <li class="p-l-10">
            <h1>All dances</h1>
         </li>
      </ul>
      <ul class="nav navbar-nav pull-right">
         <li>
            <a href="javascript:void(0)" data-dancesearch-open="">
               <i class="zmdi zmdi-search" style="    font-size: 24px;line-height: 1.5em;"></i>
               <div class="ripple-container"></div>
            </a>
         </li>
      </ul>
   </div>
   <form role="search" action="" class="navbar-form" id="dancesearch_form">
      <div class="form-group is-empty">
         <input type="text" placeholder="Name of the dance..." class="form-control" id="dance_search" autocomplete="off">
         <i data-navsearch-close="" class="zmdi zmdi-close close-search"></i>
      </div>
      <button type="submit" class="hidden btn btn-default">Submit</button>
   </form>
</nav>
<div class="row">
   <div class="col-lg-12">
      <div class="card dance-list">
         <div class="card-body p-0">
            <ul class="list-group ">
               @foreach($dances as $d)
               <li class="list-group-item p-10 dance-card">
                  <a href="{{route('dances.show', $d->id)}}">
                     <span class="pull-left">
                        <div class="circle md md-bg-pink-50 m-r-10 course-cover-frame">
                           <img src="{{asset('image/dance/'.$d->id.'/logo.jpg')}} " alt="" class="">
                        </div>
                     </span>
                     <div class="list-group-item-body">
                        <div class="list-group-item-heading dance-title">
                           {{$d->title}}
                        </div>
                        <div class="list-group-item-text">
                           <em class="disabled">{{$d->family->name ?? '---'}}</em>
                        </div>
                     </div>
                  </a>
               </li>
               @endforeach
            </ul>
         </div>
      </div>
   </div>
</div>
@stop

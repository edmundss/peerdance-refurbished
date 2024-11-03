<!DOCTYPE html>
<html lang="en">

<head>
	@include('layouts.partials._head')
</head>

<body>
  <div id="fb-root"></div>
  <script>(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = 'https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.1&appId=272632470229919&autoLogAppEvents=1';
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));</script>
  <div id="app_wrapper" class="{{$appclass ?? ""}}">
      @include('layouts.partials._header')

    <aside id="app_sidebar-left">
		@include('layouts.partials._logo_wraper')

        <nav id="app_main-menu-wrapper" class="fadeInLeft scrollbar">
            <div class="sidebar-inner sidebar-push">
                @include('layouts.partials._navigation')
            </div>
        </nav>
      </aside>
      <section id="content_outer_wrapper">
        <div id="content_wrapper" class="simple">
          <div id="header_wrapper" class="header-sm">
            <div class="container-fluid">
              <div class="row">
                <div class="col-xs-12">
                  <header id="header">
                    <h1>{{$page_title ?? null}}</h1>
                    @isset($breadcrumb)
                        <ol class="breadcrumb">
                            @foreach ($breadcrumb as $i => $b)
                                <li @if($i+1 == count($breadcrumb))	class="active"	@endif >
                                    @if($i+1 < count($breadcrumb))<a href="{{$b['url']}}">@endif
                                        {{$b['title']}}
                                    @if($i+1 < count($breadcrumb))</a>@endif
                                </li>
                            @endforeach
                        </ol>
                    @endisset
                  </header>
                </div>
              </div>
            </div>

            <ul class="card-actions lg alt-actions right-top custom-header-buttons">
              @yield('page_title_buttons')
              hellosapdo kas;dlk sadk[sa pkd[apk d[pk]]]
            </ul>
          </div>
          <div id="content" class="container-fluid m-t-0 @isset ($content_classes){{$content_classes}}@endisset">
              @yield('content')
          </div>
        </div>
        @include('layouts.partials._footer')

        </section>

      </div>
      <script src="{{asset('assets/js/vendor.bundle.js')}}"></script>
      <script src="{{asset('assets/js/app.bundle.js')}}"></script>
      @include('layouts.partials._end_stuff')
    </body>
    </html>

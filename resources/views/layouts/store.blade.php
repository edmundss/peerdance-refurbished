<!DOCTYPE html>
<html lang="en">

<head>
	@include('layouts.partials._head')
	<style media="screen">
	#content_outer_wrapper #content_wrapper {
		padding-top: 0px;
	}
	.app_sidebar-menu-collapsed #app_wrapper #content_outer_wrapper {
		padding-left: 0px;
	}
	#content_outer_wrapper {
		padding-bottom: 0px;
		padding-left: 0px;
	}
	</style>
</head>

<body>
  <div id="app_wrapper" class="{{$appclass ?? ""}}">

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

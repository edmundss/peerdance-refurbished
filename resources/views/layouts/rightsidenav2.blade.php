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

			<nav id="app_main-menu-wrapper" class="scrollbar">
				<div class="sidebar-inner sidebar-push">@include('layouts.partials._navigation')</div>
			</nav>
		</aside>
		<section id="content_outer_wrapper" class="">
			<div id="content_wrapper" class="rightnav_v2 {{$toggle_right ?? ""}}">
				<div id="header_wrapper" class="header-sm">
					<div class="container-fluid">
						<div class="row">
							<div class="col-xs-12">
								<header id="header">
									<h1>{!!$page_title ?? null!!}</h1>
								</header>
							</div>
						</div>
					</div>
					<ul class="card-actions icons lg alt-actions right-top">
						<li>
							<a href="javascript:void(0)" class="drawer-trigger" data-drawer="toggle-right">
								<i class="zmdi zmdi-menu"></i>
							</a>
						</li>
					</ul>
				</div>
				@yield('content_tabs')
				<div id="content" class="container-fluid">
					<div class="content-body">
						@yield('content')
								<!-- ENDS $dashboard_content -->
							</div>
						</div>
						<!-- ENDS $content -->
						<aside id="rightnav">
							@yield('rightsidenav')
						</aside>
			</div>

			@include('layouts.partials._footer')
		</section>
	</div>
	<script src="assets/js/vendor.bundle.js"></script>
	<script src="assets/js/app.bundle.js"></script>

	@yield('scripts')
	@yield('modals')
	<script>
		$(function(){

				$('.default-avatar-top').materialAvatar({
				  shape: 'circle'
				});
		})
	</script>
</body>

</html>

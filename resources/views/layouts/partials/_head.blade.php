	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-123843299-1"></script>
	<script>
	  window.dataLayer = window.dataLayer || [];
	  function gtag(){dataLayer.push(arguments);}
	  gtag('js', new Date());

	  gtag('config', 'UA-123843299-1');
	</script>

{{ Html::macro('activeState', function ($uri) {
    return strpos(Request::url(), $uri) !== false ? 'active' : 'passive';
}) }}

	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<meta name="description" content="">
	<meta name="keywords" content="{{$keywords ?? ''}}">
	<title>{{config('app.name')}} @isset($page_title) - {{$page_title}} @endisset</title>
	@isset ($og_image)
	    <meta property="og:image" content="{{$og_image}}" />
	@endisset
	<link rel="stylesheet" href="{{asset('assets/css/vendor.bundle.css')}}">
	<link rel="stylesheet" href="{{asset('assets/css/app.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/theme-b.css')}}">
    @yield('forum_css')
	<link rel="stylesheet" href="{{asset('plugins/intro.js/introjs.css')}}">
	<link rel="stylesheet" href="{{asset('assets/css/override.css')}}">
	<link rel="stylesheet" href="{{asset('assets/css/tenant_override.css')}}">
	<link href="//fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
	<style type="text/css">
		.fa-spotify {
			width:23px;
			padding-right: 7px;
		}


		.timeline-page #meeting-timeline .meeting-timeline-icon i.fa {
			left: 42%;
		}
		.fb-like iframe {
			left: 0;
		}
		#app_main-menu-wrapper .nav-pills > li > a i.fa {
			font-size: 1.2em;
		}
	</style>
	<meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('css')

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
        <div id="content_wrapper">
          <div id="header_wrapper" class="header-sm ">
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
          </div>
          <div class="tabpanel tab-header">
            <ul class="nav nav-tabs scrollable p-l-10">
                @foreach ($tabs as $t)
                    <li class="@if($loop->first)active @endif" role="presentation"><a href="{{$t['url']}}" data-toggle="tab">{{$t['title']}}</a></li>

                @endforeach
            </ul>
          </div>
          <div id="content" class="container-fluid">
            @yield('content')
          </div>
          <section id="chat_compose_wrapper">
            <div class="tippy-top">
              <div class="recipient">Allison Grayce</div>
              <ul class="card-actions icons  right-top">
                <li>
                  <a href="javascript:void(0)">
                    <i class="zmdi zmdi-videocam"></i>
                  </a>
                </li>
                <li class="dropdown">
                  <a href="javascript:void(0)" data-toggle="dropdown" aria-expanded="false">
                    <i class="zmdi zmdi-more-vert"></i>
                  </a>
                  <ul class="dropdown-menu btn-primary dropdown-menu-right">
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
                <li>
                  <a href="javascript:void(0)" data-chat="close">
                    <i class="zmdi zmdi-close"></i>
                  </a>
                </li>
              </ul>
            </div>
            <div class='chat-wrapper scrollbar'>
              <div class='chat-message scrollbar'>
                <div class='chat-message chat-message-recipient'>
                  <img class='chat-image chat-image-default' src='assets/img/profiles/05.jpg' />
                  <div class='chat-message-wrapper'>
                    <div class='chat-message-content'>
                      <p>Hey Mike, we have funding for our new project!</p>
                    </div>
                    <div class='chat-details'>
                      <span class='today small'></span>
                    </div>
                  </div>
                </div>
                <div class='chat-message chat-message-sender'>
                  <img class='chat-image chat-image-default' src='assets/img/profiles/02.jpg' />
                  <div class='chat-message-wrapper '>
                    <div class='chat-message-content'>
                      <p>Awesome! Photo booth banh mi pitchfork kickstarter whatever, prism godard ethical 90's cray selvage.</p>
                    </div>
                    <div class='chat-details'>
                      <span class='today small'></span>
                    </div>
                  </div>
                </div>
                <div class='chat-message chat-message-recipient'>
                  <img class='chat-image chat-image-default' src='assets/img/profiles/05.jpg' />
                  <div class='chat-message-wrapper'>
                    <div class='chat-message-content'>
                      <p> Artisan glossier vaporware meditation paleo humblebrag forage small batch.</p>
                    </div>
                    <div class='chat-details'>
                      <span class='today small'></span>
                    </div>
                  </div>
                </div>
                <div class='chat-message chat-message-sender'>
                  <img class='chat-image chat-image-default' src='assets/img/profiles/02.jpg' />
                  <div class='chat-message-wrapper'>
                    <div class='chat-message-content'>
                      <p>Bushwick letterpress vegan craft beer dreamcatcher kickstarter.</p>
                    </div>
                    <div class='chat-details'>
                      <span class='today small'></span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <footer id="compose-footer">
              <form class="form-horizontal compose-form">
                <ul class="card-actions icons left-bottom">
                  <li>
                    <a href="javascript:void(0)">
                      <i class="zmdi zmdi-attachment-alt"></i>
                    </a>
                  </li>
                  <li>
                    <a href="javascript:void(0)">
                      <i class="zmdi zmdi-mood"></i>
                    </a>
                  </li>
                </ul>
                <div class="form-group m-10 p-l-75 is-empty">
                  <div class="input-group">
                    <label class="sr-only">Leave a comment...</label>
                    <input type="text" class="form-control form-rounded input-lightGray" placeholder="Leave a comment..">
                    <span class="input-group-btn">
                      <button type="button" class="btn btn-blue btn-fab  btn-fab-sm">
                        <i class="zmdi zmdi-mail-send"></i>
                      </button>
                    </span>
                  </div>
                </div>
              </form>
            </footer>
          </section>
        </div>
        @include('layouts.partials._footer')

        </section>
        <aside id="app_sidebar-right">
          <div class="sidebar-inner sidebar-overlay">
            <div class="tabpanel">
              <ul class="nav nav-tabs nav-justified">
                <li class="active" role="presentation"><a href="#sidebar_chat" data-toggle="tab" aria-expanded="true">Chat</a></li>
                <li role="presentation"><a href="#sidebar_activity" data-toggle="tab">Activity</a></li>
                <li role="presentation"><a href="#sidebar_settings" data-toggle="tab">Settings</a></li>
              </ul>
              <div class="tab-content">
                <div class="tab-pane fade active in" id="sidebar_chat">
                  <form class="m-l-15 m-r-15 m-t-30">
                    <div class="input-group search-target">
                      <span class="input-group-addon"><i class="zmdi zmdi-search"></i></span>
                      <div class="form-group is-empty">
                        <input type="text" value="" placeholder="Filter contacts..." class="form-control" data-search-trigger="open">
                      </div>
                    </div>
                  </form>
                  <ul class="description">
                    <li class="title">
                      Online
                    </li>
                  </ul>
                  <ul class="list-group p-0">
                    <li class="list-group-item" data-chat="open" data-chat-name="John Smith">
                      <span class="pull-left"><img src="assets/img/profiles/07.jpg" alt="" class="img-circle max-w-40 m-r-10 "></span>
                      <i class="badge mini success status"></i>
                      <div class="list-group-item-body">
                        <div class="list-group-item-heading">John Smith</div>
                        <div class="list-group-item-text">New York, NY</div>
                      </div>
                    </li>
                    <li class="list-group-item" data-chat="open" data-chat-name="Allison Grayce">
                      <span class="pull-left"><img src="assets/img/profiles/05.jpg" alt="" class="img-circle max-w-40 m-r-10 "></span>
                      <i class="badge mini success status"></i>
                      <div class="list-group-item-body">
                        <div class="list-group-item-heading">Allison Grayce</div>
                        <div class="list-group-item-text">Seattle, WA</div>
                      </div>
                    </li>
                    <li class="list-group-item" data-chat="open" data-chat-name="Ashley Ford">
                      <span class="pull-left"><img src="assets/img/profiles/18.jpg" alt="" class="img-circle max-w-40 m-r-10 "></span>
                      <i class="badge mini success status"></i>
                      <div class="list-group-item-body">
                        <div class="list-group-item-heading">Ashley Ford</div>
                        <div class="list-group-item-text">Denver, CO</div>
                      </div>
                    </li>
                    <li class="list-group-item" data-chat="open" data-chat-name="Johanna Kollmann">
                      <span class="pull-left"><img src="assets/img/profiles/11.jpg" alt="" class="img-circle max-w-40 m-r-10 "></span>
                      <i class="badge mini success status"></i>
                      <div class="list-group-item-body">
                        <div class="list-group-item-heading">Johanna Kollmann </div>
                        <div class="list-group-item-text">Palo Alto, Ca</div>
                      </div>
                    </li>
                  </ul>
                  <ul class="description">
                    <li class="title">
                      Busy
                    </li>
                  </ul>
                  <ul class="list-group p-0">
                    <li class="list-group-item" data-chat="open" data-chat-name="Mike Jones">
                      <span class="pull-left"><img src="assets/img/profiles/03.jpg" alt="" class="img-circle max-w-40 m-r-10 "></span>
                      <i class="badge mini warning status"></i>
                      <div class="list-group-item-body">
                        <div class="list-group-item-heading">Mike Jones </div>
                        <div class="list-group-item-text">San Francisco, CA</div>
                      </div>
                    </li>
                    <li class="list-group-item" data-chat="open" data-chat-name="Nikki Clark">
                      <span class="pull-left"><img src="assets/img/profiles/06.jpg" alt="" class="img-circle max-w-40 m-r-10 "></span>
                      <i class="badge mini warning status"></i>
                      <div class="list-group-item-body">
                        <div class="list-group-item-heading">Nikki Clark </div>
                        <div class="list-group-item-text">Sarasota, FL</div>
                      </div>
                    </li>
                    <li class="list-group-item" data-chat="open" data-chat-name="Jason Kendall">
                      <span class="pull-left"><img src="assets/img/profiles/15.jpg" alt="" class="img-circle max-w-40 m-r-10 "></span>
                      <i class="badge mini warning status"></i>
                      <div class="list-group-item-body">
                        <div class="list-group-item-heading">Jason Kendall </div>
                        <div class="list-group-item-text">New York, NY</div>
                      </div>
                    </li>
                  </ul>
                  <ul class="description">
                    <li class="title">
                      Offline
                    </li>
                  </ul>
                  <ul class="list-group p-0">
                    <li class="list-group-item" data-chat="open" data-chat-name="Josh Hemsley">
                      <span class="pull-left"><img src="assets/img/profiles/16.jpg" alt="" class="img-circle max-w-40 m-r-10 "></span>
                      <i class="badge mini danger status"></i>
                      <div class="list-group-item-body">
                        <div class="list-group-item-heading">Josh Hemsley</div>
                        <div class="list-group-item-text">Salem, MA</div>
                      </div>
                    </li>
                    <li class="list-group-item" data-chat="open" data-chat-name="James Hart">
                      <span class="pull-left"><img src="assets/img/profiles/09.jpg" alt="" class="img-circle max-w-40 m-r-10 "></span>
                      <i class="badge mini danger status"></i>
                      <div class="list-group-item-body">
                        <div class="list-group-item-heading">James Hart</div>
                        <div class="list-group-item-text">Salem, MA</div>
                      </div>
                    </li>
                  </ul>
                  <button class="btn btn-primary btn-fab btn-fab-sm animate-fab" data-chat="open" id="chat_fab_new">
                    <i class="zmdi zmdi-plus"></i>
                  </button>
                </div>
                <div class="tab-pane fade" id="sidebar_activity">
                  <div class="sidebar-timeline">
                    <div class="time-item">
                      <div class="item-info">
                        <small class="text-muted">15 minutes ago</small>
                        <p><a href="#" class="accent">Mike Jones</a> fixed z-index conflict sidebar.scss</p>
                      </div>
                    </div>
                    <div class="time-item">
                      <div class="item-info">
                        <small class="text-muted">30 minutes ago</small>
                        <p><a href="javascript:void(0)" class="accent">Hazel	Dean</a> left a comment on product page designs.</p>
                        <p><em>"Yuccie shoreditch trust fund, artisan tumblr sustainable cronut unicorn blog seitan. "</em></p>
                      </div>
                    </div>
                    <div class="time-item">
                      <div class="item-info">
                        <small class="text-muted">45 minutes ago</small>
                        <p><a href="javascript:void(0)" class="accent">Molly</a> requested time off for training.</p>
                        <p><em>"Snackwave church-key cardigan you probably haven't heard of them, asymmetrical microdosing cronut "</em></p>
                      </div>
                    </div>
                    <div class="time-item">
                      <div class="item-info">
                        <small class="text-muted">3 hours ago</small>
                        <p><a href="javascript:void(0)" class="accent">Frederick	Roy</a> commented your post.</p>
                        <p><em>"Skateboard dreamcatcher la croix, edison bulb sustainable sriracha vexillologist kombucha master cleanse."</em></p>
                      </div>
                    </div>
                    <div class="time-item">
                      <div class="item-info">
                        <small class="text-muted">1 hour ago</small>
                        <p><a href="javascript:void(0)" class="accent">Holly Cobb</a> Uploaded 6 new photos.</p>
                      </div>
                    </div>
                    <div class="time-item">
                      <div class="item-info">
                        <small class="text-muted">5 hours ago</small>
                        <p><a href="javascript:void(0)" class="accent">Neal Stephens</a> setup a meeting with<a href="#" class="text-success"> Jason Kendall</a>.</p>
                        <p><em>"Authentic aesthetic tattooed, PBR&B squid tote bag schlitz vaporware glossier yr man braid direct trade disrupt poke.  "</em></p>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="tab-pane fade" id="sidebar_settings">
                  <div class="color-container">
                    <h3 class="title">Preset Color Options</h3>
                    <div class="row">
                      <div class="col-xs-3 p-0">
                        <div class="color-option-check">
                          <label data-load-css="assets/css/theme-a.css">
                            <input type="radio" name="setting-theme" checked="checked">
                            <span class="icon-check"></span>
                            <span class="split">
                              <span class="color bg-primary-theme-a"></span>
                              <span class="color bg-shade-theme-a"></span>
                            </span>
                            <span class="color bg-menu-darkBlue"></span>
                          </label>
                        </div>
                      </div>
                      <div class="col-xs-3 p-0">
                        <div class="color-option-check">
                          <label data-load-css="assets/css/theme-b.css">
                            <input type="radio" name="setting-theme">
                            <span class="icon-check"></span>
                            <span class="split">
                              <span class="color bg-primary-theme-b"></span>
                              <span class="color bg-shade-theme-b"></span>
                            </span>
                            <span class="color  bg-menu-darkBlue"></span>
                          </label>
                        </div>
                      </div>
                      <div class="col-xs-3 p-0">
                        <div class="color-option-check">
                          <label data-load-css="assets/css/theme-c.css">
                            <input type="radio" name="setting-theme">
                            <span class="icon-check"></span>
                            <span class="split">
                              <span class="color bg-primary-theme-c"></span>
                              <span class="color bg-shade-theme-c"></span>
                            </span>
                            <span class="color  bg-menu-darkBlue"></span>
                          </label>
                        </div>
                      </div>
                      <div class="col-xs-3 p-0">
                        <div class="color-option-check">
                          <label data-load-css="assets/css/theme-d.css">
                            <input type="radio" name="setting-theme">
                            <span class="icon-check"></span>
                            <span class="split">
                              <span class="color bg-primary-theme-d"></span>
                              <span class="color bg-shade-theme-d"></span>
                            </span>
                            <span class="color  bg-menu-darkBlue"></span>
                          </label>
                        </div>
                      </div>
                    </div>
                    <div class="row m-t-20">
                      <div class="col-xs-3 p-0">
                        <div class="color-option-check">
                          <label data-load-css="assets/css/theme-e.css">
                            <input type="radio" name="setting-theme" checked="checked">
                            <span class="icon-check"></span>
                            <span class="split">
                              <span class="color bg-primary-theme-e"></span>
                              <span class="color bg-shade-theme-e"></span>
                            </span>
                            <span class="color bg-menu-white"></span>
                          </label>
                        </div>
                      </div>
                      <div class="col-xs-3 p-0">
                        <div class="color-option-check">
                          <label data-load-css="assets/css/theme-f.css">
                            <input type="radio" name="setting-theme">
                            <span class="icon-check"></span>
                            <span class="split">
                              <span class="color bg-primary-theme-f"></span>
                              <span class="color bg-shade-theme-f"></span>
                            </span>
                            <span class="color  bg-menu-white"></span>
                          </label>
                        </div>
                      </div>
                      <div class="col-xs-3 p-0">
                        <div class="color-option-check">
                          <label data-load-css="assets/css/theme-g.css">
                            <input type="radio" name="setting-theme">
                            <span class="icon-check"></span>
                            <span class="split">
                              <span class="color bg-primary-theme-g"></span>
                              <span class="color bg-shade-theme-g"></span>
                            </span>
                            <span class="color  bg-menu-white"></span>
                          </label>
                        </div>
                      </div>
                      <div class="col-xs-3 p-0">
                        <div class="color-option-check">
                          <label data-load-css="assets/css/theme-h.css">
                            <input type="radio" name="setting-theme">
                            <span class="icon-check"></span>
                            <span class="split">
                              <span class="color bg-primary-theme-h"></span>
                              <span class="color bg-shade-theme-h"></span>
                            </span>
                            <span class="color  bg-menu-white"></span>
                          </label>
                        </div>
                      </div>
                    </div>
                    <h3 class="title m-t-30">Layout Mode</h3>
                    <ul class="description">

                      <li>
                        <div class="radio block"><label><input type="radio" name="layoutMode" value="" checked>FULL WIDTH</label></div>


                      </li>
                      <li>
                        <div class="radio block"><label><input type="radio" name="layoutMode" value="boxed-layout">BOXED</label></div>
                      </li>

                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </aside>
      </div>
      <script src="assets/js/vendor.bundle.js"></script>
      <script src="assets/js/app.bundle.js"></script>
      @include('layouts.partials._end_stuff')

    </body>

    </html>

<header id="app_topnavbar-wrapper">
    <nav role="navigation" class="navbar topnavbar">
        <div class="nav-wrapper">
            <ul class="nav navbar-nav pull-left left-menu">
                <li class="app_menu-open">
                    <a href="javascript:void(0)" data-toggle-state="app_sidebar-left-open" data-key="leftSideBar">
                        <i class="zmdi zmdi-menu"></i>
                    </a>
                </li>
            </ul>
            <ul class="nav navbar-nav pull-right">
                @if(Auth::check())
                <li class="dropdown avatar-menu">
                    <a href="javascript:void(0)" data-toggle="dropdown" aria-expanded="false">
                        <span class="meta">
                            <span class="avatar avatar-inline">
                                    @if($session_owner->avatar)
                                        <img src="{{$session_owner->getAvatar('xs')}}" alt="" class="img-circle max-w-35">
                                        <i class="badge mini success status"></i>
                                    @else
                                        <div class="default-avatar-top avatar max-w-35" style="height:35px; width:35px;">
                                            {{$session_owner->name}}
                                        </div>
                                        <i class="badge mini success status"></i>
                                    @endif
                            </span>
                            <span class="name">{{$session_owner->name}}</span>
                            <span class="caret"></span>
                        </span>
                    </a>
                    <ul class="dropdown-menu btn-primary dropdown-menu-right">
                        <li>
                            <a href="{{route('user.show', $session_owner)}}"><i class="zmdi zmdi-account"></i> Profile</a>
                        </li>
                        {{--
                        <li>
                            <a href="app-mail.html"><i class="zmdi zmdi-email"></i> Messages</a>
                        </li>
                        <li>
                            <a href="javascript:void(0)"><i class="zmdi zmdi-settings"></i> Account Settings</a>
                        </li>
                        --}}
                        <li>
                            <a href="{{url('/logout')}}"><i class="zmdi zmdi-sign-in"></i> Sign Out</a>
                        </li>
                    </ul>
                </li>
                {{--
                <li class="select-menu hidden-xs hidden-sm">
                    <select class="select form-control country" style="display:none">
                        <option option="EN">English</option>
                        <option option="ES">Español</option>
                        <option option="FN"> Français</option>
                        <option option="IT">Italiano</option>
                    </select>
                </li>
                <li>
                    <a href="javascript:void(0)" data-navsearch-open>
                        <i class="zmdi zmdi-search"></i>
                    </a>
                </li>
                <li class="dropdown hidden-xs hidden-sm">
                    <a href="javascript:void(0)" data-toggle="dropdown" aria-expanded="false">
                        <span class="badge mini status danger"></span>
                        <i class="zmdi zmdi-notifications"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-lg-menu dropdown-menu-right dropdown-alt">
                        <li class="dropdown-menu-header">
                            <ul class="card-actions icons  left-top">
                                <li class="withoutripple">
                                    <a href="javascript:void(0)" class="withoutripple">
                                        <i class="zmdi zmdi-settings"></i>
                                    </a>
                                </li>
                            </ul>
                            <h5>NOTIFICATIONS</h5>
                            <ul class="card-actions icons right-top">
                                <li>
                                    <a href="javascript:void(0)">
                                        <i class="zmdi zmdi-check-all"></i>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <div class="card">
                                <a href="javascript:void(0)" class="pull-right dismiss" data-dismiss="close">
                                    <i class="zmdi zmdi-close"></i>
                                </a>
                                <div class="card-body">
                                    <ul class="list-group ">
                                        <li class="list-group-item ">
                                            <span class="pull-left"><img src="{{asset('assets/img/profiles/11.jpg')}}" alt="" class="img-circle max-w-40 m-r-10 "></span>
                                            <div class="list-group-item-body">
                                                <div class="list-group-item-heading">Dakota Johnson</div>
                                                <div class="list-group-item-text">Do you want to grab some sushi for lunch?</div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="card">
                                <a href="javascript:void(0)" class="pull-right dismiss" data-dismiss="close">
                                    <i class="zmdi zmdi-close"></i>
                                </a>
                                <div class="card-body">
                                    <ul class="list-group ">
                                        <li class="list-group-item ">
                                            <span class="pull-left"><img src="{{asset('assets/img/profiles/07.jpg')}}" alt="" class="img-circle max-w-40 m-r-10 "></span>
                                            <div class="list-group-item-body">
                                                <div class="list-group-item-heading">Todd Cook</div>
                                                <div class="list-group-item-text">Let's schedule a meeting with our design team at 10am.</div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="card">
                                <a href="javascript:void(0)" class="pull-right dismiss" data-dismiss="close">
                                    <i class="zmdi zmdi-close"></i>
                                </a>
                                <div class="card-body">
                                    <ul class="list-group ">
                                        <li class="list-group-item ">
                                            <span class="pull-left"><img src="{{asset('assets/img/profiles/05.jpg')}}" alt="" class="img-circle max-w-40 m-r-10 "></span>
                                            <div class="list-group-item-body">
                                                <div class="list-group-item-heading">Jennifer Ross</div>
                                                <div class="list-group-item-text">We're looking to hire two more protypers to our team.</div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </li>
                        <li class="dropdown-menu-footer">
                            <a href="javascript:void(0)">
                                All notifications
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="last">
                    <a href="javascript:void(0)" data-toggle-state="sidebar-overlay-open" data-key="rightSideBar">
                        <i class="mdi mdi-playlist-plus"></i>
                    </a>
                </li>
                --}}
            @else
                <li class="hidden-xs hidden-sm">
                    {{Form::open(['class' => 'form-inline', 'url'=> route('login') , 'aria-label'=> __('Login') ])}}
                      <div class="form-group is-empty m-t-15">
                        <label class="sr-only" for="email">Username</label>
                        <input type="text" name="email" class="form-control" id="email" placeholder="E-mail" autocomplete="off">
                      </div>
                      <div class="form-group is-empty m-t-15">
                        <label class="sr-only" for="password">Password</label>
                        <input type="password" name="password" class="form-control" id="password" placeholder="Password" autocomplete="off">
                      </div>
                      <div class="form-group m-t-15">
                        <button type="submit" class="btn btn-sm btn-primary">Login</button>  or  <a href="{{url('/register')}}" class="btn btn-sm btn-primary register">Register</a>
                      </div>
                    {{Form::close()}}
                </li>
            @endif
            </ul>
        </div>
        {{--
        <form role="search" action="" class="navbar-form" id="navbar_form">
            <div class="form-group">
                <input type="text" placeholder="Search and press enter..." class="form-control" id="navbar_search" autocomplete="off">
                <i data-navsearch-close class="zmdi zmdi-close close-search"></i>
            </div>
            <button type="submit" class="hidden btn btn-default">Submit</button>
        </form>
        --}}
    </nav>
</header>

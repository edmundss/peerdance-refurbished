
    <ul class="nav nav-pills nav-stacked">
        <li class="sidebar-header"> </li>
        <li class="{{HTML::activeState('/home')}}"><a href="{{url('/home')}}"><i class="zmdi zmdi-home"></i>Home</a></li>
        <li class="{{HTML::activeState('/dance')}}"><a href="{{route('dance.index')}}"><i class="zmdi zmdi-swap-alt"></i>Dance Library</a></li>
        <li class="{{HTML::activeState('/forums')}}"><a href="{{route('chatter.home')}}"><i class="zmdi zmdi-comments"></i>Discussions</a></li>
        <li class="{{HTML::activeState('/song')}}"><a href="{{route('song.index')}}"><i class="fa fa-music"></i>Music</a></li>
        <li class=""  data-toggle="tooltip" data-original-title="Coming someday"><a href="#"><i class="zmdi zmdi-cocktail"></i>Social events</a></li>
        <li class="" data-toggle="tooltip" data-original-title="Coming someday"><a href="#"><i class="zmdi zmdi-accounts"></i>Dancers</a></li>

        @if ($active_challenge)
            <li class="{{HTML::activeState('/weeklyChallenge')}}"><a href="{{route('weeklyChallenge.show', $active_challenge)}}"><i class="fa fa-star"></i>Weekly Challenge</a></li>

        @endif

        <li class="" ><a href="{{ route('spotify.initial_authorization') }}">{!! file_get_contents('assets/img/icons/misc/spotify-brands.svg') !!}
        	@if(Auth::check())
        		@if($session_owner->spotify_refresh_token)
        			Spotify Connected
        		@else
        			Connect Spotify
        		@endif
        	@else
        		Connect Spotify
        	@endif
    	</a></li>


        @role('admin')
        <li class="nav-dropdown"><a href="#"><span></span><i class="zmdi zmdi-settings"></i>Administration</a>
            <ul class="nav-sub" data-index="8" style="display: block;">
                <li><a href="{{ route('admin.weeklyChallenge.index') }}">Weekly Challenges</a></li>
                <li><a href="{{route('role.index')}}">Roles</a></li>
                <li><a href="{{route('permission.index')}}">Permissions</a></li>
                <li><a href="{{ route('admin.user.index') }}">Users</a></li>
                <li><a href="{{route('difficulty.index')}}">Difficulty levels</a></li>
                <li><a href="{{route('dance_family.index')}}">Dance families</a></li>
            </ul>
        </li>
        @endrole



    </ul>

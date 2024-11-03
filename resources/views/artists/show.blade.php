@extends('layouts.fullwidthv1')

@section('content')
<div class="row">
	<div class="col-lg-4">
		<div class="card card-solid">
			<header class="card-heading">
				<h1 class="card-title">Artist info</h1>
				<ul class="card-actions icons right-top">
                    <li>
                      <a href="{{route('artists.edit', $artist->id)}}">
                        <i class="zmdi zmdi-edit"></i>
                      </a>
                    </li>
                </ul>
			</header>
				<table class="table no-margin">
					<tr>
						<th class="col-lg-3" ="col-lg-2">Name</th>
						<td>{{$artist->name}}</td>
					</tr>
					<tr>
						<th>Dances</th>
						<td>
							@foreach($dances as $id => $title)
							<a href="{{route('dances.show', $id)}}">{{$title}}</a>@if(!$loop->last), @endif
							@endforeach
						</td>
					</tr>
					<tr>
						<th colspan="2">Description</th>
					</tr>
				</table>
			<div class="card-body" id='description'>
				<p>
					{!!(strlen($artist->description) >0)?$artist->description:'No description'!!}
				</p>
			</div>
		</div>
    @include('layouts.partials._comments')
	</div>
</div>
@stop

@section('scripts')
	<script type="text/javascript">
		var protocol = (location.protocol == 'https:') ? 'https' : 'http';

		var videoEmbed = {
		    invoke: function(){

		        $('#description').html(function(i, html) {
		            return videoEmbed.convertMedia(html);
		        });

		    },
		    convertMedia: function(html){
		        var pattern1 = /(?:http?s?:\/\/)?(?:www\.)?(?:vimeo\.com)\/?(.+)/g;
		        var pattern2 = /(?:http?s?:\/\/)?(?:www\.)?(?:youtube\.com|youtu\.be)\/(?:watch\?v=)?(.+)/g;
		        var pattern3 = /([-a-zA-Z0-9@:%_\+.~#?&//=]{2,256}\.[a-z]{2,4}\b(\/[-a-zA-Z0-9@:%_\+.~#?&//=]*)?(?:jpg|jpeg|gif|png))/gi;

		        if(pattern1.test(html)){
		           var replacement = '<iframe width="420" height="345" src="//player.vimeo.com/video/$1" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';

		           var html = html.replace(pattern1, replacement);
		        }


		        if(pattern2.test(html)){
		              var replacement = '<div class="embed-responsive embed-responsive-16by9"><iframe class="embed-responsive-item" src="'+protocol+'://www.youtube.com/embed/$1" frameborder="0" allowfullscreen></iframe></div>';
		              var html = html.replace(pattern2, replacement);
		        }


		        if(pattern3.test(html)){
		            var replacement = '<a href="$1" target="_blank"><img class="sml" src="$1" /></a><br />';
		            var html = html.replace(pattern3, replacement);
		        }

		        return html;
		    }
		}
		$(function(){
    		videoEmbed.invoke();
		})
	</script>
@stop

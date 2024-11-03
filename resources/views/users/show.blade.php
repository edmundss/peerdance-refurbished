@extends('layouts.fullwidthv1')

@section('content')
	<div class="row">
		<div class="col-md-12 col-lg-3">
			<div class="card type--profile">
				<header class="card-heading">
                    @if($user->avatar)
    					<img src="{{$user->getAvatar('thumb')}}" alt="" class="img-circle avatar">
                    @else
                        <div class="default-avatar avatar" style="height:120px; width:120px;">
                            {{$user->name}}
                        </div>
                    @endif
                    @if(Auth::check())
					@if($user->id == $session_owner->id)
					<ul class="card-actions icons right-top">
						<li class="dropdown">
							<a href="javascript:void(0)" data-toggle="dropdown">
								<i class="zmdi zmdi-more-vert"></i>
							</a>
							<ul class="dropdown-menu dropdown-menu-right btn-primary">
								<li>
									<a href="{{ route('user.edit', $user) }}">Edit Profile</a>
								</li>
								<li>
									<a href="javascript:void(0)" data-toggle="modal" data-target="#avatar-form">Change Avatar</a>
								</li>
								<li>
									<a href="javascript:void(0)" data-toggle="modal" data-target="#cover-form">Change Cover image</a>
								</li>
							</ul>
						</li>
					</ul>
					@endif
					@endif
				</header>
				<div class="card-body">
					<h3 class="name">{{$user->name}}</h3>
					<span class="title">Dancer</span>
					<button type="button" class="btn btn-primary btn-round disabled">Follow</button>
				</div>

				@role('admin')
				<table class="table">
					<tr>
						<th>
							{{Lang::get('employees.roles')}} 
								<button id="hide-toggler" class="text-muted btn btn-default btn-xs off"><i class="fa fa-eye"></i></button>
						</th>
						<td>
							<ul style="padding-left:0px">
								@foreach($roles as $r)
										<li class="role @if(!$user->hasRole($r->name)) unused @endif" @if(!$user->hasRole($r->name)) style="display:none" @endif>
											{{Form::checkbox($r->id, null, null, array($user->hasRole($r->name)?'checked':''))}}
											<a href="{{route('role.show', $r->id)}}"> {{$r->display_name}}</a>
										</li>
								@endforeach
							</ul>
							
						</td>
					</tr>
				</table>
				@endrole
				<footer class="card-footer border-top">
					<div class="row row p-t-10 p-b-10">
						<div class="col-xs-4"><span class="count">{{$user->dances()->count()}}</span><span>Dances</span></div>
						<div class="col-xs-4"><span class="count">1.5m</span><span>Followers</span></div>
						<div class="col-xs-4"><span class="count">{{$user->steps()->count() + $user->combinations()->count()}}</span><span>Moves</span></div>
					</div>
				</footer>
			</div>
            <div class="card">
              <header class="card-heading">
                <h2 class="card-title">Move expertise</h2>
              </header>
              <div class="card-body">
                <canvas id="hello"></canvas>
              </div>
            </div>
		</div>
	</div>
@endsection

@section('css')
<style type="text/css">
	.page-profile #header_wrapper.profile-header {
		background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%, transparent), color-stop(30%, transparent), color-stop(100%, rgba(0, 0, 0, 0.45))), url({{($user->cover)?asset('image/covers/'.$user->cover->id.'/lg.jpg'):'../assets/img/headers/header-bg.png'}}) !important;
		background-repeat: no-repeat!important;
		background-size: cover!important;
	}
</style>
@endsection

@section('modals')
  <div id="avatar-form" class="modal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
          <h4 class="modal-title">Change your avatar</h4>
          </div>
            {{Form::open(array('url'=>route('user.upload_avatar', $user), 'method' => 'POST', 'files'=>true))}}
          <div class="modal-body">
            <div class="form-group">
            	{{Form::label('avatar', 'Select picture file')}}
            	{{Form::file('avatar', null, ['class' => 'form-control'])}}
            </div>
          </div>
          <div class="modal-footer">
              {{Form::submit('Add', array('class'=>'btn btn-primary'))}}
            </div>
          {{Form::close()}}
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
  </div>

  <div id="cover-form" class="modal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
          <h4 class="modal-title">Change profile cover picture</h4>
          </div>
            {{Form::open(array('url'=>route('user.upload_cover', $user), 'method' => 'POST', 'files'=>true))}}
          <div class="modal-body">
            <div class="form-group">
            	{{Form::label('cover', 'Select cover picture')}}
            	{{Form::file('cover', null, ['class' => 'form-control'])}}
            </div>
          </div>
          <div class="modal-footer">
              {{Form::submit('Add', array('class'=>'btn btn-primary'))}}
            </div>
          {{Form::close()}}
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
  </div>
@stop

@section('scripts')

    <script>
    $(function(){
        var chartjs 	
        $('.default-avatar').materialAvatar({
          shape: 'circle'
        });
        
        $.get("{{route('user.get_expertise', $user)}}", function(data){
        	console.log(data);
	        var ctx = document.getElementById("hello").getContext('2d');
	        var myChart = new Chart(ctx, {
	            type: 'doughnut',
	            data: {
	                labels: data.labels,
	                datasets: [{
	                    backgroundColor: data.datasets.backgroundColor,
	                    data: data.datasets.data
	                }]
	            }
	        });
        })

    })

    $(document).ready(function(){

		$('#hide-toggler').click(function(){
			$(this).toggleClass('off');
			if($(this).hasClass('off')) {
				$('.unused').hide();
			} else {
				$('.unused').show();
			}
		});

		$('.role input').on('change', function(){
			$.ajax({
				url:"{{route('user.role_update')}}",
				data:{
					user_id : {{$user->id}}, 
					role_id : $(this).attr('name'),
					assign : $(this).prop('checked'),
				},
				success:function(data){
					alertify.danger(data);
				}
			})
			.fail(function(){
				console.log(data);
				alertify.danger('KĻŪDA! Lomas izmaiņas neizdevās.');
				
			});
		});
	});
    </script>

@endsection

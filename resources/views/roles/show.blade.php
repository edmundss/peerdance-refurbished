@extends('layouts.fullwidthv2')

@section('content')
<div class="row">
	<div class="col-lg-4">
		<div class="card card-solid">
			<header class="card-heading">
				<h1 class="card-title">Par lomu</h1>
				<a href="{{route('role.edit', $role->id)}}" class="btn btn-primary btn-xs btn-flat pull-right">Labot</a>
			</header>
			<table class="table">
				<tr>
					<th>Sistēmas nosaukums</th>
					<td>{{$role->name}}</td>
				</tr>
				<tr>
					<th>Ekrāna nosaukums</th>
					<td>{{$role->display_name}}</td>
				</tr>
				<tr>
					<th>Apraksts</th>
					<td>{{$role->description}}</td>
				</tr>
			</table>
		</div>
	</div>
	<div class="col-lg-4">
		<div class="card card-solid">
			<header class="card-heading">
				<h1 class="card-title">Tiesības</h1>
			</header>
			<div class="card-body">
				@foreach($permissions as $p)
					{{Form::checkbox($p->name, $p->id, $role->permissions->contains($p->id), ['class' => 'permission'])}}
					{{$p->display_name}}<br>
				@endforeach
			</div>
		</div>
	</div>
	<div class="col-lg-4">
		<div class="card card-solid">
			<header class="card-heading">
				<h1 class="card-title">Darbinieki</h1>
			</header>
			<div class="card-body">
				<ul>
					@foreach($role->users as $u)
						<li><a href="{{route('user.show', $u->id)}}">{{$u->name}}</a></li>
					@endforeach
				</ul>
			</div>
		</div>
	</div>
</div>
@stop

@section('scripts')
	<script type="text/javascript">
		$(function(){
			$('.permission').click(function(){
				$.get(
				'{{route('roles.update_permissions')}}',
				{
					role: {{$role->id}},
					assigned: $(this).is(':checked'),
					permission: $(this).val()
				},
				function(data){
					console.log(data);
					$.notify({
		              // options
		              message: data 
		            },{
		              // settings
		              type: 'success'
		            });
				})
				.fail(function(){
					$.notify({
		              // options
		              message: "Tiesību piešķiršana neizdevās." 
		            },{
		              // settings
		              type: 'danger'
		            });
				})
			});
		});
	</script>
@stop
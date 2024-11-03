@extends('layouts.fullwidthv2')
@section('content')
<div class="card card-solid">
	<header class="card-heading">
		<a href="{{route('role.create')}}" class="btn btn-xs btn-primary pull-right">Pievienot</a>
	</header>
	<table class="table">
		<thead>
			<tr>
				<th>Īsais nosaukums</th>
				<th>Pilnais nosaukums</th>
				<th>Apraksts</th>
			</tr>
		</thead>
		<tbody>
		@if(count($roles)>0)
			@foreach($roles as $p)
			<tr>
				<td><a href="{{route('role.show', $p->id)}}">{{$p->name}}</a></td>
				<td>{{$p->display_name}}</td>
				<td>{{$p->description}}</td>
			</tr>
			@endforeach
		@else
			<tr><td>No roles</td></tr>
		@endif
		</tbody>

	</table>
</div>
@stop
@extends('layouts.fullwidthv1')
@section('content')
<div class="row">
	<div class="col-lg-4 col-lg-offset-4">
		<div class="card card-solid">
			<header class="card-heading with-border">
				<h1 class="card-title">Forma</h1>
			</header>
			{{Form::open(['url' => route('role.store'), 'method' => 'POST'])}}
			<div class="card-body">
					<div class="form-group">
						{{Form::label('name', 'Sistēmas nosaukums (viens-vārds)')}}
						{{Form::text('name', null, ['class' => 'form-control'])}}
					</div>
					<div class="form-group">
						{{Form::label('display_name', 'Ekrāna nosaukums')}}
						{{Form::text('display_name', null, ['class' => 'form-control'])}}
					</div>
					<div class="form-group">
						{{Form::label('description', 'Apraksts')}}
						{{Form::textarea('description', null, ['class' => 'form-control'])}}
					</div>
			</div>
			<div class="card-footer">
				{{Form::submit('Pievienot', ['class' => 'btn btn-success'])}}
			</div>
			{{Form::close()}}
		</div>
	</div>
</div>
@stop


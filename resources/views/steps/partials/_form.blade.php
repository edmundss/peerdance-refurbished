				<div class="form-group">
					{{Form::label('dance_id', 'Dejas nosaukums')}}
					{{Form::select('dance_id', $dance, $dance_id, ['class' => 'form-control', 'disabled'=>'disabled'])}}
				</div>
				<div class="form-group">
					{{Form::label('title', 'Nosaukums')}}
					{{Form::text('title', null, ['class' => 'form-control'])}}
				</div>
				<div class="form-group">
					{{Form::label('description', 'Apraksts')}}
					{{Form::textarea('description', null, ['class' => 'form-control'])}}
				</div>
				<div class="form-group">
					{{Form::label('difficulty', 'Līmenis')}}
					{{Form::select('difficulty', $difficulty_levels, 1, ['class' => 'form-control'])}}
				</div>
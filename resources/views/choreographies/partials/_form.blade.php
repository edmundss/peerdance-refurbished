				<div class="form-group">
					{{Form::label('dance_id', 'Dance')}}
					{{Form::select('dance_id', $dance, $dance_id, ['class' => 'form-control', 'disabled'=>'disabled'])}}
				</div>
				<div class="form-group">
					{{Form::label('title', 'Name of the routine')}}
					{{Form::text('title', null, ['class' => 'form-control'])}}
				</div>
				<div class="form-group">
					{{Form::label('author', 'Author of routine')}}
					{{Form::text('author', null, ['class' => 'form-control', 'placeholder' => 'If known...'])}}
				</div>
				<div class="form-group">
					{{Form::label('description', 'Description')}}
					{{Form::textarea('description', null, ['class' => 'form-control', 'id'=>''])}}
				</div>
				<div class="form-group">
					{{Form::label('difficulty', 'Difficulty level')}}
					{{Form::select('difficulty', $difficulty_levels, 1, ['class' => 'form-control'])}}
				</div>
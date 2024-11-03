				<div class="form-group">
					{{Form::label('title', 'Name of the dance')}}
					{{Form::text('title', null, ['class' => 'form-control'])}}
				</div>
				<div class="form-group">
					{{Form::label('dance_family_id', 'Belongs to dance family')}}
					{{Form::select('dance_family_id', $dance_families, null, ['class' => 'form-control select'])}}
				</div>
				<div class="form-group">
					{{Form::label('description', 'Description')}}
					{{Form::textarea('description', null, ['class' => 'form-control'])}}
				</div>
				<div class="form-group">
					{{Form::label('logo', 'Profile picture')}}
					{{Form::file('logo', null, ['class' => 'form-control'])}}
				</div>
				<div class="form-group">
					{{Form::label('cover', 'Cover picture')}}
					{{Form::file('cover', null, ['class' => 'form-control'])}}
				</div>
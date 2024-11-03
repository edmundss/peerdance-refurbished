            <div class="form-group">
              {{Form::label('step_id', 'Select step from library')}}
              {{Form::select('step_id', $step, null, ['class' => 'form-control', 'id'=>'step_id'])}}
            </div>
            <div class="text-center">
            	-- AND/OR --
            </div>
            <div class="form-group">
              {{Form::label('description', 'Add free form description')}}
              {{Form::textarea('description', null, ['class' => 'form-control'])}}
            </div>
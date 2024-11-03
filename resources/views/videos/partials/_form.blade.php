						<div class="form-group">
							{{Form::label('title', 'Name of the video')}}
							{{Form::text('title', null, ['class'=>"form-control"])}}
						</div>
						<div class="form-group">
							{{Form::label('video_id', 'Youtube video code')}}
							{{Form::text('video_id', null, ['class'=>"form-control", 'placeholder'=> 'Only video code. eq.:2pZ2zI86B4E'])}}
						</div>
						<div class="form-group">
							{{Form::label('type', 'Type')}}
							{{Form::select('type', config('constants.video_types'), null, ['class'=>"form-control select"])}}
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									{{Form::label('start', 'Starts from')}}
									<div class="input-group">
										{{Form::text('start', null, ['class'=>"form-control"])}}
										<span class="input-group-addon last">s</span>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									{{Form::label('end', 'Ends at')}}
									<div class="input-group">
										{{Form::text('end', null, ['class'=>"form-control"])}}
										<span class="input-group-addon last">s</span>
									</div> 
								</div>
							</div>
						</div>
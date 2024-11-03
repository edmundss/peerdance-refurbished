@extends('layouts.fullwidthv1')

@section('content')
    <div class="row">
        <div class="col-md-offset-3 col-md-6">
            <div class="card">
                {{Form::open(['url' => route('admin.weeklyChallenge.store'), 'method' => 'POST'])}}
                    <div class="card-body">
                        @include('admin.weekly_challenges.partials._form')
                    </div>
                    <footer class="card-footer border-top">
                        {{Form::submit('Save', ['class' => 'btn btn-primary'])}}
                    </footer>
                {{Form::close()}}
            </div>
        </div>
    </div>
@endsection
@section('css')
	<link rel="stylesheet" href="{{asset('plugins/select2/dist/css/select2.min.css')}}">
@stop

@section('scripts')
    <script src="{{asset('plugins/select2/dist/js/select2.min.js')}}"></script>

    <script type="text/javascript">

        var routes = {
            Step: "{{route('step.select2')}}",
            Combination: "{{route('combination.select2')}}",
            Choreography: "{{route('choreography.select2')}}",
        };

        var initialize_select2 = function () {

            $("select[name=parent_id]").select2({
                placeholder: "Select the "+$('select[name=parent_class]').val()+" from library",
                ajax: {
                    dataType: 'json',
                    url: routes[$('select[name=parent_class]').val()],
                    delay: 250,
                    selectOnBlur: true,
                    data: function(params) {
                        return {q: params.term};
                    },
                    processResults: function(data)
                    {
                        return { results: data }
                    },
                },
            });
        }

        $(function(){
            $('input[name=end]').bootstrapMaterialDatePicker({ format: 'YYYY-MM-DD HH:mm:ss' });
            initialize_select2();

            $('select[name=parent_class]').change(function(){
                $("select[name=parent_id]").select2("destroy");
                initialize_select2();
            })
        })

    </script>
@endsection

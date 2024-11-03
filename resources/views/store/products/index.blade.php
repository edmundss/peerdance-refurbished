@extends('layouts.store')

@section('content')
    <div class="row">
        <div class="col-lg-8 col-lg-offset-2">
            <div class="row">
                @foreach ($products as $p)
                    <div class="col-lg-3">
                        <div class="card">
                            <header class="card-heading card-image app_primary_bg">
                                <!-- IMAGE GOES HERE -->
                                <img src="{{$p->thumbnail_url}}" alt="" class="mCS_img_loaded">
                                <h2 class="card-title left-bottom overlay text-white">{{$p->name}}</h2>
                            </header>
                            <div class="card-footer border-top">
                                <ul class="card-actions left-bottom">
                                    <li>
                                        <a href="{!! route('summerhop.product.show', $p->id) !!}" class="btn btn-default btn-flat">
                                            Skatīt
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@section('css')
    <style media="screen">
    .card .card-heading.card-image .card-title.overlay {
        border-bottom: none;
        padding: 10px;
        height: auto;
        text-indent: 0px;
    }
    </style>
@endsection

@extends('layouts.store')

@section('content')

    <div class="row">
        <div class="col-lg-8 col-lg-offset-2">
            <div class="row">
                <div class="col-lg-4">
                    <div class="card card-gallery">
                        <header class="card-heading card-image app_primary_bg">
                            <img id="title-pic" src="{{$variant->pictures()->first()->preview_url}}" alt="" class="mCS_img_loaded">
                            {{-- <h2 class="card-title left-bottom overlay text-white">{{$product->name}}</h2> --}}
                        </header>
                        <div class="card-body p-0">
                            <div class="gallery row" id="gallery" itemscope itemtype="http://schema.org/ImageGallery">
                                @foreach ($product->product_variants()->groupBy('color')->get() as $v)
                                    @foreach($v->pictures as $p)
                                        <figure itemprop="associatedMedia" class="col-xs-3" itemscope itemtype="http://schema.org/ImageObject">
                                            <a href="javascript:void(0)" onclick="setColor('{{$v->color}}')">
                                                <img src="{{$p->thumbnail_url}}" itemprop="thumbnail" alt="Image description" >
                                            </a>
                                            {{-- <a href="{{$p->preview_url}}" itemprop="contentUrl" data-size="1000x1000">
                                            </a> --}}
                                        </figure>
                                    @endforeach

                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="card">
                        <header class="card-heading">
                            <h2 class="card-title">{{$product->name}}</h2>
                        </header>
                        <div class="card-body">
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation
                                ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint
                                occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-lg-4 c text-right">
                                        {{Form::label('size', 'Izmērs:')}}
                                    </div>
                                    <div class="col-lg-4">
                                        {{Form::select('size', $sizes, $variant->size, ['class' => 'form-control select'])}}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4 c text-right">
                                        {{Form::label('color', 'Krāsa:')}}
                                    </div>
                                    <div class="col-lg-4">
                                        {{Form::select('color', $colors, $variant->color, ['class' => 'form-control '])}}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4 c text-right">
                                        {{Form::label('price', 'Cena:')}}
                                    </div>
                                    <div class="col-lg-4">
                                        <p>€<span id="price">{{$variant->price}}</span></p>
                                    </div>
                                </div>

                            </div>
                            <footer class="card-footer text-right">
                                <button type="button" name="button" class="btn btn-primary" data-toggle="modal" data-target="#order-modal">Pieteikties</button>
                            </footer>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        var getProductVariantJson = () => {
            $.get("{!! route('productVariant.get_json') !!}", {
                product_id: {{$product->id}},
                size: $('select[name=size]').val(),
                color: $('select[name=color]').val(),
            }, function(data) {
                console.log(data);
                $('input[name=product_variant_id]').val(data.id);
                $('#title-pic').attr('src', data.preview_url)
                $('#price').text(data.price)
                if (data.available_sizes) {
                    alertify.error($('select[name=size]').val() + ' izmērs šai krāsai nav pieejams!');
                    $('select[name=size]').val(data.available_sizes[0]).trigger('change');
                }
            });
        };

        $('select[name=color]').change(function(){
            getProductVariantJson();
        })

        $('select[name=size]').change(function(){
            getProductVariantJson();
        })

        var setColor = (color) => {
            $('select[name=color]').val(color);
            getProductVariantJson();
        }
        $('#title-pic').click(function(){
            $('#modal-pic').attr('src', $(this).attr('src'));
            $('#basic_modal').modal();
        });
    </script>
@endsection

@section('modals')
    <div class="modal fade" id="basic_modal" tabindex="-1" role="dialog" aria-labelledby="basic_modal" style="display: none;">
        <div class="modal-dialog" role="document" style="width:800px">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <img id="modal-pic" src="{{$variant->pictures()->first()->preview_url}}" alt="" class="mCS_img_loaded">

                    <!-- modal-content -->
                </div>
                <!-- modal-dialog -->
            </div>
        </div>
    </div>
    <div class="modal fade" id="order-modal" tabindex="-1" role="dialog" aria-labelledby="basic_modal" style="display: none;">
        <div class="modal-dialog" role="document" style="width:800px">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Pieteikties</h4>
                </div>
                {{Form::open(['url' => route('order.store')])}}
                <div class="modal-body">
                    {{Form::hidden('product_variant_id', $variant->id)}}
                    <div class="form-group">
                        {{Form::label('name', 'Tavs vārds')}}
                        {{Form::text('name', session('order_name', null), ['class' => 'form-control'])}}
                    </div>
                    <div class="form-group">
                        {{Form::label('email', 'Epasta adrese')}}
                        {{Form::text('email', session('order_email', null), ['class' => 'form-control'])}}
                    </div>
                    <!-- modal-content -->
                </div>
                <div class="modal-footer">
                    {{Form::submit('Pieteikties', ['class' => 'btn btn-primary'])}}
                </div>
                {{Form::close()}}
                <!-- modal-dialog -->
            </div>
        </div>
    </div>
@endsection

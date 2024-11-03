@extends('layouts.fullwidthv1')

@section('content')
    <div class="container">
        <div class="stack">
            <div class="final-message">No more kitten :'(</div>
            <ul>
                <li>
                    <div id="ecard1" class="ecard js-swiping-ecard shadow-z-5">
                        <div class="video-grabber"></div>
                        <iframe frameborder="0" height="100%" width="100%" src="https://youtube.com/embed/GvHOEJPabuk?autoplay=0&controls=0&showinfo=0&autohide=1"></iframe>
                    </div>
                </li>
                <li>
                    <div id="ecard2" class="ecard js-swiping-ecard">
                        <div class="video-grabber"></div>
                        <iframe frameborder="0" height="100%" width="100%" src="https://youtube.com/embed/u4i_mRW6mMI?autoplay=0&controls=0&showinfo=0&autohide=1"></iframe>
                    </div>
                </li>
                <li>
                    <div id="ecard3" class="ecard js-swiping-ecard">
                        <div class="video-grabber"></div>
                        <iframe frameborder="0" height="100%" width="100%" src="https://youtube.com/embed/CEy42GgHc2I?autoplay=0&controls=0&showinfo=0&autohide=1"></iframe>
                    </div>
                </li>
                <li>
                    <div id="ecard4" class="ecard js-swiping-ecard">
                        <div class="video-grabber"></div>
                        <iframe frameborder="0" height="100%" width="100%" src="https://youtube.com/embed/R_QW9x7bQsc?autoplay=0&controls=0&showinfo=0&autohide=1"></iframe>
                    </div>
                </li>
                <li>
                    <div id="ecard5" class="ecard js-swiping-ecard">
                        <div class="video-grabber"></div>
                        <iframe frameborder="0" height="100%" width="100%" src="https://youtube.com/embed/SVabiie1RWg?autoplay=0&controls=0&showinfo=0&autohide=1"></iframe>
                    </div>
                </li>
                <li>
                    <div id="ecard6" class="ecard js-swiping-ecard">
                        <div class="video-grabber"></div>
                        <iframe frameborder="0" height="100%" width="100%" src="https://youtube.com/embed/SVabiie1RWg?controls=0&showinfo=0&autohide=1"></iframe>
                    </div>
                </li>
            </ul>
        </div>
        <div class="btn-controls text-center">
            <button type="button" class="btn btn-fab btn-danger js-left-trigger">
                <i class="zmdi zmdi-thumb-down"></i>
            </button>
            <button type="button" class="btn btn-fab btn-android js-right-trigger">
                <i class="zmdi zmdi-thumb-up"></i>
            </button>
        </div>
    </div>
@endsection

@section('css')
    <style media="screen">
    ul {
        padding: 0;
    }
    ul li {
        list-style: none;
    }

    .final-message {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    .video-grabber {
        background-color: black;
        width: calc(100% - 60px);
        height: calc(100% - 60px);
        position: absolute;
        margin-top: 0px;
        opacity: 0;
    }

    .stack {
        position: relative;
        height: 350px;
        width: 700px;
        max-width: calc(100vw - 60px);
        margin: 50px auto;
    }

    .ecard {
        height: 350px;
        width: 700px;
        max-width: 100%;
        padding: 30px;
        background: white;
        /* border: 1px solid grey; */
        border-radius: 5px;
        position: absolute;
        top: 0;
        left: 0;
    }
    .ecard .ecard-illustration {
        height: 100%;
        width: 100%;
        background-size: cover;
        background-position: 50% 50%;
        transform: scale(1);
        transition: transform 0.5s;
    }
    .ecard:hover .ecard-illustration, .ecard:focus .ecard-illustration {
        transform: scale(1.1);
    }

    .js-swiping-ecard {
        transition: transform 0.5s;
    }
    .js-swiping-ecard.done {
        display: none;
    }

    /*
    @TODO :
    - add text animation on ecards ?
    - add proper behaviour depending on media queries (faster on mobile than on desktop, different size, ...)
    */

    </style>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/hammer.js/2.0.8/hammer.js" charset="utf-8"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.lazyload/1.9.1/jquery.lazyload.js" charset="utf-8"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/jquery.lazy/1.7.1/jquery.lazy.min.js"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/jquery.lazy/1.7.1/plugins/jquery.lazy.youtube.min.js"></script>

    <script type="text/javascript">
    /*
    Dependencies:
    - jQuery
    - Hammer
    - jQuery.lazyload
    */

    //Lazyload
    $('.js-lazyload').lazyload({
        effect: 'fadeIn',
        threshold: 50,
    });
     $("iframe[data-src]").Lazy();

    //Globals
    var $topecard,
    //deltaThreshold is the swipe distance from the initial place of the ecard
    deltaThreshold = 100,
    deltaX = 0;

    var add_shadow = function () {
        $('.js-swiping-ecard').last().addClass('shadow-z-5')

    }

    function swipeEnded(event, direction, $ecard, add_shadow) {
        var  directionFactor,
        transform;
        //If the event has a type, then it is triggered from a button and has a given direction
        if (event.type === 'click') {
            directionFactor = direction === 'right' ? -1 : 1;
        }
        //If the event has a deltaX, then it is triggered from a gesture and has a calculated direction
        else if (event.deltaX) {
            directionFactor = event.deltaX >= 0 ? -1 : 1;
        }

        //If the threshold is reached or a trigger clicked, the ecard is thrown on a side and then disappear
        if ( event.deltaX && deltaX > deltaThreshold || event.deltaX && deltaX < -1 * deltaThreshold || direction) {
            transform = 'translate(' + directionFactor * -100 + 'vw, 0) rotate(' + directionFactor * -5 + 'deg)';
            $ecard
            .delay(100)
            .queue(function () {
                $(this).css('transform', transform).dequeue();
            })
            .delay(300)
            .queue(function () {
                $(this).addClass('done').remove();
            });

            //Do something
            console.log('Swipe done. \necard:', $ecard, '\nDirection:', directionFactor);


        }
        //If the threshold isn't reached, the ecard goes back to its initial place
        else {
            transform = 'translate(0, 0) rotate(0)';
            $ecard.css({
                'transform': transform,
            });
        }
    }

    function swipeLeft(event, $ecard) {
        var transform;
        deltaX = event.deltaX;
        transform = 'translate(' + deltaX * 0.8 + 'px, 0) rotate(5deg)';
        //translate the ecard on swipe
        $ecard.css({
            'transform': transform,
        });

    }

    function swipeRight(event, $ecard) {
        var transform;
        deltaX = event.deltaX;
        transform = 'translate(' + deltaX * 0.8 + 'px, 0) rotate(-5deg)';
        //translate the ecard on swipe
        $ecard.css({
            'transform': transform,
        });

    }

    //Events
    $('.js-swiping-ecard').each(function(index, element) {
        var $ecard = $(element),
        //Add hammer events on element
        hammertime = new Hammer(element);

        //Mobile gesture
        hammertime.on('panleft swipeleft', function(event) {
            swipeLeft(event, $ecard);

        });
        hammertime.on('panright swiperight', function(event) {
            swipeRight(event, $ecard);

        });
        hammertime.on('panend', function(event) {
            swipeEnded(event, false, $ecard);

        });
    });

    //Btn controls
    $('.js-left-trigger').on('click', function(event) {
        var $topecard= $('.js-swiping-ecard').last();
        swipeEnded(event, 'left', $topecard);
    });
    $('.js-right-trigger').on('click', function(event) {
        var $topecard = $('.js-swiping-ecard').last();
        swipeEnded(event, 'right', $topecard);
    });

    $(function(){
        $('.video-grabber').on('click', function() {
            $(this).next()[0].src += "&autoplay=1";
            // $("#video")[0].src += "?autoplay=1";
        });
    })


    </script>
@endsection

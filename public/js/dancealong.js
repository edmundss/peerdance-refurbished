var startSeconds = 0;
var endSeconds = 0;

$(function() {

    $('input[type=checkbox]').on('click', function() {
        var data_id = $(this).attr('data-id');

        startSeconds = 0;
        endSeconds = 0;

        if ($(this).is(':checked')) {
            $('.second-input-' + data_id).addClass('use-time');
        } else {
            $('.second-input-' + data_id).removeClass('use-time');
        }

        $('.start-seconds.use-time').each(function() {
            var value = parseInt($(this).val());
            if (startSeconds == 0) {
                startSeconds = value;
            }
            startSeconds = (value < startSeconds) ? value : startSeconds;
        });

        $('.end-seconds.use-time').each(function() {
            var value = parseInt($(this).val());
            endSeconds = (value > endSeconds) ? value : endSeconds;
        });

        console.log('start:' + startSeconds + ' end:' + endSeconds);
    })
})

// 2. This code loads the IFrame Player API code asynchronously.
var tag = document.createElement('script');

tag.src = "https://www.youtube.com/iframe_api";
var firstScriptTag = document.getElementsByTagName('script')[0];
firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

// 3. This function creates an <iframe> (and YouTube player)
//    after the API code downloads.
var player;

function onYouTubeIframeAPIReady() {
    player = new YT.Player('player', {
        'height': '390',
        'width': '640',
        'videoId': '{{$video->video_id}}'
    });

}


function play() {
    player.loadVideoById({
        'videoId': '{{$video->video_id}}',
        'startSeconds': startSeconds,
        'endSeconds': endSeconds,
        'suggestedQuality': 'large'
    });
    player.playVideo();
}
// 4. The API will call this function when the video player is ready.
function onPlayerReady(event) {
    //event.target.playVideo();
}

// 5. The API calls this function when the player's state changes.
//    The function indicates that when playing a video (state=1),
//    the player should play for six seconds and then stop.
var done = false;

function onPlayerStateChange(event) {
    //console.log(event);
    if (event.data == 0) {
        play();

    }
}

function stopVideo() {
    player.stopVideo();
}

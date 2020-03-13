$(function() {
    $('a.challenge-hint').on('click', function(e) {
        e.preventDefault();

        if ($('.info-hint').length) {
            $('.info-hint').fadeToggle();
        } else {
            $.getJSON('?get-hint', function(data) {
                if (data.status) {
                    $hint = $('<div/>', {html: data.hint, class: 'info info-hint'});
                    $('.challenge-header').parent().append($hint);

                    var $allVideos = $(".bbcode-youtube, .bbcode-vimeo");
                    $allVideos.each(function() {
                        var $el = $(this);
                        $el.removeAttr('height').height($el.width()*0.56);
                    });

                    $hint.hide().fadeIn();
                }
            });
        }
    });
    
//    $('.settings-profile-website-add').on('click', function(e) {
//        e.preventDefault();
//
//        // Duplicate existing input
//        var $input = $('.website-input:last');
//        $input.parent().append($input.clone());
//
//        // Clear inputs
//        $('.website-input:last input').val('');
//        $('.website-input:last i').attr('class', 'icon-globe');
//    });
});

var timer_start = new Date().getTime();
(function timer() {
    $('.timer').each(function() {
        var now = new Date().getTime();
        var time = now - timer_start;
        time = $(this).attr('data-time') - time/1000;
        if (time <= 0)
            time = 0;

        $(this).html('Time remaining: <span>'+time.toFixed(2)+' seconds</span>');
    });

    setTimeout(timer, 100);
})();
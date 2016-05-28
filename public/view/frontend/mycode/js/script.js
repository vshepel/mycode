$(document).ready(function() {
    $('#notification-open').click(function() {
        $('.notification').addClass('active');
        $('#overlay').show();
        $('body').css({
            overflow: "hidden"
        });
    });
    $('#overlay').click(function() {
        $('.notification').removeClass('active');
        $(this).hide();
        $('body').css({
            overflow: "inherit"
        });
    });
    $('#notification-close').click(function() {
        $('.notification').removeClass('active');
        $('#overlay').hide();
        $('body').css({
            overflow: "inherit"
        });
    });
    var textarea = document.querySelector('textarea');

    textarea.addEventListener('keydown', autosize);

    function autosize() {
        var el = this;
        setTimeout(function() {
            el.style.cssText = 'height:auto; padding:0';
            el.style.cssText = 'height:' + el.scrollHeight + 'px';
        }, 0);
    }
});

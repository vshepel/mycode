$(document).ready(function() {
    $(".search_icon").click(function() {
        $(".search").addClass("active");
        $(".search input").focus();
    });
    $(".search .mdi-close").click(function() {
        $(".search").removeClass("active");
    });
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
    jQuery.each(jQuery('textarea[data-autoresize]'), function() {
        var offset = this.offsetHeight - this.clientHeight;

        var resizeTextarea = function(el) {
            jQuery(el).css('height', 'auto').css('height', el.scrollHeight + offset);
        };
        jQuery(this).on('keyup input', function() { resizeTextarea(this); }).removeAttr('data-autoresize');
    });

});

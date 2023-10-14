jQuery(document).ready(function ($) {

    /* Валидация сообщений */
    document.addEventListener('wpcf7invalid', function (event) {
        $('.wpcf7-response-output').addClass('alert alert-danger');
    }, false);

    document.addEventListener('wpcf7spam', function (event) {
        $('.wpcf7-response-output').addClass('alert alert-warning');
    }, false);

    document.addEventListener('wpcf7mailfailed', function (event) {
        $('.wpcf7-response-output, .wpcf7-response-output.wpcf7-display-none.wpcf7-acceptance-missing').addClass('alert alert-warning');
    }, false);

    document.addEventListener('wpcf7mailsent', function (event) {
        $('.wpcf7-response-output').addClass('alert alert-success');
        $('.wpcf7-response-output').removeClass('alert-danger');
        $('button.wpcf7-submit').attr('disabled', 'disabled');
    }, false);

    $('input').bind('input', function() {
        $('button.wpcf7-submit').removeAttr('disabled');
    });

    $('.agree').prop('checked', false);

}); // jQuery End
$(function() {

    $('.fb-login').on('click', '.loginfb', function(e) {
        e.preventDefault();
    });



    // Helper function for adding a GET param to a url.
    function addParamToUrl(url, key, value) {
        url = url.split('#');
        url[0] += ((url[0].indexOf('?') > -1) ? '&' : '?') + key + '=' + encodeURIComponent(value);
        return url.join('#');
    }
});

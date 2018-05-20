side();
baseLinkTricks();

function side() {
    $('#button_to_section2').click(animationSide);
}

function baseLinkTricks() {
    $('#go_to_tricks').on('touchstart click', animationSide);
}

function animationSide() {
    var action = $_GET('action');
    if (action === null) {
        var elem = $('#index_section2');
        $(elem).removeClass('display-none');
        $('html, body').animate({scrollTop: elem.offset().top}, 1000);
    }
    else {
        $(location).attr('href', "index.php");
    }
}

function $_GET(param) {
    var vars = {};
    window.location.href.replace( location.hash, '' ).replace(
        /[?&]+([^=&]+)=?([^&]*)?/gi, // regexp
        function( m, key, value ) { // callback
            vars[key] = value !== undefined ? value : '';
        }
    );
    if ( param ) {
        return vars[param] ? vars[param] : null;
    }
    return vars;
}
removeFooter();
removeNavHeight();
side();
sideTouch();
baseLinkTricks();
animationArrowUp();
$('#btn-ajax').click(loadMoreTricks);

function arrowUp() {
    section = [];

    $('.trick').each(function () {
        section.push($($(this)));
    });
    
    $(window).scroll(function (e) {
        scrollTop = $(this).scrollTop() + ($(window).height() / 2);
        scroll = $(this).scrollTop() + ($(window).height());

        $(section[0]).addClass('removeArrowUp');
        $(section[14]).addClass('arrowUp');

        if(scroll > ($('.arrowUp').offset().top)){
            $('#arrow-up').removeClass('d-none');
        }

        if(scrollTop < $('.removeArrowUp').offset().top){
            $('#arrow-up').addClass('d-none');
        }
        });
}

function removeFooter() {
    if ($('.index_section1')) {
        footer = $('footer');
        $(footer).removeClass('d-flex');
        $(footer).addClass('display-none');
    }
}

function removeNavHeight() {
    if($('.index_section1')){
        $('.header-size').addClass('d-none');
    }
    else{
        $('.header-size').removeClass('d-none');
    }
}

function side() {
    $('#button_to_section2').click(animationSide);
}

function baseLinkTricks() {
    $('#go_to_tricks').on('touchstart click', animationSide);
}

function sideTouch() {
    $('.index_section1').on('touchstart', animationSideIf);
}

function animationSideIf(){
    if($('#index_section2').hasClass('display-none')){
        animationSide();
    }
}

function animationArrowUp() {
    $('.arrow-up').click(animationSide);
}

function animationSide() {
    var action = $_GET('action');
    if (action === null) {
        var elem = $('#index_section2');
        var footer = $('footer');
        $(elem).removeClass('display-none');
        $(footer).removeClass('display-none');
        $(footer).addClass('d-flex');
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

// AJAX

function loadMoreTricks() {
    firstMin = 0;

    $('.trick').each(function () {
        firstMin ++;
    });

    ResultMax = 8;

    $('#btn-ajax').after('<button class="btn btn-primary btn-ajax"><i class="fas fa-spinner fa-pulse"></i> Chargement </button>');
    $('#btn-ajax').remove();
    $.ajax({
        url: "/ajax/trick/",
        type: "POST",
        data: {
            numberFirst: firstMin,
            numberMax: ResultMax
        }}).done(function (data) {
            $(".btn-ajax").remove();
            $(".trick:last-child").after(data);
        arrowUp();
        test(ResultMax, firstMin);
    });

}

function test(ResultMax, firstMin){
    ifMax = 0;
    total = ResultMax + firstMin;

    $('.trick').each(function () {
        ifMax ++;
    });

    if(total <= ifMax){
        $(".trick:last-child").after('<button id="btn-ajax" class="btn btn-primary btn-ajax">Voir d\'autres Figures</button>');
        $('#btn-ajax').click(loadMoreTricks);
    }
    else {
        $("#btn-ajax").remove();
        $(".trick:last-child").after('<p class="no-more-tricks">Toutes les figures sont affichées.</p>')
    }
}
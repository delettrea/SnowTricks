var $collectionVideo;
var $collectionIllustration;

var $addVideoLink = $('<a href="#" class="add_video_link"><i class="fas fa-plus-circle"></i>Ajouter une autre Vidéo</a>');
var $newLinkVideo = $('<li class="remove-li"></li>').append($addVideoLink);
var $addIllustrationLink = $('<a href="#" class="add_illustration_link"><i class="fas fa-plus-circle"></i>Ajouter une autre Illustration</a>');
var $newLinkVideoIllustration = $('<li class="remove-li"></li>').append($addIllustrationLink);

jQuery(document).ready(function() {
    $collectionVideo = $('ul.videos');
    $collectionVideo.append($newLinkVideo);
    $collectionVideo.data('index', $collectionVideo.find(':input').length);
    $addVideoLink.on('click', function(e) {
        e.preventDefault();
        addInputForm($collectionVideo, $newLinkVideo);
    });

    $collectionIllustration = $('ul.illustrations');
    $collectionIllustration.append($newLinkVideoIllustration);
    $collectionIllustration.data('index', $collectionIllustration.find(':input').length);
    $addIllustrationLink.on('click', function(e) {
        e.preventDefault();
        addInputForm($collectionIllustration, $newLinkVideoIllustration);
    });
});

function addInputForm($collection, $newLink) {
    var prototype = $collection.data('prototype');

    var index = $collection.data('index');

    var newForm = prototype;

    newForm = newForm.replace(/__name__/g, index);

    $collection.data('index', index + 1);

    var $newFormLi = $('<li class="li-form-illustration d-flex flex-column"></li>').append(newForm);

    $newLink.before($newFormLi);
}
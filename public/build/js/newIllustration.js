var $collectionIllustration;

var $addIllustrationLink = $('<a href="#" class="add_illustration_link">Ajouter une autre Illustration</a>');
var $newLinkLiIllustration = $('<li></li>').append($addIllustrationLink);

jQuery(document).ready(function() {
    $collectionIllustration = $('ul.illustrations');

    $collectionIllustration.append($newLinkLiIllustration);


    $collectionIllustration.data('index', $collectionIllustration.find(':input').length);

    $addIllustrationLink.on('click', function(e) {
        e.preventDefault();

        addTagForm($collectionIllustration, $newLinkLiIllustration);
    });
});

function addTagForm($collectionIllustration, $newLinkLiIllustration) {
    var prototype = $collectionIllustration.data('prototype');

    var index = $collectionIllustration.data('index');

    var newForm = prototype;

    newForm = newForm.replace(/__name__/g, index);

    $collectionIllustration.data('index', index + 1);

    var $newFormLi = $('<li></li>').append(newForm);
    $newLinkLiIllustration.before($newFormLi);
}
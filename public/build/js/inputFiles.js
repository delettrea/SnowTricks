avatarRegistration();
illustration();

function avatarRegistration(){
    if($('registration-section')){
        $('input[type=file]').change(function () {
            fileInput = $("input[type=file]").val();
            avatarLabel = fileInput.replace("C:\\fakepath\\", "Votre avatar : ");
            $('.div-inputFile label').html(avatarLabel);
        });
    }
}

function illustration(){
    if($('section-addIllustration') || $('illustrations') || $('div-inputFile')){
        $('input[type=file]').change(function () {
            fileInput = $("input[type=file]").val();
            illustrationLabel = fileInput.replace("C:\\fakepath\\", "Votre image : ");
            $('.li-form-illustration div div label').html(illustrationLabel);
        });
    }
}
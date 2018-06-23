avatarRegistration();

function avatarRegistration(){
        $('input[type=file]').change(function () {
            fileInput = $("input[type=file]").val();
            avatarLabel = fileInput.replace("C:\\fakepath\\", "Votre avatar : ");
            $('.div-inputFile label').html(avatarLabel);
        });
}


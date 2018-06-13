// edit name of your avatar if is choice
$('input[type=file]').change(function () {
    fileInput = $("input[type=file]").val();
   avatarLabel = fileInput.replace("C:\\fakepath\\", "Votre avatar : ");
    $('.div-inputFile label').html(avatarLabel);
});
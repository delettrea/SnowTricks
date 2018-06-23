illustration();

function illustration(){
        $('input[type=file]').change(function () {
            fileInput = $('input[type=file]').val();
            console.log(fileInput);
            illustrationLabel = fileInput.replace("C:\\fakepath\\", "Votre image : ");
            console.log(illustrationLabel);
            $('.div-inputFile label').html(illustrationLabel);
        });
}
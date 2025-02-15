$(function () {

    function VerificaStatus() {

        $('#img-autorizacao').html('<div>Verificando status, aguarde...</div>');

        var dados = 'acao=status';
        $.post('whatsapp/acoes.php', { dados: dados }, function (data) {

            $('#bt-concectou').click();

            if(data.accountStatus == 'got qr code')
            {
                $('#img-autorizacao').html('<img src="'+data.qrCode+'" /><div>Escanei a imagem com seu WhatsApp e clique novamente em VERICICAR STATUS</div>');
            }
            else if(data.accountStatus == 'authenticated')
            {
                $.ajax('whatsapp/whatsapp.php').done(function(data) {
                    $("#content").html(data);
                });
                return false;
            }

        }, 'json');
    }


    function VerificaValidadeQRCode()
    {
        var dados = 'acao=status';
        $.post('whatsapp/acoes.php', { dados: dados }, function (data) {
            if(data.accountStatus != 'authenticated')
            {
                $('#img-autorizacao').html('<img src="'+data.qrCode+'" /><div>Escanei a imagem com seu WhatsApp</div>');
            }
        }, 'json');
    }


    $('#status').click(function () {
        $('#chama-modal-conectando-dialog').click();
        VerificaStatus();
    });


    //VerificaStatus();

    /*
    setInterval(function () {
        VerificaValidadeQRCode();
    }, 30000)
    */

});
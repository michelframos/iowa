$(function () {

    function VerificaStatus() {

        $('#img-autorizacao').html('<div>Verificando status, aguarde...</div>');

        var dados = 'acao=status';
        $.post('whatsapp/acoes.php', { dados: dados }, function (data) {

            console.log(data);
            $('#bt-concectou').click();

            if(data.accountStatus == 'got qr code')
            {
                $('#conectando-dialog #bt-concectou').click();
                $('#img-autorizacao').html('<img src="'+data.qrCode+'" /><div>Escanei a imagem com seu WhatsApp e clique novamente em VERICICAR STATUS</div>');
            }
            else if(data.accountStatus == 'authenticated')
            {
                $('#conectando-dialog #bt-concectou').click();
                $('#content').load('promocoes/envios.php', { id: $('#id_promocao').val() });
                /*
                $.ajax('promocoes/envios.php').done(function(data) {
                    $("#content").html(data);
                });
                */
                return false;
            } else if(data.accountStatus == 'loading')
            {
                $('#conectando-dialog #bt-concectou').click();
                $('#img-autorizacao').html('<div>O WhatsApp está aberto em outro computador ou navegador.</div>');
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
        //$('#chama-modal-conectando-dialog').click();
        VerificaStatus();
    });


    //VerificaStatus();

    /*
    setInterval(function () {
        VerificaValidadeQRCode();
    }, 30000)
    */


    /*Envio das mensagens*/
    $('#id_unidade').change(function () {

        var dados = $('#formPesquisa').serialize()+'&acao=listar-turmas';
        $.post('promocoes/acoes.php', { dados: dados }, function (data) {

            $('#id_turma').html(data);

        });

    });

    /*Selecionar Todos*/
    $('#contatos').on('click', '#selecionar-todos', function (){

        $('#contatos .aluno').each(function(){
                if ($('#contatos #selecionar-todos').prop( "checked")){
                    $(this).prop( "checked", true);
                } else {
                    $(this).prop("checked", false);
                }
            }
        );

    });


    $('#pesquisar').click(function () {

        $('#contatos').html('<div>Carregando contatos, por favor aguarde...</div>');

        var dados = $('#formPesquisa').serialize()+'&acao=listar-alunos';
        $.post('promocoes/acoes.php', { dados: dados }, function (data) {

            $('#contatos').html(data);

        });

    });


    $('#enviar').click(function () {

        $('#ms-envio-dialog').click();

        var alunos = '';

        $('#contatos .aluno:checked').each(function(){
            alunos+=$(this).val()+'|';
        });

        // Captura os dados do formulário
        var formulario = document.getElementById('formMensagem');

        // Instância o FormData passando como parâmetro o formulário
        var formData = new FormData(formulario);
        formData.append('alunos', alunos);

        // Envia O FormData através da requisição AJAX
        $.ajax({
            url: "promocoes/enviar-mensagem.php",
            type: "POST",
            data: formData,
            dataType: 'json',
            processData: false,
            contentType: false,
            success: function(retorno){
                console.log(retorno);
                if(retorno.status == 'ok'){
                    $('#bt-enviou').click();
                    $('#ms-envio-ok-dialog').click();
                }else if(retorno.status == 'erro-cupons'){
                    $('#bt-enviou').click();
                    $('#ms-envio-erro-cupons-dialog').click();
                }

            }
        });

    });

    $('#desconectar').click(function () {

        var dados = 'acao=sair';
        $.post('whatsapp/acoes.php', { dados: dados }, function (data) {

            console.log(data);

            if(data.result == 'Logout request sent to WhatsApp')
            {
                $.ajax('promocoes/promocoes.php').done(function(data) {
                    $("#content").html(data);
                    console.log(data);
                });
            }

        }, 'json');

    });

});
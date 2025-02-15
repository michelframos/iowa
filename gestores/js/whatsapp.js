$(function () {

    $('#formPesquisa').submit(function(){return false});

    $('#id_unidade, #id_idioma').change(function () {

        var dados = $('#formPesquisa').serialize()+'&acao=listar-turmas';
        $.post('whatsapp/acoes.php', { dados: dados }, function (data) {

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
        $.post('whatsapp/acoes.php', { dados: dados }, function (data) {

            $('#contatos').html(data);

        });

    });


    $('#enviar').click(function () {

        /*
        $('#ms-envio-dialog').click();

        var alunos = '';

        $('#contatos .aluno:checked').each(function(){
            alunos+=$(this).val()+'|';
        });

        var dados = $('#formPesquisa').serialize()+'&'+$('#formMensagem').serialize()+'&arquivo='+arquivo+'&alunos='+alunos+'&acao=enviar-mensagem';
        $.post('whatsapp/acoes.php', { dados: dados }, function (data) {

            if(data.status == 'ok'){
                $('#bt-enviou').click();
                $('#ms-envio-ok-dialog').click();
            }

        }, 'json');
        */

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
            url: "whatsapp/enviar-mensagem.php",
            type: "POST",
            data: formData,
            dataType: 'json',
            processData: false,
            contentType: false,
            success: function(retorno){
                $('#bt-enviou').click();
                $('#ms-envio-ok-dialog').click();
            }
        });

    });

    $('#desconectar').click(function () {

        var dados = 'acao=sair';
        $.post('whatsapp/acoes.php', { dados: dados }, function (data) {

            console.log(data);

            if(data.result == 'Logout request sent to WhatsApp')
            {
                $.ajax('whatsapp/autorizar.php').done(function(data) {
                    $("#content").html(data);
                    console.log(data);
                });
            }

        }, 'json');

    });

});
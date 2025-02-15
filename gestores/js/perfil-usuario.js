$(function(){
    $('#formPerfil').submit(function(){return false});

    $('#imagem').change(function(){


        if($('#imagem')[0].files[0] !== ''){

            var data = new FormData();
            data.append('arquivo_imagem', $('#imagem')[0].files[0]);

            $.ajax({
                url: 'perfil-usuario/imagem.php',
                data: data,
                dataType: 'json',
                processData: false,
                contentType: false,
                type: 'POST',
                success: function(response)
                {

                },
                complete: function (response)
                {
                    $('#imagem-perfil').html('');
                    $("#content").html('').load('perfil-usuario/perfil-usuario.php');
                }
            });

        }

    });


    $('#salvar-dados').click(function(){

        $('#ms-salvando-dialog').click();

        var dados = $('#formPerfil').serialize()+'&acao=atualizar-dados';
        $.post('perfil-usuario/acoes.php', { dados: dados }, function(data){

            if(data.status == 'ok'){
                $('#bt-salvou').click();
                $('#ms-ok-modal').click();
            }

        }, 'json');

    });


    $('#salvar-senha').click(function(){

        $('#ms-salvando-dialog').click();

        var dados = $('#formPerfil').serialize()+'&acao=salvar-senha';
        $.post('perfil-usuario/acoes.php', { dados: dados }, function(data){

            if(data.status == 'ok'){
                $('#bt-salvou').click();
                $('#ms-ok-modal').click();
            }
            else if(data.status == 'senhas não correspondem'){
                $('#bt-salvou').click();
                $('#ms-senhas-dialog').click();
            }
            else if(data.status == 'senha atual incorreta') {
                $('#bt-salvou').click();
                $('#ms-senha-atual-dialog').click();
            }
            else if(data.status == 'senha em branco'){
                $('#bt-salvou').click();
                $('#ms-senha-atual-dialog').click();
            }

        }, 'json');

    });

});

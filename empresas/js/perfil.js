$(function(){
    $('#formPerfil').submit(function(){return false});

    $('#cnpj').mask('00.000.000/0000-00', {reverse: true});
    $('#cep').mask('00.000-000', {reverse: true});

    var options =  {
        onKeyPress: function(telefone, e, field, options) {
            var masks = ['(00)0000-00000', '(00)00000-0000'];
            var mask = (telefone.length>13) ? masks[1] : masks[0];
            $('#telefone1, #telefone2').mask(mask, options);
        }};

    $('#telefone1, #telefone2').mask('(00)0000-00000', options);


    $('#busca-cep').click(function(){

        var cidade_selecionada = '';
        var cidade = '';
        var dados = '&cep='+$('#cep').val()+'&acao=busca-cep';
        $.post('../includes/busca-cep.php', { dados: dados }, function(data){

            if(data.status == 'ok')
            {
                $("#rua").val(data.endereco);
                $("#bairro").val(data.bairro);
                $("#complemento").val(data.complemento);
                $("#numero").val('');
                cidade_selecionada = data.cidade;
            }


            $("#estado option:contains("+data.uf+")").attr('selected', true);

            $.post('../includes/lista-cidades.php', {estado: $('#estado').val()}, function(data_cidade){

                console.log(cidade_selecionada);

                $('#cidade').html(data_cidade);
                cidade = data_cidade;

                if(cidade != '') {
                    $("#cidade option:contains(" + cidade_selecionada + ")").attr('selected', true);
                }

            });

        }, 'json');

    });


    $('#imagem').change(function(){


        if($('#imagem')[0].files[0] !== ''){

            var data = new FormData();
            data.append('arquivo_imagem', $('#imagem')[0].files[0]);

            $.ajax({
                url: 'perfil/imagem.php',
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
                    $("#content").html('').load('perfil/perfil.php');
                }
            });

        }

    });


    $('#salvar').click(function(){

        if($('#senha').val() != $('#confirma_senha').val())
        {
            $('#ms-confirma-senha-modal').click();
            exit;
        }

        $('#ms-salvando-dialog').click();

        var dados = $('#formPerfil').serialize()+'&acao=atualizar-dados';
        $.post('perfil/acoes.php', { dados: dados }, function(data){

            if(data.status == 'ok'){
                $('#bt-salvou').click();
                $('#ms-ok-modal').click();
            }

        }, 'json');

    });


    $('#salvar-senha').click(function(){

        $('#ms-salvando-dialog').click();

        var dados = $('#formPerfil').serialize()+'&acao=salvar-senha';
        $.post('perfil/acoes.php', { dados: dados }, function(data){

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

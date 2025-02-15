$(function(){

    $('#formPesquisa').submit(function(){return false});

    /*Pesquisa*/
    $('#valor_pesquisa').on('keypress', function(e){
        if(e.keyCode == 13)
        {
            $('#pesquisar').click();
        }
    });

    $('#pesquisar').click(function(){

        $('#formPesquisa').find('input').each(function(){

            if($(this).attr('required') && $(this).val() == '')
            {
                exit;
            }

        });

        $('#listagem').html('<h2 class="h2 cinza">Pesquisando, aguarde...</h2>');

        $.post('renovacao/listagem.php', { mes: $('#mes').val(), ano: $('#ano').val(), id_motivo: $('#id_motivo').val(), reajuste: $('#reajuste').val(), numero_parcelas: $('#numero_parcelas').val(), aluno: $('#aluno').val() }, function(data){
            $('#listagem').html(data);

            $("#listagem .parcela").each(
                function() {
                    if ($(this).prop("checked")) {
                        $(this).prop("checked", false);
                    } else {
                        $(this).prop("checked", true);
                    }
                });
        });

    });
    /*Pesquisa*/

    /*Renovação*/
    $('#listagem').on('click', '#renovar', function(){

        var parcelas = '';

        $('#listagem .parcela:checked').each(function(){
            parcelas+=$(this).val()+'|';
        });

        $('#ms-salvando-dialog').click();

        var dados = 'acao=renovar&parcelas='+parcelas+'&reajuste='+$('#listagem #reajuste').val()+'&numero_parcelas='+$('#listagem #numero_parcelas').val()+'&mes='+$('#mes option:selected').text()+'&ano='+$('#ano').val();

        $.post('renovacao/acoes.php', { dados: dados }, function(data){

            if(data.status == 'ok'){

                $('#bt-salvou').click();
                $('#listagem').html('<h2 class="texto-centro">RENOVAÇÃO DE CONTRATO REALIZADA COM SUCESSO!</h2>');

            }
            else if(data.status == 'erro-permissao')
            {
                $('#bt-salvou').click();
                $('#msg-permissao-dialog').html(data.mensagem);
                $('#ms-permissao-modal').click();
            }

        }, 'json');

    });
    /*Renovação*/

    /*Selecionar Todos*/
    $('#listagem').on('click', '#selecionar-todos', function (){

        $('#listagem .parcela').each(function(){
                if ($('#listagem #selecionar-todos').prop( "checked")){
                    $(this).prop( "checked", true);
                } else {
                    $(this).prop("checked", false);
                }
            }
        );

    });

});
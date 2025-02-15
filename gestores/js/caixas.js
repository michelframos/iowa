$(function(){

    $('#formDados').submit(function(){return false});
    $('#formPesquisa').submit(function(){return false});

    $('#valor_lancamento, #valor_transferencia').maskMoney({prefix:'R$ ', allowNegative: true, thousands:'.', decimal:',', affixesStay: false});

    $('#msg-nao-exclusao').hide();
    $('#bt-caixas-fechados').click(function(){
        $('#content').load('caixas/caixas_fechados.php');
    });

    $('#bt-voltar').click(function(){
        $('#content').load('caixas/caixas.php');
    });

    /*Pesquisa*/
    $('#valor').on('keypress', function(e){
        if(e.keyCode == 13)
        {
            $('#pesquisar').click();
        }
    });

    $('#pesquisar').click(function(){

        $('#listagem').html('<h2 class="h2 cinza">Pesquisando, aguarde...</h2>');

        $.post('idiomas/listagem.php', { valor: $('#valor').val() }, function(data){
            $('#listagem').html(data);
        });

    });
    /*Pesquisa*/

    $('#criar').click(function(){

        $('#formDados').find('input').each(function(){

            if($(this).attr('required') && $(this).val() == '')
            {
                exit;
            }

        });

        var dados = $('#formDados').serialize()+'&id='+$(this).attr('registro')+'&acao=novo';

        $.post('caixas/acoes.php', { dados: dados }, function(data){

            if(data.status == 'erro')
            {
                $('#ms-dp-modal').click();
            }
            else if(data.status == 'erro-permissao')
            {
                $('#msg-permissao-dialog').html(data.mensagem);
                $('#ms-permissao-modal').click();
            }
            else if(data.status == 'erro-caixa-aberto')
            {
                $('#msg-permissao-dialog').html(data.mensagem);
                $('#ms-permissao-modal').click();
            }
            else if(data.status == 'ok')
            {
                $('#content').load('caixas/caixas.php');
                //$('#ms-ok-modal').click();
            }

        }, 'json');

    });

    $('.bt-adicionar-responsavel').click(function(){

        $('#bt-adicionar-responsaveis').attr('registro', $(this).attr('registro'));

    });

    $('#bt-adicionar-responsaveis').click(function(){

        var usuarios = '';

        $('.usuario:checked').each(function(){
            usuarios+=$(this).val()+'|';
        });


        var dados = 'id='+$(this).attr('registro')+'&usuarios='+usuarios+'&acao=adicionar-responsaveis';
        $.post('caixas/acoes.php', { dados: dados }, function(data){

            if(data.status == 'ok'){
                $('#content').load('caixas/caixas.php');
            }

        }, 'json');

    });

    /*---------------------------------------------------------------------------------------*/
    /*Ver Resumo e Todos os lançamentos*/

    $('#bt-ver-resumo-caixas').click(function(){

        $('#listagem').load('caixas/listagem.php');

    });

    $('#bt-ver-todos-lancamento').click(function(){

        $('#listagem').load('caixas/todos-lancamentos.php');

    });


    /*=======================================================================================*/
    /*TRANSFERENCIA*/

    $('#listagem').on('click', '.bt-excluir', function(){

        $('#bt-modal-excluir').attr('registro', $(this).attr('registro'));

    });


    $('.bt-tranferir').click(function(){

        $('#bt-tranferir').attr('registro', $(this).attr('registro'));

        var dados = 'id='+$(this).attr('registro')+'&acao=calcular-saldo-caixa';
        $.post('caixas/acoes.php', { dados: dados }, function(data){

            if(data.status == 'ok'){
                $('#saldo_caixa').val(data.saldo);
            }

        }, 'json');

    });


    $('#box-conta-bancaria').hide();

    /*Seleção do local de transferencia*/
    $('#transferir_para').change(function(){

        if($(this).val() == 'outro_caixa'){
            $('#box-caixa').show();
            $('#box-conta-bancaria').hide();
        }

        if($(this).val() == 'conta_bancaria'){
            $('#box-caixa').hide();
            $('#box-conta-bancaria').show();
        }

    });


    $('#bt-tranferir').click(function(){

        var dados = $('#formTransferencia').serialize()+'&id='+$(this).attr('registro')+'&acao=transferir';

        $.post('caixas/acoes.php', { dados: dados }, function(data) {

            if(data.status == 'ok'){
                $('#content').load('caixas/caixas.php');
            } else if (data.status == 'erro'){
                $('#ms-erro-transferencia-modal').click();
            }

        }, 'json');

    });

    /*========================================================================================*/
    /*Lançamentos no Caixa*/

    /*Para quem pode ver todos os caixas*/
    $('.bt-fazer-lancamento').click(function(){

        $('#bt-lancar').attr('registro', $(this).attr('registro'));

    });


    /*Para quem pode ver somente seu próprio caixa*/
    $('#bt-fazer-lancamento').click(function(){

        $('#bt-lancar').attr('registro', $(this).attr('registro'));
        $('#ms-lancamento').click();

    });


    $('#bt-lancar').click(function(){

        var dados = $('#formLancamento').serialize()+'&id='+$(this).attr('registro')+'&acao=lancamento';
        $.post('caixas/acoes.php', { dados: dados }, function(data){

            if(data.status == 'ok'){
                $('#content').load('caixas/caixas.php');
            }

        }, 'json');

    });

    /*========================================================================================*/
    /*Fechamento do Caixa*/

    /*Para quem pode ver todos os caixas*/
    $('.bt-fechr-caixa').click(function(){

        $('#bt-fechar-caixa').attr('registro', $(this).attr('registro'));

        var dados = 'id='+$(this).attr('registro')+'&acao=somar-totais';
        $.post('caixas/acoes.php', { dados: dados }, function(data){

            $('#totais-caixa').html(data);

        });

    });


    /*Para quem pode ver somente seu próprio caixa*/
    $('#bt-fechar-meu-caixa').click(function(){

        $('#bt-fechar-caixa').attr('registro', $(this).attr('registro'));

        var dados = 'id='+$(this).attr('registro')+'&acao=somar-totais';
        $.post('caixas/acoes.php', { dados: dados }, function(data){

            $('#totais-caixa').html(data);
            $('#ms-fechar-caixa').click();

        });

    });


    $('#bt-fechar-caixa').click(function(){

        var dados = 'id='+$(this).attr('registro')+'&acao=fechar-caixa';
        $.post('caixas/acoes.php', { dados: dados }, function(data){

            if(data.status == 'ok'){
                $('#content').load('caixas/caixas.php');
            }

        }, 'json');


    });

    /*========================================================================================*/
    /*Detalhes do Caixa*/

    $('.bt-detalhes').click(function(){

        $('#content').load('caixas/detalhes.php', {id: $(this).attr('registro')});

    });


    $('.bt-estornar').click(function(){

        $('#bt-estornar').attr('registro', $(this).attr('registro')).attr('conta', $(this).attr('conta'));

    });


    $('#bt-estornar').click(function(){

        $('#formEstorno').find('input').each(function(){

            if($(this).attr('required') && $(this).val() == '')
            {
                exit;
            }

        });

        $('#formEstorno').find('select').each(function(){

            if($(this).attr('required') && $(this).val() == '')
            {
                exit;
            }

        });

        var dados = 'id='+$(this).attr('registro')+'conta='+$(this).attr('conta')+'&id_forma_pagamento='+$('#id_forma_pagamento').val()+'&acao=estornar';
        $.post('caixas/acoes.php', {dados:dados}, function(data){

            if(data.status == 'ok')
            {
                $('#content').load('caixas/detalhes.php', {id: data.id});
            }

        }, 'json');

    });

    /*========================================================================================*/

    /*
    $('#bt-modal-excluir').click(function(){

        var dados = 'id='+$(this).attr('registro')+'&acao=excluir';

        $.post('idiomas/verifica-duplicidade.php', { dados: dados }, function(data){

            if(data.status == 'ok')
            {
                $('#content').load('idiomas/idiomas.php');
            }
            else if(data.status == 'erro-permissao')
            {
                $('#msg-permissao-dialog').html(data.mensagem);
                $('#ms-permissao-modal').click();
            }
            else if(data.status == 'erro')
            {
                $('#mensagem-modal').html(data.mensagem);
                $('#ms-nao-exclusao-modal').click();
            }

        }, 'json');

    });
    */

    /*
    $('#listagem').on('click', '.ativa-inativa', function(){

        var dados = 'id='+$(this).attr('registro')+'&acao=ativa-inativa';

        $.post('idiomas/verifica-duplicidade.php', { dados: dados }, function(data){
            if(data.status == 'erro-permissao')
            {
                $('#msg-permissao-dialog').html(data.mensagem);
                $('#ms-permissao-modal').click();
            }
        }, 'json');

    })
    */

});
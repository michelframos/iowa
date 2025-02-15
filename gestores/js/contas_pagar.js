$(function(){

    $('#formDados').submit(function(){return false});
    $('#formPesquisa').submit(function(){return false});

    $('#valor').maskMoney({prefix:'R$ ', allowNegative: true, thousands:'.', decimal:',', affixesStay: false});
    //$('#lista-unidades').find('.porcentagem_unidade').maskMoney({suffix:' %', allowNegative: true, thousands:'.', decimal:',', affixesStay: false});

    $('#msg-nao-exclusao').hide();

    $('#bt-novo').click(function(){

        $('#content').load('contas-pagar/nova-conta.php');

        /*
        var dados = 'acao=novo';
        $.post('contas-pagar/acoes.php', { dados: dados }, function(data){
            if(data.status == 'ok'){
                $('#content').load('contas-pagar/altera-conta.php', { id: data.id });
            }
        }, 'json');
        */
    });


    $('#bt-contas-pagas').click(function(){
        $('#content').load('contas-pagar/contas-pagas.php');
    });


    $('#bt-voltar').click(function(){
        $('#content').load('contas-pagar/contas-pagar.php');
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

        $.post('contas-pagar/listagem.php', { dados: $('#formPesquisa').serialize() }, function(data){
            $('#listagem').html(data);
        });

    });

    $('#pesquisar_pagas').click(function(){

        $('#listagem').html('<h2 class="h2 cinza">Pesquisando, aguarde...</h2>');

        $.post('contas-pagar/listagem-contas-pagas.php', { dados: $('#formPesquisaPagas').serialize() }, function(data){
            $('#listagem').html(data);
        });

    });
    /*Pesquisa*/


    /*-----------------------------------------------------------------------------*/
    /*Adicionar Unidade à Conta*/

    $('#bt-adicionar-unidade').click(function(){

        if($('#unidade').val() == ''){
            $('#ms-selecione-unidade').click();
            exit;
        }

        $('.porcentagem_unidade').each(function(){

            if($(this).attr('unidade') == $('#unidade').val())
            {
                $('#ms-unidade-ja-adicionada').click();
                exit;
            }

        });

        $('#lista-unidades').append(
            '<tr>' +
            '<td class="id_unidade_conta">'+$('#unidade option:selected').text()+'</td>' +
            '<td><input type="text" class="porcentagem_unidade" id="'+$('#unidade').val()+'" name="porcentagem['+$('#unidade').val()+']" value="" size="3" class="texto-centro"/></td>' +
            '<td><i class="material-icons md-dark pmd-sm delete-unidade cursor-pointer" registro="'+$('#unidade').val()+'">delete</i></td>'+
            '</tr>'
        );

    });


    /*------------------------------------------------------------------------------*/
    /*Excluir Unidade*/

    $('#lista-unidades').on('click', '.delete-unidade', function(){

        var tr = $(this).closest('tr');
        var dados = 'id_unidade='+$(this).attr('registro')+'&id='+$('#salvar').attr('registro')+'&acao=excluir-unidade';

        $.post('contas-pagar/acoes.php', {dados:dados}, function(){

            tr.remove();
            return false;

        });

    });

    /*---------------------------------------------------------------------------*/
    /*ALTERAÇÃO DE PARCELAS*/
    $('#alterar-contas').click(function () {

        var parcelas = '';

        $('#listagem .parcela:checked').each(function(){
            parcelas+=$(this).val()+'|';
        });

        $('#bt-modal-alterar').attr('parcelas', parcelas);

    });


    $('#bt-modal-alterar').click(function () {

        var dados = 'parcelas='+$(this).attr('parcelas')+'&'+$('#formAlterarConta').serialize()+'&acao=alterar-contas';
        $.post('contas-pagar/acoes.php', { dados: dados }, function (data) {
            if(data.status == 'ok'){
                $('#bt-fecha-modal-alterar').click();
                $('#formAlterarConta')[0].reset();
                $('#pesquisar').click();
            }
        }, 'json');

    });

    /*Alterar Vencimento*/
    $('#alterar-vencimento').click(function(){

        var parcelas = '';

        $('.parcela:checked').each(function(){
            parcelas+=$(this).val()+'|';
        });

        $('#bt-alterar-vencimento').attr('parcelas', parcelas);

    });


    $('#bt-alterar-vencimento').click(function(){

        var dados = 'acao=alterar-vencimento&novo-vencimento='+$('#novo-vencimento').val()+'&parcelas='+$(this).attr('parcelas');
        $.post('contas-pagar/acoes.php', { dados: dados }, function(data){

            if(data.status == 'ok'){
                $('#ms-vencimento-alterado-dialog').click();
                $('#pesquisar').click();
            }

        }, 'json');

    });

    /*------------------------------------------------------------------------------*/
    /*Excluir Conta a Pagar*/

    $('#listagem').on('click', '.bt-excluir', function(){

        $('#bt-modal-excluir').attr('registro', $(this).attr('registro'));

    }) ;

    $('#bt-modal-excluir').click(function(){

        var dados = 'id='+$(this).attr('registro')+'&acao=excluir-conta';
        $.post('contas-pagar/acoes.php', {dados:dados}, function(data){

            if(data.status == 'ok')
            {
                $('#content').load('contas-pagar/contas-pagar.php');
            }

        }, 'json');

    });

    $('#excluir-contas').click(function(){

        var parcelas = '';

        $('#listagem .parcela:checked').each(function(){
            parcelas+=$(this).val()+'|';
        });

        $('#bt-modal-excluir-conta').attr('parcelas', parcelas);

    });


    $('#bt-modal-excluir-conta').click(function(){

        /*
        var dados = 'id='+$(this).attr('registro')+'&acao=excluir-conta';
        $.post('contas-pagar/acoes.php', {dados:dados}, function(data){

            if(data.status == 'ok')
            {
                $('#content').load('contas-pagar/contas-pagar.php');
            }

        }, 'json');
        */


        var dados = 'id='+$(this).attr('registro')+'&acao=excluir-conta&parcelas='+$(this).attr('parcelas');
        $.post('contas-pagar/acoes.php', { dados: dados }, function(data){

            if(data.status == 'ok'){
                $('#bt-canclar-exclusao').click();
                $('#pesquisar').click();
                //$('#listagem-parcelas').empty('').load('contas-receber/listagem-parcelas.php', { nome_aluno: $('#nome_aluno').val(), id_turma: $('#id_turma').val(), id_idioma: $('#id_idioma').val(), id_empresa: $('#id_empresa').val(), data_inicial: $('#data_inicial').val(), data_final: $('#data_final').val()});
            }
            else if(data.status == 'erro-permissao')
            {
                $('#msg-permissao-dialog').html(data.mensagem);
                $('#ms-permissao-modal').click();
            }

        }, 'json');

        return false;

    });


    /*-------------------------------------------------------------------------------*/

    $('#salvar').click(function(){

        var tipo = $('#porcentagem-valor').val();
        var pega_valor = $('#valor').val();
        var valor = pega_valor.replace('.', '').replace(',', '.');

        /*Verificando se porcentagem é igual a 100%*/
        if(tipo == 'p')
        {
            var total = 0;
            $('.porcentagem_unidade').each(function(){

                total += parseInt($(this).val());

            });

            if(total != 100){
                $('#ms-erro-porcentagem').click();
                exit;
            }
        }

        /*Verificando se o total inserido é igual o valor da conta*/
        if(tipo == 'v')
        {
            var total = parseFloat(0);
            $('.porcentagem_unidade').each(function(){

                var pega_total = $(this).val().replace('.', '').replace(',', '.');
                total += parseFloat(pega_total);

            });

            console.log(total);

            if(total != valor){
                $('#ms-erro-valor').click();
                exit;
            }
        }


        $('#formDados').find('input').each(function(){

            if($(this).attr('required') && $(this).val() == '')
            {
                exit;
            }

        });

        $('#formDados').find('select').each(function(){

            if($(this).attr('required') && $(this).val() == '')
            {
                exit;
            }

        });

        $('#ms-salvando-dialog').click();

        var observacoes = $('#observacoes').val();
        var dados = $('#formDados').serialize()+'&id='+$(this).attr('registro')+'&observacoes='+observacoes+'&acao=salvar';

        $.post('contas-pagar/acoes.php', { dados: dados }, function(data){

            $('#bt-salvou').click();

            if(data.status == 'erro')
            {
                $('#ms-dp-modal').click();
            }
            else if(data.status == 'erro-permissao')
            {
                $('#msg-permissao-dialog').html(data.mensagem);
                $('#ms-permissao-modal').click();
            }
            else if(data.status == 'erro-unidade')
            {
                $('#ms-erro-unidade').click();
            }
            else if(data.status == 'ok')
            {
                $('#ms-ok-modal').click();
            }

        }, 'json');

    });


    $('#alterar').click(function(){

        $('#formDados').find('input').each(function(){

            if($(this).attr('required') && $(this).val() == '')
            {
                exit;
            }

        });

        $('#formDados').find('select').each(function(){

            if($(this).attr('required') && $(this).val() == '')
            {
                exit;
            }

        });

        $('#ms-salvando-dialog').click();

        var dados = $('#formDados').serialize()+'&id='+$(this).attr('registro')+'&acao=alterar';

        $.post('contas-pagar/acoes.php', { dados: dados }, function(data){

            $('#bt-salvou').click();

            if(data.status == 'erro')
            {
                $('#ms-dp-modal').click();
            }
            else if(data.status == 'erro-permissao')
            {
                $('#msg-permissao-dialog').html(data.mensagem);
                $('#ms-permissao-modal').click();
            }
            else if(data.status == 'erro-unidade')
            {
                $('#ms-erro-unidade').click();
            }
            else if(data.status == 'ok')
            {
                $('#ms-ok-modal').click();
            }

        }, 'json');

    });


    $('#listagem').on('click', '.bt-alterar', function(){
        $('#content').empty('').load('contas-pagar/altera-conta.php', {id: $(this).attr('registro')});
    });


    /*Cancelando Conta a Pagar*/
    $('#listagem').on('click', '.bt-cancelar', function(){

        $('#bt-modal-cancelar').attr('registro', $(this).attr('registro'));

    });

    $('#bt-modal-cancelar').click(function(){

        $('#formCancelarConta').find('textarea').each(function(){

            if($(this).attr('required') && $(this).val() == '')
            {
                exit;
            }

        });

        var dados = 'id='+$(this).attr('registro')+'&acao=cancelar';
        $.post('contas-pagar/acoes.php', {dados: dados}, function(data){
            if(data.status == 'ok') {
                $.post('contas-pagar/listagem.php', {dados: $('#formPesquisa').serialize()}, function (data) {
                    $('#listagem').html(data);
                });
            }
        }, 'json')

    });

    /*-----------------------------------------------------------------*/

    $('#listagem').on('click', '.bt-excluir', function(){


        $('#bt-modal-excluir').attr('registro', $(this).attr('registro'));

    });


    $('#bt-modal-excluir').click(function(){

        var dados = 'id='+$(this).attr('registro')+'&acao=excluir';

        $.post('contas-pagar/acoes.php', { dados: dados }, function(data){

            if(data.status == 'ok')
            {
                $('#content').load('contas-pagar/contas-pagas.php');
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

    /*------------------------------------------------------------------------*/
    /*adicionando formas de pagamento*/
    $('#bt-adicionar-forma-pagamento').click(function (){

        if($('#id_forma_pagamento').val() != '')
        {
            $('#nova-forma-recebimento').append(
                '<tr>' +
                '<td>'+$('#id_forma_pagamento option:selected').text()+'</td>' +
                '<td><input type="text" class="valor_receber" id="'+$('#id_forma_pagamento').val()+'" name="forma_pagamento['+$('#id_forma_pagamento').val()+']" value="" size="6" class="texto-centro"/></td>' +
                '<td><i class="material-icons md-dark pmd-sm delete-forma-pagamento cursor-pointer" registro="'+$('#id_forma_pagamento').val()+'">delete</i></td>'+
                '</tr>'
            );

        }

    });


    /*excluindo forma de pagamento*/
    $('body').on('click', '.delete-forma-pagamento', function (){

        var tr = $(this).closest('tr');
        tr.remove();
        return false;
    });


    /*Quitar*/
    $('#box-conta-bancaria').hide();


    $('#listagem').on('click', '.bt-quitar', function(){

        $('#bt-quitar').attr('registro', $(this).attr('registro'));

        var dados = 'id='+$(this).attr('registro')+'&acao=dados-conta';
        $.post('contas-pagar/acoes.php', { dados:dados }, function(data){

            $('#dados-conta').html(data);
            $('#ms-quitar-modal').click();

        });

    });


    $('#bt-quitar').click(function(){

        $('#formQuitar').find('input').each(function(){

            if($(this).attr('required') && $(this).val() == '')
            {
                exit;
            }

        });

        $('#formQuitar').find('select').each(function(){

            if($(this).attr('required') && $(this).val() == '')
            {
                exit;
            }

        });


        var dados = $('#formQuitar').serialize()+'&id='+$(this).attr('registro')+'&acao=quitar&'+$('#formFormasPagamento').serialize();
        $.post('contas-pagar/acoes.php', { dados:dados }, function(data){

            if(data.status == 'ok')
            {
                $('#bt-cancelar-quitar').click();
                $('#pesquisar').click();
                $('#formQuitar')[0].reset();
                $('#nova-forma-recebimento').find('.delete-forma-pagamento').each(function (){
                    var tr = $(this).closest('tr');
                    tr.remove();
                });

                //$('#content').load('contas-pagar/contas-pagar.php');
            }
            else if(data.status == 'erro')
            {

            }
            else if(data.status == 'erro-permissao')
            {
                $('#msg-permissao-dialog').html(data.mensagem);
                $('#ms-permissao-modal').click();
            }
            else if(data.status == 'erro-valor')
            {
                $('#mensagem-erro-valor').html(data.mensagem);
                $('#ms-erro-valor-dialog').click();
            }


        }, 'json');

    });

    /*SOMANDO PARCELAS SELECIONADA*/
    $('#quitar-selecionadas').click(function(){

        var parcelas = '';

        $('.parcela:checked').each(function(){
            parcelas+=$(this).val()+'|';
        });

        $('#bt-modal-quitar-selecionadas').attr('parcelas', parcelas);

        var dados = 'id='+$(this).attr('aluno')+'&parcelas='+parcelas+'&acao=calcular-parcelas';
        $.post('contas-pagar/acoes.php', { dados: dados }, function(data){

            if(data.status == 'ok'){
                $('#valor_total_parcelas').html(data.total);
                $('#bt-modal-quitar-selecionadas').attr('total_parcelas', data.total);
            }
            else if(data.status == 'erro-permissao')
            {
                $('#msg-permissao-dialog').html(data.mensagem);
                $('#ms-permissao-modal').click();
            }

        }, 'json');

    });


    /*QUITANDO PARCELAS*/
    $('#bt-modal-quitar-selecionadas').click(function(){

        $('#formQuitarSelecionadas').find('input').each(function(){

            if($(this).attr('required') && $(this).val() == '')
            {
                exit;
            }

        });

        $('#formQuitarSelecionadas').find('select').each(function(){

            if($(this).attr('required') && $(this).val() == '')
            {
                exit;
            }

        });

        if($('#data_pagamento_selecionadas').val() != '' && $('#id_forma_pagamento_selecionadas').val() != '') {
            var dados = 'parcelas=' + $(this).attr('parcelas') + '&total_parcelas='+$(this).attr('total_parcelas')+'&data_pagamento_selecionadas=' + $('#data_pagamento_selecionadas').val() + '&id_forma_pagamento_selecionadas=' + $('#id_forma_pagamento_selecionadas').val() + '&acao=quitar-parcelas&'+$('#formQuitarSelecionadas').serialize();
            $.post('contas-pagar/acoes.php', {dados: dados}, function (data) {

                if (data.status == 'ok') {

                    $('#formQuitarSelecionadas')[0].reset();
                    $('#nova-forma-recebimento-selecionadas').find('.delete-forma-pagamento').each(function (){
                        var tr = $(this).closest('tr');
                        tr.remove();
                    });

                    /*
                    $('#listagem-parcelas').empty('').load('contas-pagar/listagem-parcelas.php', { data_inicial: $('#data_inicial').val(), data_final: $('#data_final').val(), id_natureza: $('#id_natureza').val(), id_fornecedor: $('#id_fornecedor').val(), id_unidade: $('#id_unidade').val(), id_categoria: $('#id_categoria').val() });
                    $('#bt-cancelar-quitar-parcelas').click();
                    */
                    $('#bt-cancelar-quitar-parcelas-selecionadas').click();
                    $('#pesquisar').click();
                }
                else if (data.status == 'erro-caixa'){
                    $('#ms-erro-caixa-modal').click();
                }
                else if(data.status == 'erro-permissao')
                {
                    $('#msg-permissao-dialog').html(data.mensagem);
                    $('#ms-permissao-modal').click();
                }
                else if(data.status == 'erro-vencimento')
                {
                    $('#bt-cancelar-quitar-parcelas').click();
                    $('#mensagem-modal-vencimento').html(data.mensagem);
                    $('#ms-erro-vencimento-dialog').click();
                }
                else if(data.status == 'erro-valor')
                {
                    $('#mensagem-erro-valor').html(data.mensagem);
                    $('#ms-erro-valor-dialog').click();
                }

            }, 'json');

            return false;

        }

    });


    /*adicionando formas de pagamento*/
    $('#bt-adicionar-forma-pagamento-selecionadas').click(function (){

        if($('#id_forma_pagamento_selecionadas').val() != '')
        {

            $('body.valor_receber').maskMoney({prefix:'R$ ', allowNegative: true, thousands:'.', decimal:',', affixesStay: false});
            $('#nova-forma-recebimento-selecionadas').append(
                '<tr>' +
                '<td>'+$('#id_forma_pagamento_selecionadas option:selected').text()+'</td>' +
                '<td><input type="text" class="valor_receber" id="'+$('#id_forma_pagamento_selecionadas').val()+'" name="forma_pagamento_selecionadas['+$('#id_forma_pagamento_selecionadas').val()+']" value="" size="6" class="texto-centro"/></td>' +
                '<td><i class="material-icons md-dark pmd-sm delete-forma-pagamento cursor-pointer" registro="'+$('#id_forma_pagamento_selecionadas').val()+'">delete</i></td>'+
                '</tr>'
            );

        }

    });


    /*excluindo forma de pagamento*/
    $('body').on('click', '.delete-forma-pagamento', function (){

        var tr = $(this).closest('tr');
        tr.remove();
        return false;
    });


    /*Seleção do local de transferencia*/
    $('#quitar_de').change(function(){

        if($(this).val() == 'caixa'){
            $('#box-caixa').show();
            $('#box-conta-bancaria').hide();
        }

        if($(this).val() == 'conta_bancaria'){
            $('#box-caixa').hide();
            $('#box-conta-bancaria').show();
        }

    });

    $('#quitar_selecionadas_de').change(function(){

        if($(this).val() == 'caixa'){
            $('#box-caixa-selecionadas').show();
            $('#box-conta-bancaria-selecionadas').hide();
        }

        if($(this).val() == 'conta_bancaria'){
            $('#box-caixa-selecionadas').hide();
            $('#box-conta-bancaria-selecionadas').show();
        }

    });


    /*-------------------------------------------------------------------------*/
    /*Remover Cancelamento*/
    $('#listagem').on('click', '.bt-remover-cancelamento', function(){

        var dados = 'id='+$(this).attr('registro')+'&acao=remover-cancelamento';
        $.post('contas-pagar/acoes.php', {dados:dados}, function(data){

            if(data.status == 'ok')
            {
                $('#content').load('contas-pagar/contas-pagar.php');
            }
            else if(data.status == 'erro-permissao')
            {
                $('#msg-permissao-dialog').html(data.mensagem);
                $('#ms-permissao-modal').click();
            }

        }, 'json');

    });


    $('#listagem').on('click', '.ativa-inativa', function(){

        var dados = 'id='+$(this).attr('registro')+'&acao=ativa-inativa';

        $.post('contas-pagar/acoes.php', { dados: dados }, function(data){
            if(data.status == 'erro-permissao')
            {
                $('#msg-permissao-dialog').html(data.mensagem);
                $('#ms-permissao-modal').click();
            }
        }, 'json');

    });


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

    /*pintando linha selecionada*/
    $('#listagem').on('click', '.parcela', function(){

        var linha = $(this).closest('tr');

        if ($(this).is(':checked') == true){
            linha.addClass('linha-ativa');
        }
        else if($(this).is(':checked') == false){
            linha.removeClass('linha-ativa');
        }

    });



});
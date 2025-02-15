$(function(){

    $("#data_inicial, #data_final, #data_pagamento, #novo-vencimento").datetimepicker({
        format: "DD/MM/YYYY"
    });

    /*----------------------------------------------------------------------------------------------------------------*/
    /*Parcelas*/

    /*Pesquisa parcelas*/
    $('#nome_aluno').on('keypress', function(e){
        if(e.keyCode == 13)
        {
            $('#pesquisar-parcelas').click();
        }
    });


    $('#pesquisar-parcelas').click(function(){

        $('#listagem-parcelas').html('<h2 class="h2 cinza">Pesquisando, aguarde...</h2>');

        $.post('contas-receber/listagem-parcelas.php', { nome_aluno: $('#nome_aluno').val(), id_turma: $('#id_turma').val(), id_idioma: $('#id_idioma').val(), id_empresa: $('#id_empresa').val(), sacado: $('#sacado').val(), data_inicial: $('#data_inicial').val(), data_final: $('#data_final').val() }, function(data){
            console.log(data);
            $('#listagem-parcelas').html(data);
        });

    });
    /*Pesquisa parcelas*/

    /*VERIFICANDO PARCELAS SELECIONADAS*/
    $('#alterar-parcela').click(function(){

        var parcelas = '';

        $('.parcela:checked').each(function(){
            parcelas+=$(this).val()+'|';
        });

        //alert(parcelas);

        $('#bt-altera-parcelas').attr('parcelas', parcelas);
        $('#ms-altera-parcela-modal').click();

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
        $.post('alunos/acoes.php', { dados: dados }, function(data){

            if(data.status == 'ok'){
                $('#ms-vencimento-alterado-dialog').click();
            }

        }, 'json');

    });


    /*ALTERANDO PARCELAS*/
    $('#bt-altera-parcelas').click(function(){

        if($('#observacao').val() != '')
        {
            var dados = $('#formAlteraParcela').serialize() + '&id=' + $(this).attr('aluno') + '&parcelas=' + $(this).attr('parcelas') + '&acao=alterar-parcelas';

            $.post('alunos/acoes.php', {dados: dados}, function (data) {

                if (data.status == 'ok') {
                    //$('#content').load('alunos/altera-aluno.php', {id: $('#salvar').attr('registro')});
                    //$('#financeiro').empty('').load('alunos/altera-aluno.php #financeiro', {id: $('#salvar').attr('registro')});
                    //$('#content-observacoes').empty('').load('alunos/lista-observacoes.php', { id: $('#salvar').attr('registro') });
                    $('#listagem-parcelas').empty('').load('contas-receber/listagem-parcelas.php', { nome_aluno: $('#nome_aluno').val(),id_turma: $('#id_turma').val(), id_idioma: $('#id_idioma').val(), id_empresa: $('#id_empresa').val(), data_inicial: $('#data_inicial').val(), data_final: $('#data_final').val()});
                    $('#bt-cancela-altera-parcelas').click();
                    $('#formAlteraParcela')[0].reset();
                }
                else if(data.status == 'erro-permissao')
                {
                    $('#msg-permissao-dialog').html(data.mensagem);
                    $('#ms-permissao-modal').click();
                }

            }, 'json');

            return false;
        }

    });


    /*ZERANDO JUROS, MULTA, ACRESCIMOS E DESCONTOS*/
    $('#zerar-valores').click(function(){

        var parcelas = '';

        $('.parcela:checked').each(function(){
            parcelas+=$(this).val()+'|';
        });

        var dados = 'id='+$(this).attr('aluno')+'&parcelas='+parcelas+'&acao=zerar-valores';
        $.post('alunos/acoes.php', { dados: dados }, function(data){

            if(data.status == 'ok'){
                //$('#content').load('alunos/altera-aluno.php', {id: $('#salvar').attr('registro')});
                //$('#financeiro').empty('').load('alunos/altera-aluno.php #financeiro', {id: $('#salvar').attr('registro')});
                $('#listagem-parcelas').empty('').load('contas-receber/listagem-parcelas.php', { nome_aluno: $('#nome_aluno').val(), id_turma: $('#id_turma').val(), id_idioma: $('#id_idioma').val(), id_empresa: $('#id_empresa').val(), data_inicial: $('#data_inicial').val(), data_final: $('#data_final').val()});
            }
            else if(data.status == 'erro-permissao')
            {
                $('#msg-permissao-dialog').html(data.mensagem);
                $('#ms-permissao-modal').click();
            }

        }, 'json');

        return false;


    });


    /*adicionando formas de pagamento*/
    $('#bt-adicionar-forma-pagamento').click(function (){

        if($('#id_forma_pagamento').val() != '')
        {

            $('body.valor_receber').maskMoney({prefix:'R$ ', allowNegative: true, thousands:'.', decimal:',', affixesStay: false});
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


    /*SOMANDO PARCELAS SELECIONADA*/
    $('#quitar-parcela').click(function(){

        var parcelas = '';

        $('.parcela:checked').each(function(){
            parcelas+=$(this).val()+'|';
        });

        $('#bt-modal-quitar-parcelas').attr('parcelas', parcelas);

        var dados = 'id='+$(this).attr('aluno')+'&parcelas='+parcelas+'&acao=calcular-parcelas';
        $.post('alunos/acoes.php', { dados: dados }, function(data){

            if(data.status == 'ok'){
                $('#valor_total_parcelas').html(data.total);
                $('#bt-modal-quitar-parcelas').attr('total_parcelas', data.total);
            }
            else if(data.status == 'erro-permissao')
            {
                $('#msg-permissao-dialog').html(data.mensagem);
                $('#ms-permissao-modal').click();
            }

        }, 'json');

    });


    /*QUITANDO PARCELAS*/
    $('#bt-modal-quitar-parcelas').click(function(){

        if($('#data_pagamento').val() != '' && $('id_forma_pagamento').val() != '') {
            var dados = 'id=' + $(this).attr('aluno') + '&parcelas=' + $(this).attr('parcelas') + '&total_parcelas='+$(this).attr('total_parcelas')+'&data_pagamento=' + $('#data_pagamento').val() + '&id_forma_pagamento=' + $('#id_forma_pagamento').val() + '&acao=quitar-parcelas&'+$('#formQuitar').serialize();
            $.post('alunos/acoes.php', {dados: dados}, function (data) {

                if (data.status == 'ok') {
                    /*Recibo*/
                    $('#link_recibo').attr('href', data.link_recibo);
                    $('#ms-imprimir-recibo-dialog').click();

                    $('#formQuitar')[0].reset();
                    $('#nova-forma-recebimento').find('.delete-forma-pagamento').each(function (){
                        var tr = $(this).closest('tr');
                        tr.remove();
                    });

                    $('#listagem-parcelas').empty('').load('contas-receber/listagem-parcelas.php', { nome_aluno: $('#nome_aluno').val(), id_turma: $('#id_turma').val(), id_idioma: $('#id_idioma').val(), id_empresa: $('#id_empresa').val(), data_inicial: $('#data_inicial').val(), data_final: $('#data_final').val()});
                    $('#bt-cancelar-quitar-parcelas').click();
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



    /*-----------------------------------------------------------------------*/
    /*RENEGOCIANDO PARCELAS*/
    $('#listagem-parcelas').on('click', '.bt-renegociar', function(){

        var parcela = $(this).attr('parcela');
        $('#box-renegociar').load('contas-receber/renegociar.php', {parcela: parcela});
        $('#tab-renegociar').click();

    });

    $('#voltar').click(function(){
        $('#tab-selecionar-parcelas').click();
    });


    $('#renegociar').on('click', '#remover_acrescimos', function(){

        //$('#renegociar #valor_parcela').val($('#renegociar #valor_original').val());
        $('#renegociar #valor_parcela').val($('#renegociar #total').val());
        $('#renegociar #juros').val('0');
        $('#renegociar #multa').val('0');
        $('#renegociar #acrescimo').val('0');
        $('#renegociar #importar_acrescimos').val('n');

    });

    $('#renegociar').on('click', '#gerar', function(){

        $('#renegociar #formNovosDados').find('input').each(function(){

            if($(this).attr('required') && $(this).val() == '')
            {
                exit;
            }

        });

        var dados = $('#renegociar #formNovosDados').serialize()+'&acao=gerar';
        $.post('contas-receber/acoes.php', { dados: dados }, function (data){

            if(data.status == 'ok'){
                $('#content').empty('').load('contas-receber/contas-receber.php');
            }
            else if(data.status == 'erro-permissao')
            {
                $('#msg-permissao-dialog').html(data.mensagem);
                $('#ms-permissao-modal').click();
            }

        }, 'json');


    });
    /*-----------------------------------------------------------------------*/



    /*EXCLUIR PARCELA*/
    /*
    $('#listagem-parcelas').on('click', '.bt-excluir-parcela', function(){

        $('#bt-modal-excluir-parcela').attr('registro', $(this).attr('registro'));

    });
    */

    $('#excluir-parcelas').click(function(){

        var parcelas = '';

        $('#listagem-parcelas .parcela:checked').each(function(){
            parcelas+=$(this).val()+'|';
        });

        $('#bt-modal-excluir-parcela').attr('parcelas', parcelas);

    });

    $('#bt-modal-excluir-parcela').click(function(){

        var dados = 'id='+$(this).attr('aluno')+'&parcelas='+$(this).attr('parcelas')+'&acao=excluir-parcela';
        $.post('alunos/acoes.php', { dados: dados }, function(data){

            if(data.status == 'ok'){
                $('#bt-canclar-exclusao').click();
                $('#pesquisar-parcelas').click();
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


    /*REMOVER PAGAMENTO*/
    $('#listagem-parcelas').on('click', '.bt-remover-pagamento-parcela', function(){

        var dados = 'id='+$(this).attr('aluno')+'&parcela='+$(this).attr('registro')+'&acao=remover-pagamento';
        $.post('alunos/acoes.php', { dados: dados }, function(data){

            if(data.status == 'ok'){
                $('#pesquisar-parcelas').click();
                //$('#listagem-parcelas').empty('').load('contas-receber/listagem-parcelas.php', { nome_aluno: $('#nome_aluno').val(), id_turma: $('#id_turma').val(), id_idioma: $('#id_idioma').val(), id_empresa: $('#id_empresa').val(), data_inicial: $('#data_inicial').val(), data_final: $('#data_final').val()});
            }

        }, 'json');

        return false;

    });


    /*ALTERAR PARCELA*/
    $('#listagem-parcelas').on('click', '.bt-alterar-parcela', function(){

        $('#content').empty('').load('contas-receber/altera-parcela.php', {id: $(this).attr('registro'), id_parcela: $(this).attr('parcela')});

    });


    /*ADICIONAR PARCELA*/
    $('#adicionar-parcela').click(function(){

        $('#content').empty('').load('contas-receber/adicionar-parcela.php');

    });


    /*CANCELAR PARCELA*/
    $('#listagem-parcelas').on('click', '.bt-cancelar-parcela', function(){

        //alert($(this).attr('parcela'));

        $('#bt-modal-cancelar-parcela').attr('registro', $(this).attr('parcela')).attr('parcelas', $(this).attr('parcela'));
        $('#observacao-cancelamento').val('');

    });


    $('#cancelar-parcelas').click(function(){

        var parcelas = '';

        $('#listagem-parcelas .parcela:checked').each(function(){
            parcelas+=$(this).val()+'|';
        });

        $('#bt-modal-cancelar-parcela').attr('parcelas', parcelas);
        $('#observacao-cancelamento').val('');

    });


    $('#bt-modal-cancelar-parcela').click(function(){

        if($('#observacao-cancelamento').val() != '')
        {
            var dados = 'id='+$(this).attr('aluno')+'&parcelas='+$(this).attr('parcelas')+'&observacao='+$('#observacao-cancelamento').val()+'&acao=cancelar-parcela';
            $.post('alunos/acoes.php', { dados: dados }, function(data){

                if(data.status == 'ok')
                {
                    $('#listagem-parcelas').empty('').load('contas-receber/listagem-parcelas.php', { nome_aluno: $('#nome_aluno').val(), id_turma: $('#id_turma').val(), id_idioma: $('#id_idioma').val(), id_empresa: $('#id_empresa').val(), data_inicial: $('#data_inicial').val(), data_final: $('#data_final').val()});
                    //$('#content-observacoes').empty('').load('alunos/lista-observacoes.php', { id: $('#salvar').attr('registro') });
                    $('#bt-fecha-cancelar-parcela').click();

                }
                else if(data.status == 'erro-permissao')
                {
                    $('#msg-permissao-dialog').html(data.mensagem);
                    $('#ms-permissao-modal').click();
                }

            }, 'json');

            return false;
        }

    });


    /*---------------------------------------------------------------------------------------------------------------*/
    /*---------------------------------------------------------------------------------------------------------------*/
    /*Geração de recibos*/
    $('#gerar-recibo').click(function (){

        var parcelas = '';

        $('#listagem-parcelas .parcela_recibo:checked').each(function(){
            parcelas+=$(this).val()+'|';
        });

        $('#bt-gerar-recibo').attr('parcelas', parcelas);

    });


    $('#bt-gerar-recibo').click(function(){

        var dados = 'parcelas='+$(this).attr('parcelas')+'&acao=gerar-recibo';
        $.post('contas-receber/gerar-recibo.php', { dados: dados }, function(data){

            if(data.status == 'erro-sacado')
            {
                $('#ms-erro-recibo-dialog').click();
            }
            else if(data.status == 'ok')
            {
                $('#link_recibo').attr('href', data.link_recibo);
                $('#ms-imprimir-recibo-dialog').click();
            }

        }, 'json');

    });


    /*PAUSAR PARCELAS*/
    /******************************************************************************************************************/
    /******************************************************************************************************************/
    $('#pausar-parcelas').click(function(){

        var parcelas = '';

        $('#listagem-parcelas .parcela:checked').each(function(){
            parcelas+=$(this).val()+'|';
        });

        $('#bt-pausar-parcelas').attr('parcelas', parcelas);

    });

    $('#bt-pausar-parcelas').click(function(){

        var dados = 'id='+$(this).attr('aluno')+'&parcelas='+$(this).attr('parcelas')+'&acao=pausar-parcelas';
        $.post('alunos/acoes.php', { dados: dados }, function(data){

            if(data.status == 'ok'){
                $('#bt-cancelar-pausar-parcelas').click();
                $('#pesquisar-parcelas').click();
            }
            else if(data.status == 'erro-permissao')
            {
                $('#msg-permissao-dialog').html(data.mensagem);
                $('#ms-permissao-modal').click();
            }

        }, 'json');

        return false;

    });


    /*Selecionar Todos*/
    $('#listagem-parcelas').on('click', '#selecionar-todos', function (){

        $('#listagem-parcelas .parcela').each(function(){
                if ($('#listagem-parcelas #selecionar-todos').prop( "checked")){
                    $(this).prop( "checked", true);
                } else {
                    $(this).prop("checked", false);
                }
            }
        );

    });


    /*pintando linha selecionada*/
    $('#listagem-parcelas').on('click', '.parcela', function(){

        var linha = $(this).closest('tr');

        if ($(this).is(':checked') == true){
            linha.addClass('linha-ativa');
        }
        else if($(this).is(':checked') == false){
            linha.removeClass('linha-ativa');
        }

    });


});
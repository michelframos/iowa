$(function(){

    $('#formDados').submit(function(){return false});
    $('#formPesquisa').submit(function(){return false});

    $('#msg-nao-exclusao').hide();

    $('#cnpj').mask('00.000.000/0000-00', {reverse: true});
    $('#cep').mask('00.000-000', {reverse: true});

    $('#valor_hora_aula_help').maskMoney({prefix:'R$ ', allowNegative: true, thousands:'.', decimal:',', affixesStay: false});

    var options =  {
        onKeyPress: function(telefone, e, field, options) {
            var masks = ['(00)0000-00000', '(00)00000-0000'];
            var mask = (telefone.length>13) ? masks[1] : masks[0];
            $('#telefone1, #telefone2').mask(mask, options);
        }};

    $('#telefone1, #telefone2').mask('(00)0000-00000', options);


    /*---------------------------------------------------------------------------------------------------------------*/
    /*---------------------------------------------------------------------------------------------------------------*/
    /*BUSCA CEP*/

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


    $('#bt-novo').click(function(){

        var dados = 'acao=novo';
        $.post('empresas/acoes.php', { dados: dados }, function(data){
            if(data.status == 'ok')
            {
                $('#content').load('empresas/altera-empresa.php', {id: data.id});
            }
            else if(data.status == 'erro-permissao')
            {
                $('#msg-permissao-dialog').html(data.mensagem);
                $('#ms-permissao-modal').click();
            }
        }, 'json');

    });


    /*Pesquisa*/
    $('#valor_pesquisa').on('keypress', function(e){
        if(e.keyCode == 13)
        {
            $('#pesquisar').click();
        }
    });

    $('#pesquisar').click(function(){

        $('#listagem').html('<h2 class="h2 cinza">Pesquisando, aguarde...</h2>');

        $.post('empresas/listagem.php', { valor_pesquisa: $('#valor_pesquisa').val(), campo: $('#campo').val() }, function(data){
            $('#listagem').html(data);
        });

    });
    /*Pesquisa*/


    $('#voltar').click(function(){
        $('#content').load('empresas/empresas.php');
    });



    $('#listagem').on('click', '.bt-altera', function(){
        $('#content').load('empresas/altera-empresa.php', {id: $(this).attr('registro')});
    });



    $('#salvar').click(function(){

        if($('#senha').attr('required') && $('#senha').val() == ''){

            if($('#senha').val() == '' || $('#confirma_senha').val() == '')
            {
                $('#ms-senha-modal').click();
            }
            exit;

        }


        if($('#senha').val() != $('#confirma_senha').val())
        {
            $('#ms-confirma-senha-modal').click();
            exit;
        }

        if($('#cnpj').val() != '')
        {
            if(!validaCnpj($('#cnpj').val())){
                $('#ms-cnpj-invalido-modal').click();
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

        var dados = $('#formDados').serialize()+'&id='+$(this).attr('registro')+'&acao=salvar';

        $.post('empresas/acoes.php', { dados: dados }, function(data){

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
            else if(data.status == 'ok')
            {
                $('#ms-ok-modal').click();
            }

        }, 'json');

    });




    $('#listagem').on('click', '.bt-excluir', function(){

        $('#bt-modal-excluir').attr('registro', $(this).attr('registro'));

    });




    $('#bt-modal-excluir').click(function(){

        var dados = 'id='+$(this).attr('registro')+'&acao=excluir';

        $.post('empresas/acoes.php', { dados: dados }, function(data){

            if(data.status == 'ok')
            {
                $('#content').load('empresas/empresas.php');
            }
            else if(data.status == 'erro-permissao')
            {
                $('#msg-permissao-dialog').html(data.mensagem);
                $('#ms-permissao-modal').click();
            }
            else if(data.status == 'erro')
            {
                //$('#msg-nao-exclusao').show();
                $('#mensagem-modal').html(data.mensagem);
                $('#ms-nao-exclusao-modal').click();
            }

        }, 'json');

    });




    $('#listagem').on('click', '.ativa-inativa', function(){

        var dados = 'id='+$(this).attr('registro')+'&acao=ativa-inativa';

        $.post('empresas/acoes.php', { dados: dados }, function(data){
            if(data.status == 'erro-permissao')
            {
                $('#msg-permissao-dialog').html(data.mensagem);
                $('#ms-permissao-modal').click();
            }
            else if(data.status == 'erro-permissao')
            {
                $('#msg-permissao-dialog').html(data.mensagem);
                $('#ms-permissao-modal').click();
            }
        }, 'json');

    });


    /*VALIDAÇÕES*/
    /*Validação de CNPJ*/
    function validaCnpj(str){
        str = str.replace('.','');
        str = str.replace('.','');
        str = str.replace('.','');
        str = str.replace('-','');
        str = str.replace('/','');
        cnpj = str;
        var numeros, digitos, soma, i, resultado, pos, tamanho, digitos_iguais;
        digitos_iguais = 1;
        if (cnpj.length < 14 && cnpj.length < 15)
            return false;
        for (i = 0; i < cnpj.length - 1; i++)
            if (cnpj.charAt(i) != cnpj.charAt(i + 1))
            {
                digitos_iguais = 0;
                break;
            }
        if (!digitos_iguais)
        {
            tamanho = cnpj.length - 2;
            numeros = cnpj.substring(0,tamanho);
            digitos = cnpj.substring(tamanho);
            soma = 0;
            pos = tamanho - 7;
            for (i = tamanho; i >= 1; i--)
            {
                soma += numeros.charAt(tamanho - i) * pos--;
                if (pos < 2)
                    pos = 9;
            }
            resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
            if (resultado != digitos.charAt(0))
                return false;
            tamanho = tamanho + 1;
            numeros = cnpj.substring(0,tamanho);
            soma = 0;
            pos = tamanho - 7;
            for (i = tamanho; i >= 1; i--)
            {
                soma += numeros.charAt(tamanho - i) * pos--;
                if (pos < 2)
                    pos = 9;
            }
            resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
            if (resultado != digitos.charAt(1))
                return false;
            return true;
        }
        else
            return false;
    }

    /*PARCELAS*/
    /*VERIFICANDO PARCELAS SELECIONADAS*/
    $('#financeiro').on('click', '#alterar-parcela', function(){

        var parcelas = '';

        $('.parcela:checked').each(function(){
            parcelas+=$(this).val()+'|';
        });

        //alert(parcelas);

        $('#bt-altera-parcelas').attr('parcelas', parcelas);
        $('#ms-altera-parcela-modal').click();

    });


    /*ALTERANDO PARCELAS*/
    $('#bt-altera-parcelas').click(function(){

        if($('#observacao').val() != '')
        {
            var dados = $('#formAlteraParcela').serialize() + '&id=' + $('#salvar').attr('registro') + '&parcelas=' + $(this).attr('parcelas') + '&acao=alterar-parcelas';

            $.post('empresas/acoes.php', {dados: dados}, function (data) {

                if (data.status == 'ok') {
                    //$('#content').load('alunos/altera-aluno.php', {id: $('#salvar').attr('registro')});
                    $('#financeiro').load('empresas/altera-empresa.php #financeiro', {id: $('#salvar').attr('registro')});
                    $('#bt-cancela-altera-parcelas').click();
                    $('#formAlteraParcela')[0].reset();
                }

            }, 'json');
        }

    });


    /*ZERANDO JUROS, MULTA, ACRESCIMOS E DESCONTOS*/
    $('#financeiro').on('click', '#zerar-valores', function(){

        var parcelas = '';

        $('.parcela:checked').each(function(){
            parcelas+=$(this).val()+'|';
        });

        var dados = 'id='+$('#salvar').attr('registro')+'&parcelas='+parcelas+'&acao=zerar-valores';
        $.post('empresas/acoes.php', { dados: dados }, function(data){

            if(data.status == 'ok'){
                //$('#content').load('alunos/altera-aluno.php', {id: $('#salvar').attr('registro')});
                $('#financeiro').load('empresas/altera-empresa.php #financeiro', {id: $('#salvar').attr('registro')});
            }

        }, 'json');


    });


    /*SOMANDO PARCELAS SELECIONADA*/
    $('#financeiro').on('click', '#quitar-parcela', function(){

        var parcelas = '';

        $('.parcela:checked').each(function(){
            parcelas+=$(this).val()+'|';
        });

        $('#bt-modal-quitar-parcelas').attr('parcelas', parcelas);

        var dados = 'id='+$('#salvar').attr('registro')+'&parcelas='+parcelas+'&acao=calcular-parcelas';
        $.post('empresas/acoes.php', { dados: dados }, function(data){

            if(data.status == 'ok'){
                $('#valor_total_parcelas').html(data.total);
            }

        }, 'json');

    });


    /*QUITANDO PARCELAS*/
    $('#bt-modal-quitar-parcelas').click(function(){

        if($('#data_pagamento').val() != '' && $('id_forma_pagamento').val() != '') {
            var dados = 'id=' + $('#salvar').attr('registro') + '&parcelas=' + $(this).attr('parcelas') + '&data_pagamento=' + $('#data_pagamento').val() + '&id_forma_pagamento=' + $('#id_forma_pagamento').val() + '&acao=quitar-parcelas';
            $.post('empresas/acoes.php', {dados: dados}, function (data) {

                if (data.status == 'ok') {
                    $('#financeiro').load('empresas/altera-empresa.php #financeiro', {id: $('#salvar').attr('registro')});
                    $('#bt-cancelar-quitar-parcelas').click();
                }

            }, 'json');
        }

    });


    /*EXCLUIR PARCELA*/
    /*
    $('#financeiro').on('click', '.bt-cancelar-parcela', function(){

        //alert($(this).attr('parcela'));

        $('#bt-modal-cancelar-parcela').attr('registro', $(this).attr('parcela'));
        $('#observacao-cancelamento').val('');

    });
    */

    $('#excluir-parcelas').click(function(){

        var parcelas = '';

        $('.parcela:checked').each(function(){
            parcelas+=$(this).val()+'|';
        });


        $('#bt-modal-excluir-parcela').attr('parcelas', parcelas);

    });

    $('#bt-modal-excluir-parcela').click(function(){

        var dados = 'id='+$('#salvar').attr('registro')+'&parcelas='+$(this).attr('parcelas')+'&acao=excluir-parcela';
        $.post('empresas/acoes.php', { dados: dados }, function(data){

            if(data.status == 'ok'){
                $('#content').load('empresas/altera-empresa.php', {id: $('#salvar').attr('registro')});
            }

        }, 'json');

    });


    /*REMOVER PAGAMENTO*/
    $('#financeiro').on('click', '.bt-remover-pagamento-parcela', function(){

        var dados = 'id='+$('#salvar').attr('registro')+'&parcela='+$(this).attr('registro')+'&acao=remover-pagamento';
        $.post('empresas/acoes.php', { dados: dados }, function(data){

            if(data.status == 'ok'){
                $('#content').load('empresas/altera-empresa.php', {id: $('#salvar').attr('registro')});
            }

        }, 'json');

    });


    /*ALTERAR PARCELA*/
    $('#financeiro').on('click', '.bt-alterar-parcela', function(){

        $('#content').load('empresas/altera-parcela.php', {id: $(this).attr('registro'), id_parcela: $(this).attr('parcela')});

    });


    /*ADICIONAR PARCELA*/
    $('#financeiro').on('click', '#adicionar-parcela', function(){

        $('#content').load('empresas/adicionar-parcela.php', {id: $(this).attr('registro')});

    });


    /*CANCELAR PARCELA*/
    /*
    $('.bt-cancelar-parcela').click(function(){

        $('#bt-modal-cancelar-parcela').attr('registro', $(this).attr('parcela'));

    });
    */
    $('#cancelar-parcelas').click(function(){

        var parcelas = '';

        $('.parcela:checked').each(function(){
            parcelas+=$(this).val()+'|';
        });


        $('#bt-modal-cancelar-parcela').attr('parcelas', parcelas);
        $('#observacao-cancelamento').val('');

    });

    $('#bt-modal-cancelar-parcela').click(function(){

        if($('#observacao-cancelamento').val() != '')
        {
            var dados = 'id='+$('#salvar').attr('registro')+'&parcelas='+$(this).attr('parcelas')+'&observacao='+$('#observacao-cancelamento').val()+'&acao=cancelar-parcela';
            $.post('empresas/acoes.php', { dados: dados }, function(data){

                if(data.status == 'ok')
                {
                    $('#observacao-cancelamento').val('');
                    $('#content').load('empresas/altera-empresa.php', {id: $('#salvar').attr('registro')});
                    $('#bt-fecha-cancelar-parcela').click();

                }

            }, 'json');
        }

    });

});
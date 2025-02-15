$(function(){

    $('#formDados').submit(function(){return false});
    $('#formAlteraParcela').submit(function(){return false});
    $('#formPesquisa').submit(function(){return false});

    $('#msg-nao-exclusao').hide();

    $('#cep, #cep_responsavel').mask('00.000-000', {reverse: true});
    $('#cpf, #cpf_responsavel').mask('000.000.000-00', {reverse: true});
    $('#data_nascimento, #data_nascimento_responsavel').mask('00/00/0000');

    /*Data Atual*/
    var currentTime = new Date()
    var month = currentTime.getMonth() + 1;
    var day = currentTime.getDate();
    var year = currentTime.getFullYear();

    var date = day + "/" + month + "/" + year;

    if($('#data_nascimento').val() == '')
    {
        $('#data_nascimento').val(date);
    }

    /*carregando observacoes*/
    $('#aba-observacoes').one('click', function(){
        $('#content-observacoes').empty('').load('alunos/lista-observacoes.php', { id: $('#salvar').attr('registro') });
    });

    /*carregando Matrículas*/
    $('#aba-matricula').one('click', function(){
        $('#content-matriculas').empty('').load('alunos/lista-matriculas.php', { id: $('#salvar').attr('registro') });
    });

    /*carregando Documentos*/
    $('#aba-documentos').one('click', function(){
        $('#content-documentos').empty('').load('alunos/lista-documentos.php', { id: $('#salvar').attr('registro') });
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

    var options =  {
    onKeyPress: function(telefone, e, field, options) {
        var masks = ['(00)0000-00000', '(00)00000-0000'];
        var mask = (telefone.length>13) ? masks[1] : masks[0];
        $('#celular, #celular_responsavel, #telefone1, #telefone1_responsavel, #telefone2, #telefone2_responsavel, #telefone3, #telefone3_responsavel').mask(mask, options);
    }};

    $('#celular, #celular_responsavel, #telefone1, #telefone1_responsavel, #telefone2, #telefone2_responsavel, #telefone3, #telefone3_responsavel').mask('(00)0000-00000', options);

    function calculaMaioridade(nasc) {
        var hoje = new Date(), idade;

        idade = (
            (hoje.getMonth() > nasc.getMonth())
            ||
            (hoje.getMonth() == nasc.getMonth() && hoje.getDate() >= nasc.getDate())
        ) ? hoje.getFullYear() - nasc.getFullYear() : hoje.getFullYear() - nasc.getFullYear()-1;

        if (idade > 18) { return true; }
    }

    $('#endereco-aluno').click(function(){

        $("#cep_responsavel").val($("#cep").val());
        $("#endereco_responsavel").val($("#endereco").val());
        $("#bairro_responsavel").val($("#bairro").val());
        $("#complemento_responsavel").val($("#complemento").val());
        $("#numero_responsavel").val($("#numero").val());
        $('#estado_responsavel option[value="'+$('#estado').val()+'"]').prop("selected", true).delay(1000);
        $.post('../includes/lista-cidades.php', {estado: $('#estado_responsavel').val()}, function(data){
            $('#cidade_responsavel').html(data);
            $('#cidade_responsavel option[value="'+$('#cidade').val()+'"]').prop("selected", true);
        });

        $('#telefone1_responsavel').val($('#telefone1').val());
        $('#telefone2_responsavel').val($('#telefone2').val());
        $('#telefone3_responsavel').val($('#telefone3').val());

        return false;

    });

    /*----------------------------------------------------------------------------------------------------------------*/
    /*----------------------------------------------------------------------------------------------------------------*/

    $('#data_nascimento').focusout(function(){
        if($('#data_nascimento').val() != '')
        {
            var dados = 'acao=calcula-idade&data='+$(this).val()+'&id='+$('#salvar').attr('registro');
            $.post('alunos/acoes.php', { dados: dados }, function(data){

                if(data.status == 'ok'){
                    if(data.idade < 18)
                    {
                        $('#aba-responsavel').removeClass('oculto');
                    }
                    else if (data.idade >= 18)
                    {
                        $('#aba-responsavel').addClass('oculto');
                    }
                }

            }, 'json');
        }

        return false;

    });

    /*----------------------------------------------------------------------------------------------------------------*/
    /*----------------------------------------------------------------------------------------------------------------*/


    /*----------------------------------------------------------------------------------------------------------------*/
    /*----------------------------------------------------------------------------------------------------------------*/
    $('#box-empresa-financeiro, #box-empresa-pedagogico').hide();
    $('#responsavel_financeiro').change(function(){

        var resposavel_financeiro = $('#responsavel_financeiro option:selected').val();
        if(resposavel_financeiro == 2)
        {
            $('#box-empresa-financeiro').show();
        }
        else
        {
            $('#box-empresa-financeiro').hide();
        }

    });

    $('#responsavel_pedagogico').change(function(){

        var resposavel_pedagogico = $('#responsavel_pedagogico option:selected').val();
        if(resposavel_pedagogico == 2)
        {
            $('#box-empresa-pedagogico').show();
        }
        else
        {
            $('#box-empresa-pedagogico').hide();
        }

    });
    /*----------------------------------------------------------------------------------------------------------------*/
    /*----------------------------------------------------------------------------------------------------------------*/

    $('#funcao').change(function(){
        $('#dados1, #dados2, #dados3').hide();
        $('#dados'+$('#funcao option:selected').val()).show();
    });

    $('#bt-novo').click(function(){

        var dados = 'acao=novo';
        $.post('alunos/acoes.php', { dados: dados }, function(data){
            if(data.status == 'ok')
            {
                $('#content').empty('').load('alunos/altera-aluno.php', {id: data.id});
            }
            else if(data.status == 'erro-permissao')
            {
                $('#msg-permissao-dialog').html(data.mensagem);
                $('#ms-permissao-modal').click();
            }
        }, 'json');

        return false;

    });


    /*Pesquisa*/
    $('#valor_pesquisa').on('keypress', function(e){
        if(e.keyCode == 13)
        {
            $('#pesquisar').click();
            e.preventDefault();
        }
    });

    $('#pesquisar').click(function(){

        $('#listagem').html('<h2 class="h2 cinza">Pesquisando, aguarde...</h2>');

        $.post('alunos/listagem.php', { acao: 'pesquisar', valor_pesquisa: $('#valor_pesquisa').val(), situacao: $('#situacao').val(), unidade: $('#unidade').val(), origem: $('#origem').val() }, function(data){
            $('#listagem').html(data);
        });

    });
    /*Pesquisa*/


    $('#bt-voltar').click(function(){
        $('#content').empty('').load('alunos/alunos.php');
    });



    $('#listagem').on('click', '.bt-altera', function(){
        $('#content').load('alunos/altera-aluno.php', {id: $(this).attr('registro')});
    });



    /*Função para Salvar alterações do cadastro*/
    $('#bt-continua-cadastro').click(function(){

        var id = $(this).attr('registro');

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


        if($('#cpf').val() != '')
        {
            if(!validarCPF($('#cpf').val())){
                $('#ms-cpf-invalido-modal').click();
                exit;
            }
        }


        if($('#cpf_responsavel').val() != '')
        {
            if(!validarCPF($('#cpf_responsavel').val())){
                $('#ms-cpf-invalido-modal').click();
                exit;
            }
        }


        var dados = $('#formDados').serialize()+'&id='+$('#salvar').attr('registro')+'&acao=salvar';

        $('#ms-salvando-dialog').click();

        $.post('alunos/acoes.php', { dados: dados }, function(data){

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
            else if (data.status == 'erro-login') {
                $('#ms-login-dp-modal').click();
            }
            else if(data.status == 'ok')
            {
                $('#content').empty('').load('alunos/altera-aluno.php', { id: id });
                $('#ms-ok-modal').click();
            }

        }, 'json');

        //return false;

    });
    /*Função para Salvar alterações do cadastro*/



    $('#salvar').click(function(event){

        let continuar = true;

        let campos = [
            'caracteristicas',
            'objetivo',
            'historico',
            'promessa',
        ];

        for(let i = 0; i < campos.length; i++){
            if(document.querySelector(`#${campos[i]}`).value === ''){
                continuar = false;
            }
        }

        if(!continuar){
            let modal = document.querySelector('#mensagem-dialog');
            modal.querySelector('#titulo-modal').innerHTML = 'Erro';
            modal.querySelector('#mensagem-modal').innerHTML = 'Todos os campos do perfil são obrigatórios.';
            document.querySelector('#ms-mensagem-dialog').click();
            return ;
        }

        var id = $(this).attr('registro');

        $('#bt-continua-cadastro').attr('registro', $(this).attr('registro'));

        /*Verificando a maioridade*/
            if($('#data_nascimento').val == ''){
                var data_nascimento = 0;
            }


            var dados = 'acao=calcula-idade&data=' + $('#data_nascimento').val() + '&id=' + $('#salvar').attr('registro');
            $.post('alunos/acoes.php', {dados: dados}, function (data) {

                if (data.idade < 18)

                {
                    if (
                        $('#parentesco_responsavel').val() == '' ||
                        $('#nome_responsavel').val() == '' ||
                        $('#data_nascimento_responsavel').val() == '' ||
                        $('#rg_responsavel').val() == '' ||
                        $('#cpf_responsavel').val() == ''
                    ) {
                        $('#ms-preencher-dados-responsavel-modal').click();
                    } else {
                        $('#bt-continua-cadastro').click();
                    }
                }

                else if (data.idade > 17)

                {
                    /*Verifica duplicidade*/
                    var dados = 'cpf_aluno=' + $('#cpf').val() + '&cpf_responsavel=' + $('#cpf_responsavel').val() + '&id=' + $('#salvar').attr('registro') + '&acao=verifica-cpf';
                    $.post('alunos/acoes.php', {dados: dados}, function (data) {

                        if (data.status == 'erro') {
                            $('#mensagem-modal').html(data.mensagem);
                            $('#ms-dp-modal').click();
                        }
                        else if (data.status == 'erro-login') {
                            $('#ms-login-dp-modal').click();
                        }
                        else if (data.status == 'ok') {
                            $('#bt-continua-cadastro').click();
                        }

                    }, 'json');
                }

            }, 'json');
            /*Término da verificação de maioridade*/

        //event.stopPropagation();

    });



    $('#listagem').on('click', '.bt-excluir', function(){

        $('#bt-modal-excluir').attr('registro', $(this).attr('registro'));

    });




    $('#bt-modal-excluir').click(function(){

        var dados = 'id='+$(this).attr('registro')+'&acao=excluir';

        $.post('alunos/acoes.php', { dados: dados }, function(data){

            if(data.status == 'ok')
            {
                $('#content').load('alunos/alunos.php');
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

        $.post('alunos/acoes.php', { dados: dados }, function(data){
            if(data.status == 'erro-permissao')
            {
                $('#msg-permissao-dialog').html(data.mensagem);
                $('#ms-permissao-modal').click();
            }
        }, 'json');

    })

    /*---------------------------------------------------------------------------------------------------------------*/
    /*---------------------------------------------------------------------------------------------------------------*/
    /*BUSCA CEP*/

    $('#busca-cep').click(function(){

        var cidade_selecionada = '';
        var cidade = '';
        var dados = 'id='+$('#salvar').attr('registro')+'&cep='+$('#cep').val()+'&acao=busca-cep';
        $.post('../includes/busca-cep.php', { dados: dados }, function(data){

            if(data.status == 'ok')
            {
                $("#endereco").val(data.endereco);
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

        return false;

    });

    $('#busca-cep-responsavel').click(function(){

        var cidade_selecionada = '';
        var cidade = '';
        var dados = '&cep='+$('#cep_responsavel').val()+'&acao=busca-cep';
        $.post('../includes/busca-cep.php', { dados: dados }, function(data){

            if(data.status == 'ok')
            {
                $("#endereco_responsavel").val(data.endereco);
                $("#bairro_responsavelre").val(data.bairro);
                $("#complemento_responsavel").val(data.complemento);
                $("#numero_responsavel").val('');
                cidade_selecionada = data.cidade;
            }


            $("#estado_responsavel option:contains("+data.uf+")").attr('selected', true);

            $.post('../includes/lista-cidades.php', {estado: $('#estado_responsavel').val()}, function(data_cidade){

                console.log(cidade_selecionada);

                $('#cidade_responsavel').html(data_cidade);
                cidade = data_cidade;

                if(cidade != '') {
                    $("#cidade_responsavel option:contains(" + cidade_selecionada + ")").attr('selected', true);
                }

            });

        }, 'json');

        return false;

    });

    /*---------------------------------------------------------------------------------------------------------------*/
    /*---------------------------------------------------------------------------------------------------------------*/
    /*OBSERVAÇÕES*/
    $('#content-observacoes').on('click', '#nova-observacao', function(){
        $('#content-observacoes').empty('').load('alunos/nova-observacao.php');
    });

    $('#content-observacoes').on('click', '#voltar-observacoes', function(){
        $('#content-observacoes').empty('').load('alunos/lista-observacoes.php', { id: $('#salvar').attr('registro') });
    });

    $('#content-observacoes').on('click', '#salvar-observacao', function(){

        if($('#content-observacoes #observacao').val() == ''){
            $('#ms-observacao-modal').click();
            exit;
        }

        var dados = 'observacao='+$('#content-observacoes #observacao').val()+'&id='+$('#salvar').attr('registro')+'&acao=salvar-observacao';

        $('#ms-salvando-dialog').click();

        $.post('alunos/acoes.php', { dados: dados }, function(data){

            $('#bt-salvou').click();

            if(data.status == 'ok'){
                $('#content-observacoes').empty('').load('alunos/lista-observacoes.php', { id: $('#salvar').attr('registro') });
            }

        }, 'json');

        return false;
    });

    $('#content-observacoes').on('click', '.bt-visualiza-observacao', function(){

        $('#content-observacoes').empty('').load('alunos/visualiza-observacao.php', { id: $(this).attr('registro') });

    });


    /*---------------------------------------------------------------------------------------------------------------*/
    /*---------------------------------------------------------------------------------------------------------------*/
    /*MATRÍCULA*/

    $('#content-matriculas #formMatricula').submit(function(){return false});
    $('#content-matriculas #formAlteraMatricula').submit(function(){return false});

    $('#content-matriculas').on('change', '#id_situacao_aluno_turma', function(){

        if($(this).val() == 2){
            $('#content-matriculas #id_motivo_desistencia').show();
        } else {
            $('#content-matriculas #id_motivo_desistencia').hide();
        }

    });

    $('#content-matriculas').on('click', '#nova-matricula', function(){
        $('#content-matriculas').empty('').load('alunos/nova-matricula.php');
    });

    $('#content-matriculas').on('click', '#voltar-matricula', function(){
        $('#content-matriculas').empty('').load('alunos/lista-matriculas.php', { id: $('#salvar').attr('registro') });
    });

    $('#content-matriculas').on('click', '#salvar-matricula', function(e){

        e.preventDefault();

        //if($('#id_turma').val() != '' && $('#numero_parcelas').val() != '' && $('#valor_parcela').val() != '' && $('#data_vencimento').val() != '' && $('#responsavel_financeiro').val() != '' && $('#responsavel_pedagogico').val() != '') {
        if($('#id_turma').val() != '' /*&& $('#numero_parcelas').val() != '' && $('#data_vencimento').val() != ''*/ && $('#responsavel_financeiro').val() != '' && $('#responsavel_pedagogico').val() != '') {

            var dados = $('#formMatricula').serialize() + '&acao=salvar-matricula&id=' + $('#salvar').attr('registro');

            $('#ms-salvando-dialog').click();

            $.post('alunos/acoes.php', {dados: dados}, function (data) {
                $('#bt-salvou').click();

                if (data.status == 'ok') {
                    $('#content-matriculas').empty('').load('alunos/lista-matriculas.php', {id: $('#salvar').attr('registro')});
                    $('#financeiro').empty('').load('alunos/altera-aluno.php #financeiro', {id: $('#salvar').attr('registro')});
                    $('#situacao').val(1);
                }

                else if (data.status == 'erro-matricula') {
                    $('#ms-matricula-duplicada-modal').click();
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



    $('#content-matriculas').on('click', '#alterar-matricula', function(e){

        e.preventDefault();

        let id_situacao_aluno_turma = $('#id_situacao_aluno_turma').val();
        let data_vencimento = $('#data_vencimento').val();

        //if($('#id_turma').val() != '' && $('#numero_parcelas').val() != '' && $('#valor_parcela').val() != '' && $('#data_vencimento').val() != '' && $('#responsavel_financeiro').val() != '' && $('#responsavel_pedagogico').val() != '') {
        //(if($('#id_turma').val() != '' && $('#numero_parcelas').val() != '' && $('#data_vencimento').val() != '' && $('#responsavel_financeiro').val() != '' && $('#responsavel_pedagogico').val() != '') {
        if($('#id_turma').val() != '' && $('#numero_parcelas').val() != '' && ((id_situacao_aluno_turma == 1 && data_vencimento !== '') || (id_situacao_aluno_turma == 2)) && $('#responsavel_financeiro').val() != '' && $('#responsavel_pedagogico').val() != '') {

            var dados = $('#formAlteraMatricula').serialize() + '&acao=alterar-matricula&id_matricula=' + $('#alterar-matricula').attr('registro')+'&id='+$('#salvar').attr('registro');

            $.post('alunos/acoes.php', {dados: dados}, function (data) {

                if (data.status == 'ok') {
                    $('#content-matriculas').empty('').load('alunos/lista-matriculas.php', {id: $('#salvar').attr('registro')});

                    if(data.status_matricula != null){
                        $('#situacao option[value="'+data.status_matricula+'"]').prop('selected', true);
                        console.log(data.status_matricula);
                    }
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


    $('#content-matriculas').on('click', '.bt-altera-matricula', function(){

        $('#content-matriculas').empty('').load('alunos/altera-matricula.php', { id: $(this).attr('registro') });

    });


    $('#content-matriculas').on('click', '.bt-excluir-matricula', function(){
        $('#bt-modal-excluir-matricula').attr('registro', $(this).attr('registro'));
    });


    $('#bt-modal-excluir-matricula').click(function(){

        var id = $('#salvar').attr('registro');
        var dados = 'id='+$('#salvar').attr('registro')+'&acao=excluir-matricula&id_matricula='+$(this).attr('registro');

        $.post('alunos/acoes.php', { dados: dados }, function(data){

            if(data.status == 'ok')
            {
                $('#bt-modal-cancelar').click();
                $('#content').load('alunos/altera-aluno.php', {id: id});
            }
            else if(data.status == 'erro')
            {
                $('#bt-modal-cancelar').click();
                $('#ms-erro-exclusao-matricula-dialog').click();
            }
            else if(data.status == 'erro-permissao')
            {
                $('#bt-modal-cancelar').click();
                $('#msg-permissao-dialog').html(data.mensagem);
                $('#ms-permissao-modal').click();
            }

        }, 'json');

        return false;

    });


    /*----------------------------------------------------------------------------------------------------------------*/
    /*DOCUMENTOS*/

    $('#content-documentos').on('click', '#editar-documento', function(){

        var matricula = $('#id_matricula').val();
        var documento = $('#id_texto').val();

        $('#content-documentos').empty('').load('alunos/contrato.php', { aluno: $(this).attr('registro'), matricula: matricula, documento: documento} );
    });


    $('#content-documentos').on('click', '#voltar-documentos', function(){
        $('#content-documentos').empty('').load('alunos/lista-documentos.php', { id: $('#salvar').attr('registro') });
    });


    /*----------------------------------------------------------------------------------------------------------------*/
    /*Parcelas*/

    /*Pesquisa parcelas*/
    $('#financeiro').on('click', '#pesquisar-parcelas', function(){

        $('#listagem-parcelas').html('<h2 class="h2 cinza">Pesquisando, aguarde...</h2>');

        $.post('alunos/listagem-parcelas.php', { id: $(this).attr('registro'), id_turma: $('#id_turma').val(), id_idioma: $('#id_idioma').val(), status_parcela: $('#status_parcela').val() /*id_empresa: $('#id_empresa').val()*/ }, function(data){
            console.log(data);
            $('#listagem-parcelas').html(data);
        });

    });
    /*Pesquisa parcelas*/

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

            $.post('alunos/acoes.php', {dados: dados}, function (data) {

                if (data.status == 'ok') {
                    //$('#content').load('alunos/altera-aluno.php', {id: $('#salvar').attr('registro')});
                    $('#financeiro').empty('').load('alunos/altera-aluno.php #financeiro', {id: $('#salvar').attr('registro')});
                    $('#content-observacoes').empty('').load('alunos/lista-observacoes.php', { id: $('#salvar').attr('registro') });
                    $('#bt-cancela-altera-parcelas').click();
                    $('#formAlteraParcela')[0].reset();
                }

            }, 'json');

            return false;
        }

    });


    /*ZERANDO JUROS, MULTA, ACRESCIMOS E DESCONTOS*/
    $('#financeiro').on('click', '#zerar-valores', function(){

        var parcelas = '';

        $('.parcela:checked').each(function(){
            parcelas+=$(this).val()+'|';
        });

        var dados = 'id='+$('#salvar').attr('registro')+'&parcelas='+parcelas+'&acao=zerar-valores';
        $.post('alunos/acoes.php', { dados: dados }, function(data){

            if(data.status == 'ok'){
                //$('#content').load('alunos/altera-aluno.php', {id: $('#salvar').attr('registro')});
                $('#financeiro').empty('').load('alunos/altera-aluno.php #financeiro', {id: $('#salvar').attr('registro')});
            }

        }, 'json');

        return false;


    });



    /*SOMANDO PARCELAS SELECIONADA*/
    $('#financeiro').on('click', '#quitar-parcela', function(){

        var parcelas = '';

        $('.parcela:checked').each(function(){
            parcelas+=$(this).val()+'|';
        });

        $('#bt-modal-quitar-parcelas').attr('parcelas', parcelas);

        var dados = 'id='+$('#salvar').attr('registro')+'&parcelas='+parcelas+'&acao=calcular-parcelas';
        $.post('alunos/acoes.php', { dados: dados }, function(data){

            if(data.status == 'ok'){
                $('#valor_total_parcelas').html(data.total);
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
            var dados = 'id=' + $('#salvar').attr('registro') + '&parcelas=' + $(this).attr('parcelas') + '&data_pagamento=' + $('#data_pagamento').val() + '&id_forma_pagamento=' + $('#id_forma_pagamento').val() + '&acao=quitar-parcelas';
            $.post('alunos/acoes.php', {dados: dados}, function (data) {

                if (data.status == 'ok') {
                    $('#financeiro').empty('').load('alunos/altera-aluno.php #financeiro', {id: $('#salvar').attr('registro')});
                    $('#bt-cancelar-quitar-parcelas').click();
                } else if (data.status == 'erro-caixa'){
                    $('#ms-erro-caixa-modal').click();
                }
                else if(data.status == 'erro-vencimento')
                {
                    $('#bt-cancelar-quitar-parcelas').click();
                    $('#mensagem-modal-vencimento').html(data.mensagem);
                    $('#ms-erro-vencimento-dialog').click();
                }

            }, 'json');

            return false;

        }

    });


    /*EXCLUIR PARCELA*/
    $('#financeiro').on('click', '.bt-excluir-parcela', function(){

        $('#bt-modal-excluir-parcela').attr('registro', $(this).attr('registro'));

    });


    $('#excluir-parcelas').click(function(){

        var parcelas = '';

        $('#listagem-parcelas .parcela:checked').each(function(){
            parcelas+=$(this).val()+'|';
        });

        $('#bt-modal-excluir-parcela').attr('parcelas', parcelas);

    });


    $('#bt-modal-excluir-parcela').click(function(){

        var dados = 'id='+$('#salvar').attr('registro')+'&parcelas='+$(this).attr('parcelas')+'&acao=excluir-parcela';
        $.post('alunos/acoes.php', { dados: dados }, function(data){

            if(data.status == 'ok'){
                //$('#bt-canclar-exclusao').click();
                $('#pesquisar-parcelas').click();
                //$('#financeiro').empty('').load('alunos/altera-aluno.php #financeiro', {id: $('#salvar').attr('registro')});
            }

        }, 'json');

        return false;

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


    /*REMOVER PAGAMENTO*/
    $('#financeiro').on('click', '.bt-remover-pagamento-parcela', function(){

        var dados = 'id='+$('#salvar').attr('registro')+'&parcela='+$(this).attr('registro')+'&acao=remover-pagamento';
        $.post('alunos/acoes.php', { dados: dados }, function(data){

            if(data.status == 'ok'){
                $('#pesquisar-parcelas').click();
                //$('#financeiro').empty('').load('alunos/altera-aluno.php #financeiro', {id: $('#salvar').attr('registro')});
            }

        }, 'json');

        return false;

    });


    /*ALTERAR PARCELA*/
    $('#financeiro').on('click', '.bt-alterar-parcela', function(){

        $('#content').empty('').load('alunos/altera-parcela.php', {id: $(this).attr('registro'), id_parcela: $(this).attr('parcela')});

    });


    /*ADICIONAR PARCELA*/
    $('#financeiro').on('click', '#adicionar-parcela', function(){

        $('#content').empty('').load('alunos/adicionar-parcela.php', {id: $(this).attr('registro')});

    });


    /*CANCELAR PARCELA*/
    $('#financeiro').on('click', '.bt-cancelar-parcela', function(){

        //alert($(this).attr('parcela'));

        $('#bt-modal-cancelar-parcela').attr('registro', $(this).attr('parcela'));
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
            var dados = 'id='+$('#salvar').attr('registro')+'&parcelas='+$(this).attr('parcelas')+'&observacao='+$('#observacao-cancelamento').val()+'&acao=cancelar-parcela';
            $.post('alunos/acoes.php', { dados: dados }, function(data){

                if(data.status == 'ok')
                {
                    /*$('#financeiro').empty('').load('alunos/altera-aluno.php #financeiro', {id: $('#salvar').attr('registro')});*/
                    $('#pesquisar-parcelas').click();
                    $('#content-observacoes').empty('').load('alunos/lista-observacoes.php', { id: $('#salvar').attr('registro') });
                    $('#bt-fecha-cancelar-parcela').click();

                }

            }, 'json');

            return false;
        }

    });


    $('#listagem-parcelas').on('click', '.bt-descancelar-parcela', function () {

        var id_parcela = $(this).attr('registro');
        var dados = 'id_parcela='+id_parcela+'&acao=descancelar';
        $.post('alunos/acoes.php', { dados: dados }, function (data) {

            if(data.status == 'ok'){
                $('#pesquisar-parcelas').click();
            }

        }, 'json');

    });


    /*VALIDÇÕES DE CPF E CNPJ*/
    /*Validação de CPF*/
    function validarCPF(strCPF){
        strCPF = strCPF.replace('.','');
        strCPF = strCPF.replace('.','');
        strCPF = strCPF.replace('.','');
        strCPF = strCPF.replace('-','');

        var Soma;
        var Resto;
        Soma = 0;

        if (strCPF == "00000000000") return false;

        for (i=1; i<=9; i++) Soma = Soma + parseInt(strCPF.substring(i-1, i)) * (11 - i);
        Resto = (Soma * 10) % 11;

        if ((Resto == 10) || (Resto == 11))  Resto = 0;
        if (Resto != parseInt(strCPF.substring(9, 10)) ) return false;

        Soma = 0;
        for (i = 1; i <= 10; i++) Soma = Soma + parseInt(strCPF.substring(i-1, i)) * (12 - i);
        Resto = (Soma * 10) % 11;

        if ((Resto == 10) || (Resto == 11))  Resto = 0;
        if (Resto != parseInt(strCPF.substring(10, 11) ) ) return false;
        return true;
    }


});

$(function(){
    $('#formDados').submit(function(){return false});

    $('#data_vencimento').mask('00/00/0000');
    $('#valor').maskMoney({prefix:'R$ ', allowNegative: true, thousands:'.', decimal:',', affixesStay: false});
    $('#valor_parcela').maskMoney({prefix:'R$ ', allowNegative: true, thousands:'.', decimal:',', affixesStay: false});

    $('#voltar').click(function(){
        $('#content').load('alunos/altera-aluno.php', {id: $(this).attr('registro')});
    });

    $('#voltar-contas-receber').click(function(){
        $('#content').load('contas-receber/contas-receber.php');
    });


    /*-----------------------------------------------------------------------------------*/
    /*Mostrando alunos de acordo com a turma selecinada - pagina adicionar parcela em contas a receber*/
    $('#id_turma').change(function(){

        var dados = 'id_turma='+$(this).val()+'&acao=listar-alunos';
        $.post('contas-receber/acoes.php', { dados: dados }, function(data){

            $('#id_aluno').html(data);

        });

    });


    /*-----------------------------------------------------------------------------------*/
    /*Página de alteração de parcela dentro do cadastro de aluno*/
    $('#salvar').click(function(){

        if($('#observacao').val() != '') {

            $('#voltar').attr('disabled', 'disabled');

            $('#ms-salvando-dialog').click();

            var dados = $('#formDados').serialize() + '&id=' + $(this).attr('registro') + '&id_parcela=' + $(this).attr('parcela') + '&acao=alterar-parcela';
            $.post('alunos/acoes.php', {dados: dados}, function (data) {

                $('#bt-salvou').click();

                if (data.status == 'ok') {
                    $('#content').load('alunos/altera-aluno.php', {id: $('#salvar').attr('registro')});
                }

            }, 'json');

        }

    });


    /*-----------------------------------------------------------------------------------*/
    /*Página de alteração de parcela dentro do cadastro de contas a receber*/
    $('#salvar-conta-receber').click(function(){

        if($('#observacao').val() != '') {

            $('#ms-salvando-dialog').click();

            var dados = $('#formDados').serialize() + '&id=' + $(this).attr('registro') + '&id_parcela=' + $(this).attr('parcela') + '&acao=alterar-parcela';
            $.post('alunos/acoes.php', {dados: dados}, function (data) {

                $('#bt-salvou').click();

                if (data.status == 'ok') {
                    $('#content').load('contas-receber/contas-receber.php');
                }

            }, 'json');

        }

    });


    $('#salvar-parcela').click(function(){

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

        var id = $(this).attr('registro');

        var dados = $('#formDados').serialize()+'&id='+$(this).attr('registro')+'&id_aluno='+$('#id_aluno').val()+'&id_matricula='+$('#id_turma').find(':selected').attr('matricula')+'&acao=salvar-nova-parcela';
        $.post('alunos/acoes.php', { dados: dados }, function(data){

            $('#bt-salvou').click();

            if (data.status == 'ok') {
                $('#content').load('alunos/altera-aluno.php', {id: data.id});
            }

        }, 'json');

    });

    $('#salvar-parcela-conta-receber').click(function(){

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

        var id = $(this).attr('registro');

        var dados = $('#formDados').serialize()+'&id='+$(this).attr('registro')+'&id_aluno='+$('#id_aluno').val()+'&acao=salvar-nova-parcela';

        console.log(dados);

        $.post('alunos/acoes.php', { dados: dados }, function(data){

            $('#bt-salvou').click();

            if (data.status == 'ok') {
                //$('#content').load('contas-receber/contas-receber.php');
            }

        }, 'json');

    });


});

$(function(){

    $('#formPesquisa').submit(function(){return false});

    function carregamentoAutomatico(){
        $('#id_coach').val(sessionStorage.getItem('id_coach'));
        $('#id_colega').val(sessionStorage.getItem('id_colega'));
        $('#status_turma').val(sessionStorage.getItem('status_turma'));
        $('#valor_pesquisa').val(sessionStorage.getItem('valor_pesquisa'));

        if(sessionStorage.getItem('id_coach') !== ''){
            var dados = 'acao=busca-instrutores&id_coach='+sessionStorage.getItem('id_coach');
            $.post('coachs/acoes.php', { dados: dados }, function(data){

                if($('#id_colega').html(data)){
                    $('#id_colega').val(sessionStorage.getItem('id_colega'));

                    if(
                        sessionStorage.getItem('id_coach') !== null ||
                        sessionStorage.getItem('id_colega') !== null ||
                        sessionStorage.getItem('status_turma') !== null ||
                        sessionStorage.getItem('valor_pesquisa') !== null
                    ) {
                        $('#pesquisar').click();
                    }
                }

            });
        }
    }
    carregamentoAutomatico();


    /*Ao selecionar um coach*/
    $('#id_coach').change(function(){

        var dados = 'acao=busca-instrutores&id_coach='+$(this).val();
        $.post('coachs/acoes.php', { dados: dados }, function(data){
            $('#id_colega').html(data);
        });

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

        sessionStorage.setItem('valor_pesquisa', $('#valor_pesquisa').val());
        sessionStorage.setItem('id_coach', $('#id_coach').val());
        sessionStorage.setItem('id_colega', $('#id_colega').val());
        sessionStorage.setItem('status_turma', $('#status_turma').val());

        $.post('coachs/listagem.php', { acao: 'pesquisar', valor_pesquisa: $('#valor_pesquisa').val(), id_colega: $('#id_colega').val(), status_turma: $('#status_turma').val() }, function(data){
            $('#listagem').html(data);
        });

    });
    /*Pesquisa*/

    $('#voltar-coachs').click(function(){
        $('#content').load('coachs/coachs.php');
    });


    $('#listagem').on('click', '.bt-altera-turma', function(){
        $('#content').load('coachs/altera-turma.php', {id: $(this).attr('registro')});
    });

    $('#integrantes').on('click', '#voltar-turma', function(){
        $('#content').load('coachs/altera-turma.php', {id: $('#salvar').attr('registro')});
    });


    $('#integrantes').on('click', '.bt-altera-integrante', function(){
       $('#integrantes').load('coachs/dados-aluno.php', { id: $(this).attr('registro'), turma: $(this).attr('turma') });
    });


    $('#listagem').on('click', '.bt-altera-aluno', function(){
        $('#content').load('coachs/dados-aluno-single.php', {id: $(this).attr('registro')});
    });


    /*Atas*/
    $('#bt-salvar-ata-turma').click(function (){

        var id_turma = $(this).attr('turma');

        $('#formNovaAtaTurma').find('input').each(function(){

            if($(this).attr('required') && $(this).val() == '')
            {
                exit;
            }

        });

        $('#formNovaAtaTurma').find('select').each(function(){

            if($(this).attr('required') && $(this).val() == '')
            {
                exit;
            }

        });


        $('#ms-salvando-dialog').click();

        var dados = $('#formNovaAtaTurma').serialize()+'&id_turma='+id_turma+'&acao=salvar-ata-turma';
        $.post('coachs/acoes.php', { dados: dados }, function(data){

            if(data.status == 'ok'){

                $('#bt-salvou').click();
                $('#formNovaAtaTurma')[0].reset();
                $('#listagem-atas-turma').load('coachs/listagem-atas-turma.php', { id: id_turma});

            }

        }, 'json');

    });


    $('#listagem-atas-turma').on('click', '.bt-altera-ata-turma', function(){

        var id_ata = $(this).attr('ata');

        var dados = 'acao=busca-ata&ata='+$(this).attr('ata');
        $.post('coachs/acoes.php', { dados: dados }, function(data){
            if(data.status == 'ok'){
                $('#alterar-ata-turma').val(data.ata);
                $('#bt-alterar-ata-turma').attr('ata', id_ata);
                $('#ms-alterar-ata-turma-dialog').click();
            }
        }, 'json');

    });


    $('#bt-alterar-ata-turma').click(function (){

        $('#formAlteraAtaTurma').find('input').each(function(){

            if($(this).attr('required') && $(this).val() == '')
            {
                exit;
            }

        });

        $('#formAlteraAtaTurma').find('select').each(function(){

            if($(this).attr('required') && $(this).val() == '')
            {
                exit;
            }

        });


        $('#ms-salvando-dialog').click();

        var id_turma = $(this).attr('turma');
        var dados = $('#formAlteraAtaTurma').serialize()+'&id_turma='+id_turma+'&ata='+$(this).attr('ata')+'&acao=alterar-ata-turma';
        $.post('coachs/acoes.php', { dados: dados }, function(data){

            if(data.status == 'ok'){

                $('#bt-salvou').click();
                $('#formAlteraAtaTurma')[0].reset();
                $('#listagem-atas-turma').load('coachs/listagem-atas-turma.php', { id: id_turma});

            }

        }, 'json');

    });

});
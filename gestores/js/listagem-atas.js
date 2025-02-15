$(function(){

    /*Atas*/

    $('.bt-altera-ata').click(function(){

        var id_ata = $(this).attr('ata');

        $('#id_altera_turma option[value='+$(this).attr('turma')+']').prop("selected", true);

        var dados = 'acao=busca-ata&ata='+$(this).attr('ata');
        $.post('coachs/acoes.php', { dados: dados }, function(data){
            if(data.status == 'ok'){
                $('#alterar-ata').val(data.ata);
                $('#bt-alterar-ata').attr('ata', id_ata);
                $('#ms-alterar-ata-dialog').click();
            }
        }, 'json');

    });


    $('#bt-salvar-ata').click(function (){

        var id_aluno = $(this).attr('aluno');

        $('#formNovaAta').find('input').each(function(){

            if($(this).attr('required') && $(this).val() == '')
            {
                exit;
            }

        });

        $('#formNovaAta').find('select').each(function(){

            if($(this).attr('required') && $(this).val() == '')
            {
                exit;
            }

        });


        $('#ms-salvando-dialog').click();

        var dados = $('#formNovaAta').serialize()+'&id_aluno='+id_aluno+'&acao=salvar-ata';
        $.post('coachs/acoes.php', { dados: dados }, function(data){

            if(data.status == 'ok'){

                $('#bt-salvou').click();
                $('#formNovaAta')[0].reset();
                $('#listagem-atas-single').load('coachs/listagem-atas-single.php', { id: id_aluno});
                $('#listagem-atas').load('coachs/listagem-atas.php', { id: id_aluno});

            }

        }, 'json');

    });


    $('#bt-alterar-ata').click(function (){

        $('#formAlteraAta').find('input').each(function(){

            if($(this).attr('required') && $(this).val() == '')
            {
                exit;
            }

        });

        $('#formAlteraAta').find('select').each(function(){

            if($(this).attr('required') && $(this).val() == '')
            {
                exit;
            }

        });


        $('#ms-salvando-dialog').click();

        var id_aluno = $(this).attr('aluno');
        var dados = $('#formAlteraAta').serialize()+'&ata='+$(this).attr('ata')+'&acao=alterar-ata';
        $.post('coachs/acoes.php', { dados: dados }, function(data){

            if(data.status == 'ok'){

                $('#bt-salvou').click();
                $('#formAlteraAta')[0].reset();
                $('#listagem-atas-single').load('coachs/listagem-atas-single.php', { id: id_aluno});
                $('#listagem-atas').load('coachs/listagem-atas.php', { id: id_aluno});

            }

        }, 'json');

    });

});
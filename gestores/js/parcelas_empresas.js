$(function(){
    $('#formDados').submit(function(){return false});

    $('#valor_parcela').maskMoney({prefix:'R$ ', allowNegative: true, thousands:'.', decimal:',', affixesStay: false});
    $('#data_vencimento').mask('00/00/0000');

    $('#voltar').click(function(){
        $('#content').load('empresas/altera-empresa.php', {id: $(this).attr('registro')});
    });

    $('#salvar').click(function(){

        if($('#observacao').val() != '') {

            var dados = $('#formDados').serialize() + '&id=' + $(this).attr('registro') + '&id_parcela=' + $(this).attr('parcela') + '&acao=alterar-parcela';

            $('#ms-salvando-dialog').click();

            $.post('empresas/acoes.php', {dados: dados}, function (data) {

                if (data.status == 'ok') {
                    $('#bt-salvou').click();
                    $('#content').load('empresas/altera-empresa.php', {id: $('#salvar').attr('registro')});
                }

            }, 'json');

        }

    });


    $('#salvar-parcela').click(function(){

        var dados = $('#formDados').serialize()+'&id='+$(this).attr('registro')+'&id_matricula='+$('#id_turma').find(':selected').attr('matricula')+'&acao=salvar-nova-parcela';

        $('#ms-salvando-dialog').click();

        $.post('empresas/acoes.php', { dados: dados }, function(data){

            if (data.status == 'ok') {
                $('#bt-salvou').click();
                $('#content').load('empresas/altera-empresa.php', {id: $('#salvar-parcela').attr('registro')});
            }

        }, 'json');

    });

});

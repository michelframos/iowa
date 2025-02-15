$(function(){

    $('#formDados').submit(function(){return false});

    $('#salvar').click(function(e){

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

        var dados = $('#formDados').serialize()+'&acao=salvar';
        $.post('emails/acoes.php', { dados: dados }, function(data){
            if(data.status == 'ok'){
                $('#ms-ok-modal').click();
            }
        }, 'json');

        e.preventBubble();

    });

});

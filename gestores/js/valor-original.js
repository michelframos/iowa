$(function(){

    $('#formPesquisa').submit(function(){return false});
    $('#listagem .valor').maskMoney({prefix:'R$ ', allowNegative: true, thousands:'.', decimal:',', affixesStay: false});

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

        $.post('valor-original/listagem.php', { acao: 'pesquisar', valor_pesquisa: $('#valor_pesquisa').val(), unidade: $('#unidade').val(), turma: $('#turma').val(), situacao: $('#situacao').val() }, function(data){
            $('#listagem').html(data);
        });

    });
    /*Pesquisa*/


    $('#listagem').on('click', '.bt-altera', function(){
        $('#content').load('alunos/altera-aluno.php', {id: $(this).attr('registro')});
    });


    $('body').on('click', ' #salvar', function (){

        var dados = $('#formValores').serialize()+'&acao=salvar';
        $.post('valor-original/acoes.php', { dados: dados }, function (data){

            $('#ms-salvando-dialog').click();

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


});
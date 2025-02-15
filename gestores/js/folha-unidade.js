$(function(){

    $('#professor').change(function(){

        var dados = $('#formPesquisa').serialize()+'&acao=busca-turmas';
        $.post('relatorios/folha-unidade/acoes.php', { dados: dados }, function(data){

            $('#turma').html(data);

        });

    });


    $('#gerar-relatorio').click(function(){

        $('#relatorio').html('<div>Gerando Relatório, aguarde...</div>');

        var dados = $('#formPesquisa').serialize()+'&acao=gerar-relatorio';
        $.post('relatorios/folha-unidade/acoes.php', { dados: dados }, function(data){

            $('#relatorio').html(data);

        });

    });


    $('#imprimir-relatorio').click(function(){
        $('#relatorio').print({
            globalStyles: true,
            mediaPrint: false,
            stylesheet: "http://fonts.googleapis.com/css?family=Inconsolata",
            noPrintSelector: ".no-print",
            iframe: true,
            append: null,
            prepend: null,
            manuallyCopyFormValues: true,
            deferred: $.Deferred(),
            timeout: 750,
            title: null,
            doctype: '<!doctype html>'
        });
    })


});

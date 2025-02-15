$(function (){

    /*
    var dados = 'acao=gerar-relatorio';
    $.post('relatorios/relatorio-faltas/acoes.php', { dados: dados }, function (data){
        $('#relatorio').html(data);
    });
    */

    $('#gerar-relatorio').click(function (){

        $('#relatorio').html('<h2>Gerando relatório, por favor aguarde...</h2>');

        var dados = $('#formPesquisa').serialize()+'&acao=gerar-relatorio';
        $.post('relatorios/consolidado-faltas/acoes.php', { dados: dados }, function (data){
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
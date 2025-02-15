$(function(){

    $('#gerar-relatorio').click(function(){

        var dados = $('#formPesquisa').serialize()+'&acao=gerar-relatorio';
        $.post('relatorios/alunos-unidade/acoes.php', { dados: dados }, function(data){

            $('#relatorio').html(data);

        });

    });

    $('#imprimir-relatorio').click(function(){
        $('#relatorio').printMe(
            { "path": ["relatorios/impressao.css"], "title": "<p id='titulo'>Relatório de Aluno - Material</p>" }
        );
    });


    /*
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
    */


});

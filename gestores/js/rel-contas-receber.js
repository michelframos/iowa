$(function(){

    $('#unidade').change(function(){

        var dados = $('#formPesquisa').serialize()+'&acao=busca-turmas';
        $.post('relatorios/contas-receber/acoes.php', { dados: dados }, function(data){

            $('#id_turma').html(data);

        });

    });


    $('#situacao_aluno').change(function(){

        var dados = $('#formPesquisa').serialize()+'&acao=busca-alunos';
        $.post('relatorios/contas-receber/acoes.php', { dados: dados }, function(data){

            $('#nome_aluno').html(data);

        });

    });


    $('#gerar-relatorio').click(function(){

        $('#relatorio').html('<div class="size-1-5 bold">Pesquisando, por favor aguarde..</div>');

        var dados = $('#formPesquisa').serialize()+'&acao=gerar-relatorio';
        $.post('relatorios/contas-receber/acoes.php', { dados: dados }, function(data){

            $('#relatorio').html(data);

        });

    });


    /*
    $('#imprimir-relatorio').click(function(){
        $('#relatorio').print({
            globalStyles: true,
            mediaPrint: true,
            //stylesheet: "http://fonts.googleapis.com/css?family=Inconsolata",
            stylesheet: "../impressao.css",
            //noPrintSelector: ".no-print",
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

    $('#imprimir-relatorio').click(function(){
        $('#relatorio').printMe(
            { "path": ["relatorios/impressao.css"], "title": "<p id='titulo'>Relatório de Contas a Receber</p>" }
        );
    });

});

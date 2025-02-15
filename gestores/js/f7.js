$(function(){

    $('#id_unidade').change(function(){

        var dados = $('#formPesquisa').serialize()+'&acao=busca-turmas';
        $.post('relatorios/f7/acoes.php', { dados: dados }, function(data){

            $('#turma').html(data);

        });

        // var dados_professores = $('#formPesquisa').serialize()+'&acao=buscar-professores';
        // $.post('relatorios/f7/acoes.php', { dados: dados_professores }, function (data) {
        //     $('#id_professor').html(data);
        // })

    });

    $('#id_professor').change(function(){

        var dados = $('#formPesquisa').serialize()+'&acao=busca-turmas';
        $.post('relatorios/f7/acoes.php', { dados: dados }, function(data){

            $('#turma').html(data);

        });

    });


    $('#gerar-relatorio').click(function(){

        $('#relatorio').html('<div class="texto-center titulo">Carregando..</div>');

        var dados = $('#formPesquisa').serialize()+'&acao=gerar-relatorio';
        $.post('relatorios/f7/acoes.php', { dados: dados }, function(data){

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

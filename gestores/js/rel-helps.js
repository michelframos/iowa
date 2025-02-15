$(function(){

    $('#turma').change(function(){

        var dados = $('#formPesquisa').serialize()+'&acao=busca-alunos';
        $.post('relatorios/helps/acoes.php', { dados: dados }, function(data){

            $('#aluno').html(data);

        });

    });

    $('#gerar-relatorio').click(function(){

        if($('#professor').val() == ''){
            $('#professor').focus();
            exit;
        }

        var dados = $('#formPesquisa').serialize()+'&acao=gerar-relatorio';
        $.post('relatorios/helps/acoes.php', { dados: dados }, function(data){

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

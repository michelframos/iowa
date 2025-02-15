$(function(){

    $('#gerar-relatorio').click(function(){


        var dados = $('#formPesquisa').serialize()+'&acao=gerar-relatorio';
        $.post('relatorios/email-marketing/acoes.php', { dados: dados }, function(data){

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
    });

    $('#exportar-relatorio').click(async function () {

        let situacao_aluno = document.querySelector('#situacao_aluno').value;
        let data_inicial_matricula = document.querySelector('#data_inicial_matricula').value;
        let data_final_matricula = document.querySelector('#data_final_matricula').value;
        let data_inicial_nativado = document.querySelector('#data_inicial_nativado').value;
        let data_final_inativado = document.querySelector('#data_final_inativado').value;

        let dados = new FormData();
        dados.append('situacao_aluno', situacao_aluno);
        dados.append('data_inicial_matricula', data_inicial_matricula);
        dados.append('data_final_matricula', data_final_matricula);
        dados.append('data_inicial_nativado', data_inicial_nativado);
        dados.append('data_final_inativado', data_final_inativado);

        let response = await fetch(
            HOME()+'/exportacoes/rel-email-marketing.php',
            {
                method: 'post',
                body: dados,
            }
        );

        let data = await response.json();

        if(data.status === 'ok'){
            window.open(HOME()+'/exportacoes/download.php?arquivo='+data.arquivo);
        }


    });


});

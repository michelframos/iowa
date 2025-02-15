$(function(){

    $('#formLerRetorno').submit(function(){return false});

    $('#taxa, #multa, #juros').mask('0,00000', {reverse: true});

    /*
    $('#taxa').maskMoney({sulfix:' %', allowNegative: true, thousands:'.', decimal:',', affixesStay: false});
    $('#multa').maskMoney({sulfix:' %', allowNegative: true, thousands:'.', decimal:',', affixesStay: false});
    $('#juros').maskMoney({sulfix:' %', allowNegative: true, thousands:'.', decimal:',', affixesStay: false});
    */



    $('#filtrar').click(function(){

        $('#lista-selecionar-parcelas').html('<div class="titulo size-1-5">Filtrando...</div>');

        var dados = $('#formParcelas').serialize();
        $('#lista-selecionar-parcelas').load('cobranca/selecionar-parcelas.php', { dados: dados });

    });



    $('#salvar').click(function(){

        $('#ms-salvando-dialog').click();

        var dados = $('#formGerarCobranca').serialize()+'&acao=salvar';
        $.post('cobranca/acoes_bb.php', { dados: dados }, function(data){

            if(data.status == 'ok'){
                $('#bt-salvou').click();
            }
            else if(data.status == 'erro-permissao')
            {
                $('#msg-permissao-dialog').html(data.mensagem);
                $('#ms-permissao-modal').click();
            }


        }, 'json');

    });



    $('#gerar').click(function(){

        var id_unidade = $('#id_unidade').val();
        if(id_unidade == '')
        {
            $('#ms-selecao-unidade-dialog').click();
        }
        else
        {
            var parcelas = '';

            $('.parcela:checked').each(function(){
                parcelas+=$(this).val()+'|';
            });

            $('#bt-imprimir-gerar').attr('parcelas', parcelas);
            $('#ms-imprimir-gerar-dialog').click();
        }

    });



    $('#bt-imprimir-gerar').click(function(){

        var tipo_acao = $('#tipo_acao').val();

        var dados = $('#formGerarCobranca').serialize()+'&parcelas='+$(this).attr('parcelas');

        $.post('cobranca/acoes_bb.php', {dados:dados}, function(data){

            if(tipo_acao == 'imprimir'){
                $('#lista-boletos').html(data);
                $('#ms-boletos-dialog').click();

            }

            if(tipo_acao == 'arquivo_cnab'){

                if(data.status == 'ok'){
                    //$('#lista-boletos').html('<a href="../../cobranca/'+data.arquivo+'">Baixar Arquivo</a>');
                    $('#ms-boletos-gerados-dialog').click();
                    $('#arquivos').load('cobranca/arquivos.php');
                }

            }

            if(data.status == 'erro-permissao')
            {
                $('#msg-permissao-dialog').html(data.mensagem);
                $('#ms-permissao-modal').click();
            }


        }, 'json');

    });


    $('#ler').click(function(){

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

        $('#formLerRetorno').submit(function(){
            var dados = new FormData(this);

            $.ajax({
                url: 'cobranca/ler-arquivo.php',
                type: "POST",
                data: dados,
                processData: false,
                cache: false,
                contentType: false,
                dataType: 'json',
                success: function(data) {

                },
                error: function (request, status, error) {
                    //alert(request.responseText);
                },
                complete: function(data){

                    if (data.responseJSON.status == 'erro-caixa'){
                        $('#ms-erro-caixa-modal').click();
                    }

                    if(data.responseJSON.status == 'ok'){
                        $('#formLerRetorno')[0].reset();
                        $('#ms-retorno-importado-dialog').click();
                        $('#content').empty('').load('cobranca/cobranca.php');
                        //$('#resultado_retornos').load('cobranca/resultado_retornos.php');
                    }


                }
            });
            return false;
        });

    });


    /*Selecionar Todos*/
    $('#lista-selecionar-parcelas').on('click', '#selecionar-todos', function (){

        $('#lista-selecionar-parcelas .parcela').each(function(){
                if ($('#lista-selecionar-parcelas #selecionar-todos').prop( "checked")){
                    $(this).prop( "checked", true);
                } else {
                    $(this).prop("checked", false);
                }
            }
        );

    });


    /*
    $('#ler').click(function(){

        var formData = new FormData($('#formLerRetorno').prop('#retorno')[0]);

        $.ajax({
            url: 'cobranca/ler-arquivo.php',
            data: formData,
            type: 'post',
            success: function(response)
            {
                console.log(response)
            },
            processData: false,
            cache: false,
            contentType: false
        });

    });
    */

});
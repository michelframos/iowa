$(function(){

    $('#formNovosDados').submit(function(){return false});

    $("#data_inicial, #data_final").datetimepicker({
        format: "DD/MM/YYYY"
    });

    /*
    $('#taxa').maskMoney({sulfix:' %', allowNegative: true, thousands:'.', decimal:',', affixesStay: false});
    $('#multa').maskMoney({sulfix:' %', allowNegative: true, thousands:'.', decimal:',', affixesStay: false});
    $('#juros').maskMoney({sulfix:' %', allowNegative: true, thousands:'.', decimal:',', affixesStay: false});
    */

    $('#filtrar').click(function(){

        $('#lista-selecionar-parcelas').html('<div class="titulo size-1-5">Filtrando...</div>');

        var dados = $('#formParcelas').serialize();
        $('#lista-selecionar-parcelas').load('boletos/selecionar-parcelas.php', { dados: dados });

    });


    /*Pesquisa*/
    $('#valor_pesquisa').on('keypress', function(e){
        if(e.keyCode == 13)
        {
            $('#filtrar').click();
            e.preventDefault();
        }
    });


    /*Envio por email*/
    $('#selecionar-parcelas #enviar').click(function(){

        var boletos = '';

        $('.boleto:checked').each(function(){
            boletos+=$(this).val()+'|';
        });

        if(boletos == ''){

            $('#ms-enviar-dialog').click();

        } else{

            $('#ms-emails-dialog').click();

            var dados = 'acao=enviar-emails&boletos='+boletos;
            $.post('boletos/acoes.php', { dados: dados }, function(data){

                if(data.status == 'ok'){

                    var erros = '';
                    if(data.erros != ''){
                        erros += '<div>Erros no Envio:</div>';
                        data.erros.forEach(function (i, v){
                            erros += '<div>E-mail: '+i['email']+'</div>';
                        });
                    }

                    $('#erros-envio').html(erros);

                    $('#bt-terminou-envio').click();
                    $('#ms-boletos-enviados-dialog').click();
                    console.log('deu certo');
                } else if(data.status == 'erro'){
                    $('#bt-terminou-envio').click();
                    $('#ms-erro-envio-boletos-dialog').click();
                }

            }, 'json');

        }

    });


    /*Navegação dentro de Gestão de Boletos*/
    $('#lista-selecionar-parcelas').on('click', '.bt-renegociar', function(){

        var boleto = $(this).attr('boleto');
        $('#box-renegociar').load('boletos/renegociar.php', {boleto: boleto});
        $('#tab-renegociar').click();

    });

    $('#voltar').click(function(){

        $('#tab-selecionar-parcelas').click();

    });

    $('#renegociar').on('click', '#remover_acrescimos', function(){

        /*
        var valor_original = $('#renegociar #valor_original').val().replace(',','.');
        var desconto = $('#renegociar #desconto').val().replace(',','.');
        var total = parseFloat(valor_original)-parseFloat(desconto);
        total.replace();
        */

        //$('#renegociar #valor_parcela').val(parseFloat(valor_original)-parseFloat(desconto));
        $('#renegociar #valor_parcela').val($('#renegociar #total').val());
        $('#renegociar #juros').val('0');
        $('#renegociar #multa').val('0');
        $('#renegociar #acrescimo').val('0');
        $('#renegociar #importar_acrescimos').val('n');

    });

    $('#renegociar').on('click', '#gerar', function(){

        $('#renegociar #formNovosDados').find('input').each(function(){

            if($(this).attr('required') && $(this).val() == '')
            {
                exit;
            }

        });

        var dados = $('#renegociar #formNovosDados').serialize()+'&acao=gerar';
        $.post('boletos/acoes.php', { dados: dados }, function (data){

            if(data.status == 'ok'){
                $('#content').empty('').load('boletos/boletos.php');
            }
            else if(data.status == 'erro-permissao')
            {
                $('#msg-permissao-dialog').html(data.mensagem);
                $('#ms-permissao-modal').click();
            }

        }, 'json');


    });


    $('#arquivos').on('click', '.download', function(){

        $.post('boletos/baixar.php', { arquivo: $(this).attr('arquivo') }, function(data){
            location.href = data;
        });

    });


    /*Selecionar Todos*/
    $('#lista-selecionar-parcelas').on('click', '#selecionar-todos', function (){

        $('#lista-selecionar-parcelas .boleto').each(function(){
                if ($('#lista-selecionar-parcelas #selecionar-todos').prop( "checked")){
                    $(this).prop( "checked", true);
                } else {
                    $(this).prop("checked", false);
                }
            }
        );

    });


    /*Excluir Boleto*/

    $('#bt-excluir-boleto').click(function(){

        var boletos = '';

        $('.boleto:checked').each(function(){
            boletos+=$(this).val()+'|';
        });

        console.log(boletos);

        if(boletos == ''){

            $('#ms-selecao-boletos').click();

        } else{

            var dados = 'acao=excluir-boletos&boletos='+boletos;
            $.post('boletos/acoes.php', { dados: dados }, function(data){

                if(data.status == 'ok'){
                    $('#filtrar').click();
                }

            }, 'json');

        }

    });


});
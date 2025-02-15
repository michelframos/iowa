$(function(){

    $('#formDados').submit(function(){return false});
    $('#formPesquisa').submit(function(){return false});

    $('#msg-nao-exclusao').hide();
    $('#cnpj').mask('00.000.000/0000-00', {reverse: true});
    $('#cep').mask('00.000-000', {reverse: true});

    $('#valor_hora_aula_help').maskMoney({prefix:'R$ ', allowNegative: true, thousands:'.', decimal:',', affixesStay: false});

    var options =  {
        onKeyPress: function(telefone, e, field, options) {
            var masks = ['(00)0000-00000', '(00)00000-0000'];
            var mask = (telefone.length>13) ? masks[1] : masks[0];
            $('#telefone1, #telefone2').mask(mask, options);
        }};

    $('#telefone1, #telefone2').mask('(00)0000-00000', options);


    /*---------------------------------------------------------------------------------------------------------------*/
    /*---------------------------------------------------------------------------------------------------------------*/
    /*BUSCA CEP*/

    $('#busca-cep').click(function(){

        var cidade_selecionada = '';
        var cidade = '';
        var dados = '&cep='+$('#cep').val()+'&acao=busca-cep';
        $.post('../includes/busca-cep.php', { dados: dados }, function(data){

            if(data.status == 'ok')
            {
                $("#rua").val(data.endereco);
                $("#bairro").val(data.bairro);
                $("#complemento").val(data.complemento);
                $("#numero").val('');
                cidade_selecionada = data.cidade;
            }


            $("#estado option:contains("+data.uf+")").attr('selected', true);

            $.post('../includes/lista-cidades.php', {estado: $('#estado').val()}, function(data_cidade){

                console.log(cidade_selecionada);

                $('#cidade').html(data_cidade);
                cidade = data_cidade;

                if(cidade != '') {
                    $("#cidade option:contains(" + cidade_selecionada + ")").attr('selected', true);
                }

            });

        }, 'json');

    });


    $('#bt-novo').click(function(){

        var dados = 'acao=novo';
        $.post('unidades/acoes.php', { dados: dados }, function(data){
            if(data.status == 'ok')
            {
                $('#content').load('unidades/altera-unidade.php', {id: data.id});
            }
            else if(data.status == 'erro-permissao')
            {
                $('#msg-permissao-dialog').html(data.mensagem);
                $('#ms-permissao-modal').click();
            }
        }, 'json');

    });


    /*Pesquisa*/
    $('#valor').on('keypress', function(e){
        if(e.keyCode == 13)
        {
            $('#pesquisar').click();
        }
    });

    $('#pesquisar').click(function(){

        $('#listagem').html('<h2 class="h2 cinza">Pesquisando, aguarde...</h2>');

        $.post('unidades/listagem.php', { valor: $('#valor').val(), campo: $('#campo').val() }, function(data){
            $('#listagem').html(data);
        });

    });
    /*Pesquisa*/


    $('#bt-voltar').click(function(){
        $('#content').load('unidades/unidades.php');
    });



    $('#listagem').on('click', '.bt-altera', function(){
        $('#content').load('unidades/altera-unidade.php', {id: $(this).attr('registro')});
    });



    $('#salvar').click(function(){

        if($('#cnpj').val() != '')
        {
            if(!validaCnpj($('#cnpj').val())){
                $('#ms-cnpj-invalido-modal').click();
                exit;
            }
        }

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

        $('#ms-salvando-dialog').click();

        var dados = $('#formDados').serialize()+'&id='+$(this).attr('registro')+'&acao=salvar';

        $.post('unidades/acoes.php', { dados: dados }, function(data){

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




    $('#listagem').on('click', '.bt-excluir', function(){

        $('#bt-modal-excluir').attr('registro', $(this).attr('registro'));

    });




    $('#bt-modal-excluir').click(function(){

        var dados = 'id='+$(this).attr('registro')+'&acao=excluir';

        $.post('unidades/acoes.php', { dados: dados }, function(data){

            if(data.status == 'ok')
            {
                $('#content').load('unidades/unidades.php');
            }
            else if(data.status == 'erro-permissao')
            {
                $('#msg-permissao-dialog').html(data.mensagem);
                $('#ms-permissao-modal').click();
            }
            else if(data.status == 'erro')
            {
                $('#msg-nao-exclusao').show();
            }

        }, 'json');

    });




    $('#listagem').on('click', '.ativa-inativa', function(){

        var dados = 'id='+$(this).attr('registro')+'&acao=ativa-inativa';

        $.post('unidades/acoes.php', { dados: dados }, function(data){
            if(data.status == 'erro-permissao')
            {
                $('#msg-permissao-dialog').html(data.mensagem);
                $('#ms-permissao-modal').click();
            }
        }, 'json');

    });

    $('#listagem').on('click', '.usar-dados-boleto', function(){

        var dados = 'id='+$(this).attr('registro')+'&acao=usar-dados-boleto';

        $.post('unidades/acoes.php', { dados: dados }, function(data){
            if(data.status == 'erro-permissao')
            {
                $('#msg-permissao-dialog').html(data.mensagem);
                $('#ms-permissao-modal').click();
            }
            else if(data.status == 'erro')
            {
                $('#ms-erro-usar-dados-dialog').click();
            }
        }, 'json');

    });


    $('.desconto_ate_vencimento').click(function(){
        var dados = 'id='+$(this).attr('registro')+'&acao=desconto_ate_vencimento';

        $.post('unidades/acoes.php', { dados: dados }, function(data){});
    });


    $('.incluir_mora_multa').click(function(){
        var dados = 'id='+$(this).attr('registro')+'&acao=incluir_mora_multa';

        $.post('unidades/acoes.php', { dados: dados }, function(data){});
    });


    $('.protestar_atrasados').click(function(){
        var dados = 'id='+$(this).attr('registro')+'&acao=protestar_atrasados';

        $.post('unidades/acoes.php', { dados: dados }, function(data){});
    });


    $('.informar_descontos_adicionais').click(function(){
        var dados = 'id='+$(this).attr('registro')+'&acao=informar_descontos_adicionais';

        $.post('unidades/acoes.php', { dados: dados }, function(data){});
    });


    /*Busca Dados Banco*/
    $('#codigo_banco').change(function () {

        var id_unidade = $('#id').val();
        var codigo_banco = $(this).val();

        $.post(HOME()+'/scripts/busca-dados-banco', { id_unidade: id_unidade, codigo_banco: codigo_banco, acao: 'buscaDadosBanco' }, function (data) {
            $('#carteira').val(data.carteira);
            $('#especie').val(data.especie);
            $('#agencia').val(data.agencia);
            $('#conta').val(data.conta);
            $('#codigo_cliente').val(data.codigo_cliente);
            $('#juros').val(data.juros);
            $('#multa').val(data.multa);
        }, 'json');

    });

    $('#aba-dados-bancarios').click(function () {
        $('#codigo_banco').change();
    });

    /*VALIDAÇÕES*/
    /*Validação de CNPJ*/
    function validaCnpj(str){
        str = str.replace('.','');
        str = str.replace('.','');
        str = str.replace('.','');
        str = str.replace('-','');
        str = str.replace('/','');
        cnpj = str;
        var numeros, digitos, soma, i, resultado, pos, tamanho, digitos_iguais;
        digitos_iguais = 1;
        if (cnpj.length < 14 && cnpj.length < 15)
            return false;
        for (i = 0; i < cnpj.length - 1; i++)
            if (cnpj.charAt(i) != cnpj.charAt(i + 1))
            {
                digitos_iguais = 0;
                break;
            }
        if (!digitos_iguais)
        {
            tamanho = cnpj.length - 2;
            numeros = cnpj.substring(0,tamanho);
            digitos = cnpj.substring(tamanho);
            soma = 0;
            pos = tamanho - 7;
            for (i = tamanho; i >= 1; i--)
            {
                soma += numeros.charAt(tamanho - i) * pos--;
                if (pos < 2)
                    pos = 9;
            }
            resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
            if (resultado != digitos.charAt(0))
                return false;
            tamanho = tamanho + 1;
            numeros = cnpj.substring(0,tamanho);
            soma = 0;
            pos = tamanho - 7;
            for (i = tamanho; i >= 1; i--)
            {
                soma += numeros.charAt(tamanho - i) * pos--;
                if (pos < 2)
                    pos = 9;
            }
            resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
            if (resultado != digitos.charAt(1))
                return false;
            return true;
        }
        else
            return false;
    }

});
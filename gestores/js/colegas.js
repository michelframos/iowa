$(function(){

    $('#formDados').submit(function(){return false});
    $('#formPesquisa').submit(function(){return false});

    $('#msg-nao-exclusao').hide();

    var options =  {
        onKeyPress: function(telefone, e, field, options) {
            var masks = ['(00)0000-00000', '(00)00000-0000'];
            var mask = (telefone.length>13) ? masks[1] : masks[0];
            $('#telefone, #celular').mask(mask, options);
        }};

    $('#telefone, #celular').mask('(00)0000-00000', options);

    $('#cep').mask('00.000-000', {reverse: true});
    $('#data_nascimento').mask('00/00/0000', {reverse: true});

    /*$('#data_admissao').datetimepicker();*/

    $('#cpf').mask('000.000.000-00', {reverse: true});

    $('#dados1, #dados2, #dados3').hide();
    $('#funcao').change(function(){
        $('#dados1, #dados2, #dados3').hide();
        $('#dados'+$('#funcao option:selected').val()).show();
    });


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
                $("#endereco").val(data.endereco);
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
        $.post('colegas/acoes.php', { dados: dados }, function(data){
            if(data.status == 'ok')
            {
                $('#content').load('colegas/altera-colega.php', {id: data.id});
            }
            else if(data.status == 'erro-permissao')
            {
                $('#msg-permissao-dialog').html(data.mensagem);
                $('#ms-permissao-modal').click();
            }
        }, 'json');

    });


    /*Pesquisa*/
    $('#valor_pesquisa').on('keypress', function(e){
        if(e.keyCode == 13)
        {
            $('#pesquisar').click();
        }
    });

    $('#pesquisar').click(function(){

        $('#listagem').html('<h2 class="h2 cinza">Pesquisando, aguarde...</h2>');

        $.post('colegas/listagem.php', {
            valor_pesquisa: $('#valor_pesquisa').val(),
            funcao: $('#funcao').val(),
            unidade: $('#unidade').val(),
            status: $('#status').val()
        }, function(data){
            $('#listagem').html(data);
        });

    });
    /*Pesquisa*/


    $('#bt-voltar').click(function(){
        $('#content').load('colegas/colegas.php');
    });



    $('#listagem').on('click', '.bt-altera', function(){
        $('#content').load('colegas/altera-colega.php', {id: $(this).attr('registro')});
    });



    $('#salvar').click(function(){

        if($('#cpf').val() != '')
        {
            if(!validarCPF($('#cpf').val())){
                $('#ms-cpf-invalido-modal').click();
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

        $.post('colegas/acoes.php', { dados: dados }, function(data){

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

        $.post('colegas/acoes.php', { dados: dados }, function(data){

            if(data.status == 'ok')
            {
                $('#content').load('colegas/colegas.php');
            }
            else if(data.status == 'erro-permissao')
            {
                $('#msg-permissao-dialog').html(data.mensagem);
                $('#ms-permissao-modal').click();
            }
            else if(data.status == 'erro')
            {
                //$('#msg-nao-exclusao').show();
                $('#mensagem-modal').html(data.mensagem);
                $('#ms-nao-exclusao-modal').click();
            }

        }, 'json');

    });




    $('#listagem').on('click', '.ativa-inativa', function(){

        var dados = 'id='+$(this).attr('registro')+'&acao=ativa-inativa';

        $.post('colegas/acoes.php', { dados: dados }, function(data){
            if(data.status == 'erro-permissao')
            {
                $('#msg-permissao-dialog').html(data.mensagem);
                $('#ms-permissao-modal').click();
            }
        }, 'json');

    });


    /*VALIDÇÕES DE CPF E CNPJ*/
    /*Validação de CPF*/
    function validarCPF(strCPF){
        strCPF = strCPF.replace('.','');
        strCPF = strCPF.replace('.','');
        strCPF = strCPF.replace('.','');
        strCPF = strCPF.replace('-','');

        var Soma;
        var Resto;
        Soma = 0;

        if (strCPF == "00000000000") return false;

        for (i=1; i<=9; i++) Soma = Soma + parseInt(strCPF.substring(i-1, i)) * (11 - i);
        Resto = (Soma * 10) % 11;

        if ((Resto == 10) || (Resto == 11))  Resto = 0;
        if (Resto != parseInt(strCPF.substring(9, 10)) ) return false;

        Soma = 0;
        for (i = 1; i <= 10; i++) Soma = Soma + parseInt(strCPF.substring(i-1, i)) * (12 - i);
        Resto = (Soma * 10) % 11;

        if ((Resto == 10) || (Resto == 11))  Resto = 0;
        if (Resto != parseInt(strCPF.substring(10, 11) ) ) return false;
        return true;
    }

});
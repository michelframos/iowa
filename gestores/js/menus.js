$(function(){

    /*================================================================================================================*/
    /*Idiomas*/
    $('#menu-idiomas').click(function(){
        $('#content').load('idiomas/idiomas.php');
    });

    $('body').on('click', '#bt-voltar-idioma', function(){
        $('#content').load('idiomas/idiomas.php');


    }).on('click', '#bt-novo-idioma', function(){
        var dados = 'acao=novo';

        $.post('idiomas/verifica-duplicidade.php', { dados: dados }, function(data){
            if(data.status == 'ok')
            {
                $('#content').load('idiomas/altera-idioma.php', {id: data.id});
            }
            else if(data.status == 'erro-permissao')
            {
                $('#msg-permissao-dialog').html(data.mensagem);
                $('#ms-permissao-modal').click();
            }
        }, 'json');


    }).on('click', '.bt-altera-idioma', function(){
        $('#content').load('idiomas/altera-idioma.php', {id: $(this).attr('registro')});

    });

    /*================================================================================================================*/
    /*Nomes de Provas*/

    $('#menu-configuracao-emails').click(function(){
        $('#content').empty('').load('emails/emails.php');
    });

    $('#menu-inicio').click(function(){
        $('#content').empty('').load('inicio.php');
    });

    $('#menu-usuarios').click(function(){
        $('#content').empty('').load('usuarios/usuarios.php');
    });

    $('#menu-historico-acoes').click(function(){
        $('#content').empty('').load('historico-acoes/historico-acoes.php');
    });

    $('#menu-nomes-provas').click(function(){
        $('#content').empty('').load('nome-provas/nome-provas.php');
    });

    $('#menu-sistema-notas').click(function(){
        $('#content').empty('').load('sistema-notas/sistema-notas.php');
    });

    $('#menu-unidades').click(function(){
        $('#content').empty('').load('unidades/unidades.php');
    });

    $('#menu-perfis').click(function(){
        $('#content').empty('').load('perfis/perfis.php');
    });

    $('#menu-valores').click(function(){
        $('#content').empty('').load('valores-hora-aula/valores.php');
    });

    $('#menu-nomes-produtos').click(function(){
        $('#content').empty('').load('nomes-produtos/nomes-produtos.php');
    });

    $('#menu-programacao').click(function(){
        $('#content').empty('').load('programacao/programacao.php');
    });

    $('#menu-origem-aluno').click(function(){
        $('#content').empty('').load('origem-aluno/origem-aluno.php');
    });

    $('#menu-colegas').click(function(){
        //$('#content').empty('').load('colegas/colegas.php');

        $.ajax('colegas/colegas.php').done(function(data) {
            $("#content").html(data);
            return false;
        });
    });


    $('#menu-motivos-desistencia').click(function(){
        $('#content').empty('').load('motivos-desistencia/motivos-desistencia.php');
    });


    $('#menu-editor-documentos').click(function(){
        $('#content').empty('').load('editor/editor.php');
    });

    $('#menu-alunos').click(function(){
        //$('#content').empty('').load('alunos/alunos.php');

        $.ajax('alunos/alunos.php').done(function(data) {
            $("#content").html(data);
            return false;
        });
    });

    $('#menu-promocoes').click(function(){

        $.ajax('promocoes/promocoes.php').done(function(data) {
            $("#content").html(data);
            return false;
        });
    });

    $('#menu-empresas').click(function(){
        $('#content').empty('').load('empresas/empresas.php');
    });

    $('#menu-turmas').click(function(){
        //$('#content').empty('').load('turmas/turmas.php');

        $.ajax('turmas/turmas.php').done(function(data) {
            $("#content").html(data);
            return false;
        });

    });

    $('#menu-fornecedores').click(function(){
        $('#content').empty('').load('fornecedores/fornecedores.php');
    });

    $('#menu-categorias-lancamentos').click(function(){
        $('#content').empty('').load('categorias-lancamentos/categorias-lancamentos.php');
    });

    $('#menu-formas-recebimento').click(function(){
        $('#content').empty('').load('formas-recebimento/formas-recebimento.php');
    });

    $('#menu-natureza').click(function(){
        $('#content').empty('').load('natureza/natureza.php');
    });

    $('#menu-cobranca').click(function(){
        $('#content').empty('').load('cobranca/cobranca.php');
    });


    $('#menu-gestao-boletos').click(function(){
        $('#content').empty('').load('boletos/boletos.php');
    });

    $('#menu-caixas').click(function(){
        $('#content').empty('').load('caixas/caixas.php');
    });

    $('#menu-valor-original').click(function(){
        $('#content').empty('').load('valor-original/valor-original.php');
    });

    $('#menu-contas-receber').click(function(){
        $('#content').empty('').load('contas-receber/contas-receber.php');
    });

    $('#menu-contas-pagar').click(function(){
        $('#content').empty('').load('contas-pagar/contas-pagar.php');
    });





    $('#menu-relatorio-colegas').click(function(){
        $('#content').empty('').load('relatorios/colegas/colegas.php');
    });

    $('#menu-relatorio-folha-pagamento').click(function(){
        $('#content').empty('').load('relatorios/folha/folha.php');
    });


    $('#menu-relatorio-folha-unidade').click(function(){
        $('#content').empty('').load('relatorios/folha-unidade/folha-unidade.php');
    });

    $('#menu-relatorio-turmas').click(function(){
        //$('#content').empty('').load('relatorios/turmas/turmas.php');

        $.ajax('relatorios/turmas/turmas.php').done(function(data) {
            $("#content").html(data);
        });
    });


    $('#menu-relatorio-alunos-turmas').click(function(){
        //$('#content').empty('').load('relatorios/turmas/turmas.php');

        $.ajax('relatorios/alunos-turmas/alunos-turmas.php').done(function(data) {
            $("#content").html(data);
        });
    });

    $('#menu-relatorio-alunos-empresas').click(function(){
        //$('#content').empty('').load('relatorios/turmas/turmas.php');

        $.ajax('relatorios/alunos-empresas/alunos-empresas.php').done(function(data) {
            $("#content").html(data);
        });
    });

    $('#menu-relatorio-ocorrencias').click(function(){
        //$('#content').empty('').load('relatorios/turmas/turmas.php');

        $.ajax('relatorios/ocorrencias-aulas/ocorrencias-aulas.php').done(function(data) {
            $("#content").html(data);
        });
    });

    $('#menu-relatorio-frequencia').click(function(){
        //$('#content').empty('').load('relatorios/turmas/turmas.php');

        $.ajax('relatorios/frequencia/frequencia.php').done(function(data) {
            $("#content").html(data);
        });
    });

    $('#menu-relatorio-f7').click(function(){
        //$('#content').empty('').load('relatorios/turmas/turmas.php');

        $.ajax('relatorios/f7/f7.php').done(function(data) {
            $("#content").html(data);
        });
    });

    $('#menu-consolidado-faltas').click(function(){
        //$('#content').empty('').load('relatorios/turmas/turmas.php');

        $.ajax('relatorios/consolidado-faltas/consolidado-faltas.php').done(function(data) {
            $("#content").html(data);
        });
    });

    $('#menu-relatorio-contas').click(function(){
        //$('#content').empty('').load('relatorios/turmas/turmas.php');

        $.ajax('relatorios/contas/contas.php').done(function(data) {
            $("#content").html(data);
        });
    });

    $('#menu-relatorio-contas-receber').click(function(){
        //$('#content').empty('').load('relatorios/turmas/turmas.php');

        $.ajax('relatorios/contas-receber/contas.php').done(function(data) {
            $("#content").html(data);
        });
    });

    $('#menu-relatorio-faturamento').click(function(){
        //$('#content').empty('').load('relatorios/turmas/turmas.php');

        $.ajax('relatorios/faturamento/faturamento.php').done(function(data) {
            $("#content").html(data);
        });
    });

    $('#menu-relatorio-matriculas').click(function(){
        //$('#content').empty('').load('relatorios/turmas/turmas.php');

        $.ajax('relatorios/matriculas/matriculas.php').done(function(data) {
            $("#content").html(data);
        });
    });

    $('#menu-relatorio-matriculas-unidade').click(function(){
        //$('#content').empty('').load('relatorios/turmas/turmas.php');

        $.ajax('relatorios/matriculas-unidade/matriculas-unidade.php').done(function(data) {
            $("#content").html(data);
        });
    });

    $('#menu-relatorio-inativacao-alunos').click(function(){
        //$('#content').empty('').load('relatorios/turmas/turmas.php');

        $.ajax('relatorios/inativacoes/inativacoes.php').done(function(data) {
            $("#content").html(data);
        });
    });

    $('#menu-relatorio-helps').click(function(){
        //$('#content').empty('').load('relatorios/turmas/turmas.php');

        $.ajax('relatorios/helps/helps.php').done(function(data) {
            $("#content").html(data);
        });
    });

    $('#menu-renovacao').click(function(){
        //$('#content').empty('').load('relatorios/turmas/turmas.php');

        $.ajax('renovacao/renovacao.php').done(function(data) {
            $("#content").html(data);
        });
    });

    $('#menu-perfil-usuario').click(function(){
        //$('#content').empty('').load('relatorios/turmas/turmas.php');

        $.ajax('perfil-usuario/perfil-usuario.php').done(function(data) {
            $("#content").html(data);
        });
    });

    $('#menu-help').click(function(){
        //$('#content').empty('').load('relatorios/turmas/turmas.php');

        $.ajax('helps/helps.php').done(function(data) {
            $("#content").html(data);
        });
    });

    $('#menu-coachs').click(function(){
        //$('#content').empty('').load('relatorios/turmas/turmas.php');

        $.ajax('coachs/coachs.php').done(function(data) {
            $("#content").html(data);
        });
    });


    $('#menu-central-conhecimento').click(function(){
        //$('#content').empty('').load('relatorios/turmas/turmas.php');

        $.ajax('conhecimentos/conhecimentos.php').done(function(data) {
            $("#content").html(data);
        });
    });


    $('#menu-relatorio-aniversariantes').click(function(){
        //$('#content').empty('').load('relatorios/turmas/turmas.php');

        $.ajax('relatorios/aniversariantes/aniversariantes.php').done(function(data) {
            $("#content").html(data);
        });
    });


    $('#menu-aluno-material').click(function(){
        //$('#content').empty('').load('relatorios/turmas/turmas.php');

        $.ajax('relatorios/aluno-material/aluno-material.php').done(function(data) {
            $("#content").html(data);
        });
    });

    $('#menu-alunos-unidade').click(function(){
        //$('#content').empty('').load('relatorios/turmas/turmas.php');

        $.ajax('relatorios/alunos-unidade/alunos-unidade.php').done(function(data) {
            $("#content").html(data);
        });
    });


    $('#menu-logins').click(function(){
        //$('#content').empty('').load('relatorios/turmas/turmas.php');

        $.ajax('relatorios/logins/logins.php').done(function(data) {
            $("#content").html(data);
        });
    });


    $('#menu-whatsapp').click(function(){
        //$('#content').empty('').load('relatorios/turmas/turmas.php');

        $.ajax('whatsapp/autorizar.php').done(function(data) {
            $("#content").html(data);
        });
    });


    $('#menu-email-marketing').click(function(){
        $.ajax('relatorios/email-marketing/email-marketing.php').done(function(data) {
            $("#content").html(data);
        });
    });


});

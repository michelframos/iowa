$(function () {

    /*ALTERAR DIA*/
    $('#bt_alterar_dia').click(function () {

        $('.pmd-switch').each(function (i, v) {
            $(v).removeClass('anula-click');
        });

        $('.horario-dia').each(function (i, v) {
            $(v).removeClass('anula-click');
        });

        $('#salvar_alterar_dia').removeClass('oculto');
        //$('#salvar').addClass('oculto');
    });

    $('#salvar_alterar_dia').click(function () {

        $('#ms-salvando-dialog').click();

        var dados = $('#formDados').serialize()+'&acao=alterar-dia';
        $.post('turmas/acoes-botoes.php', { dados: dados },function (data) {

            if(data.status == 'ok'){

                $('.pmd-switch').each(function (i, v) {
                    $(v).addClass('anula-click');
                });

                $('.horario-dia').each(function (i, v) {
                    $(v).addClass('anula-click');
                });

                $('#bt-salvou').click();
                $('#ms-ok-modal').click();

                //$('#salvar').removeClass('oculto');
                $('#salvar_alterar_dia').addClass('oculto');

            }

        }, 'json');

    });


    /*ALTERAR PROGRAMAÇÃO*/
    $('#bt_programacao').click(function () {

        $('#id_produto').removeClass('anula-click');
        $('#salvar_programacao').removeClass('oculto');

    });

    $('#salvar_programacao').click(function () {

        $('#ms-salvando-dialog').click();
        var dados = $('#formDados').serialize()+'&acao=alterar-programacao';

        $.post('turmas/acoes-botoes.php', { dados: dados }, function (data) {

            if(data.status == 'ok'){

                $('#id_produto').addClass('anula-click');
                $('#salvar_programacao').addClass('oculto');

                $('#bt-salvou').click();
                $('#ms-ok-modal').click();

                $('#log-detalhes').append('' +
                    '<div>Programação Atual: '+data.log_detalhes.programacao_atual+'</div>' +
                    '<div>Horas do Estágio: '+data.log_detalhes.horas_estagio_atual+'</div>'+
                    '<div>Horas Semanais: '+data.log_detalhes.horas_semainais_atual+'</div><br>' +

                    '<div>Nova Programação: '+data.log_detalhes.nova_programacao+'</div>' +
                    '<div>Horas do Estágio: '+data.log_detalhes.horas_estagio_nova+'</div>'+
                    '<div>Horas Semanais: '+data.log_detalhes.horas_semainais_nova+'</div><br>'+

                    '<div>Horas Dadas: '+data.log_detalhes.horas_dadas+'</div>'+
                    '<div>Horas Restantes: '+data.log_detalhes.horas_restantes+'</div>'+
                    '<div>Número de Aulas Restantes: '+data.log_detalhes.numero_aulas+'</div>'
                );

                $.each(data.log_aulas, function (index, value) {
                    $('#log-aulas').append('<div>Aula: '+value.numero_aula+' Hora Início: '+value.hora_inicio+' Hora Término: '+value.hora_termino+' Data: '+value.data+' Horas Dadas: '+value.horas_dadas+'</div>');
                });

            }

        }, 'json');

    });


    /*ALTERAR HORÁRIO*/
    $('#bt_horario').click(function () {

        $('.horario-dia').each(function (i, v) {
            $(v).removeClass('anula-click');
        });

        $('#salvar_horario').removeClass('oculto');

    });

    $('#salvar_horario').click(function () {

        $('#ms-salvando-dialog').click();

        var dados = $('#formDados').serialize()+'&acao=alterar-horario';
        $.post('turmas/acoes-botoes.php', { dados: dados }, function (data) {
            if(data.status == 'ok'){

                $('.horario-dia').each(function (i, v) {
                    $(v).addClass('anula-click');
                });

                $('#salvar_horario').addClass('oculto');

                $('#bt-salvou').click();
                $('#ms-ok-modal').click();

            }
        }, 'json');

    });

    /*ALTERAR INSTRUTOR*/
    $('#bt_instrutor').click(function () {

        $('#id_colega').removeClass('anula-click');
        $('#salvar_instrutor').removeClass('oculto');

    });

    $('#salvar_instrutor').click(function () {

        $('#ms-salvando-dialog').click();

        var dados = $('#formDados').serialize()+'&acao=alterar-instrutor';
        $.post('turmas/acoes-botoes.php', { dados: dados }, function (data) {

            if(data.status == 'ok'){

                $('#id_colega').addClass('anula-click');
                $('#salvar_instrutor').addClass('oculto');

                $('#bt-salvou').click();
                $('#ms-ok-modal').click();

            }

        }, 'json');

    });

    /*ALTERAR VALOR HORA AULA*/
    $('#bt_alterar_valor_hora_aula').click(function () {

        $('#id_valor_hora_aula').removeClass('anula-click');
        $('#salvar_valor_hora_aula').removeClass('oculto');

    });

    $('#salvar_valor_hora_aula').click(function () {

        $('#ms-salvando-dialog').click();

        var dados = $('#formDados').serialize()+'&acao=alterar-valor-hora-aula';
        $.post('turmas/acoes-botoes.php', { dados: dados }, function (data) {

            if(data.status == 'ok'){

                $('#id_valor_hora_aula').addClass('anula-click');
                $('#salvar_valor_hora_aula').addClass('oculto');

                $('#bt-salvou').click();
                $('#ms-ok-modal').click();

            }

        }, 'json');

    });

    /*ALTERAR SISTEMA DE NOTAS*/
    $('#bt_alterar_sistema_notas').click(function () {

        $('#id_sistema_notas').removeClass('anula-click');
        $('#salvar_valor_sistema_notas').removeClass('oculto');

    });

    $('#salvar_valor_sistema_notas').click(function () {

        $('#ms-salvando-dialog').click();

        var dados = $('#formDados').serialize()+'&acao=alterar-sistema-notas';
        $.post('turmas/acoes-botoes.php', { dados: dados }, function (data) {

            if(data.status == 'ok'){

                $('#id_sistema_notas').addClass('anula-click');
                $('#salvar_valor_sistema_notas').addClass('oculto');

                $('#bt-salvou').click();
                $('#ms-ok-modal').click();

            }

        }, 'json');

    });

});
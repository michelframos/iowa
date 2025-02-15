$(function (){

    $('#bt-voltar').click(function (){
        $('#content').load('inicio.php');
    });

    $('#bt-voltar-alunos').click(function (){
        $('#content').load('alunos/alunos.php', { id_turma: $(this).attr('id_turma') });
    });

    $('.ver-dados').click(function (){
        $('#content').load('alunos/outros_dados.php', { id_turma: $(this).attr('id_turma'), id_aluno: $(this).attr('id_aluno') });
    });

});
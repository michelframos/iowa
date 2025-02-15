$(function(){

    $('.ver-alunos').click(function (){
        $('#content').load('alunos/alunos.php', { id_turma: $(this).attr('turma') });
    });

});

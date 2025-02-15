$(function(){

    $('#menu-perfil').click(function(){
        $('#content').load('perfil/perfil.php');
    });

    $('#menu-inicio').click(function(){
        $('#content').load('inicio.php');
    });

    $('#menu-mensalidades').click(function(){
        $('#content').load('mensalidades/mensalidades.php');
    });

});

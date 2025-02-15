$(function(){

    $('#menu-inicio').click(function(){
        $('#content').load('inicio.php');
    });

    $('#menu-perfil').click(function(){
        $('#content').load('perfil/perfil.php');
    });

    $('#menu-rel-faltas').click(function(){
        $('#content').load('relatorios/relatorio-faltas/relatorio-faltas.php');
    });

    $('#menu-consolidado-faltas').click(function(){
        $('#content').load('relatorios/consolidado-faltas/consolidado-faltas.php');
    });

});

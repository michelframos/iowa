$(function (){

    $('#salvar').click(function(){

        var senha = $('#senha').val();
        var confirma_senha = $('#confirma_senha').val();

        if(senha == ''){
            alert('Informe a nova senha');
            return false;
        }

        if(confirma_senha == ''){
            alert('Confirme a nova senha');
            return false;
        }

        if(senha != confirma_senha){
            alert('A senha e a confirmação não estão iguais.');
            return false;
        }

    });

});

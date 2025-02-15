<?php
    $aluno = Alunos::find($envio->id_aluno);

    $participacao = new Participacoes_Promocoes();
    $participacao->id_envio_promocao = $envio->id;
    $participacao->nome_participante = $aluno->nome;
    $participacao->email_participante = $aluno->email1;
    $participacao->telefone_participante = $aluno->celular;
    $participacao->data_participacao = date('Y-m-d H:i:s');
    $participacao->save();

    $envio->utilizado = 's';
    $envio->save();

?>

<div style="position: absolute; width: 100%; height: 100%; display: flex; justify-content: center; align-items: center;">
    <div>
        <div>
            <div class="texto-centro"><img src="assets/images/logoiowa.png" width="100"/> </div>
            <div class="espaco20"></div>
            <p class="texto-branco texto-centro size-1-5 texto">Olá <?php echo $aluno->nome ?>, obrigado por participar de nossa promoçao!</p>
        </div>
    </div>

</div>
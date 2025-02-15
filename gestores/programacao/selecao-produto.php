<?php
    include_once('../../config.php');
    include_once('../funcoes_painel.php');
?>
<script src="js/programacao.js"></script>


<!-- Start Content -->
<div class="espaco20"></div>
<div class="titulo">
    <i class="material-icons texto-laranja pmd-md">assignment</i>
    <h1>Selecione o Produto Desejado</h1>
</div>

<section class="pmd-card pmd-z-depth padding-10">

    <div class="espaco20"></div>
    <a href="javascript:void(0);" class="btn btn-danger pmd-btn-raised" id="bt-voltar">Voltar</a>
    <div class="espaco20"></div>


    <?php
    $produtos = Nomes_Produtos::all(array('conditions' => array('status = ? and programacao = ?', 'a', 'n'), 'order' => 'nome_material asc'));
    if(!empty($produtos)):
        foreach($produtos as $produto):
    ?>
        <div class="coluna-4">
            <!--Title, Media, Description and Action area -->
            <!-- Inverse card -->
            <div class="pmd-card pmd-card-inverse pmd-z-depth">

                <!-- Card media -->
                <div class="pmd-card-media">
                    <img class="img-responsive" src="<?php echo HOME ?>/assets/imagens/img-produto.png" style="width: 100%;">
                </div>

                <div class="pmd-card-title">
                    <h2 class="pmd-card-title-text"><?php echo $produto->nome_material ?></h2>
                    <span class="pmd-card-subtitle-text"><?php echo $produto->horas_semanais.' Hora(s) Por Semana'; ?></span>
                </div>

                <!-- Card action -->
                <div class="pmd-card-actions">
                    <button type="button" class="btn btn-sm pmd-btn-fab pmd-btn-flat pmd-ripple-effect btn-primary cria-programacao" registro="<?php echo $produto->id; ?>"><i class="material-icons pmd-sm">thumb_up</i></button>
                </div>
            </div>
        </div>
    <?php
        endforeach;

    else:
    ?>

        <ul class="aviso">
            <li class="icone-aviso"><i class="material-icons texto-laranja pmd-md">info</i></li>
            <li class="texto-aviso">Desculpe! Não existem produtos ha serem selecionados.</li>
        </ul>

    <?php
    endif;
    ?>

    <div class="espaco20"></div>

</section>

<script>
    $('#myModal').on('hidden.bs.modal', function (e) {
        // do something...
    })
</script>
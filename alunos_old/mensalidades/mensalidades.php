
<!-- Start Content -->
<div class="espaco20"></div>
<div class="titulo">
    <i class="material-icons texto-laranja pmd-md">confirmation_number</i>
    <h1>Mensalidades</h1>
</div>

<section class="pmd-card pmd-z-depth padding-10">

    <!-- --------------------------------------------------------------------------------------------------- -->
    <!-- Inicio Abas -->
    <div class="pmd-card pmd-z-depth">
        <div class="pmd-tabs pmd-tabs-bg">
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#mensalidade-abertas" aria-controls="home" role="tab" data-toggle="tab">Mensalidade em Aberto</a></li>
                <li role="presentation"><a href="#mensalidade-pagas" aria-controls="home" role="tab" data-toggle="tab">Mensalidade Pagas e Canceladas</a></li>
            </ul>
        </div>

        <div class="pmd-card-body">
            <div class="tab-content">

                <!-- --------------------------------------------------------------------------------------- -->
                <!-- Conteúdo de Uma Aba -->
                <div role="tabpanel" class="tab-pane active" id="mensalidade-abertas">

                    <?php include_once('listagem-parcelas.php'); ?>

                </div>
                <!-- Conteúdo de Uma Aba -->
                <!-- --------------------------------------------------------------------------------------- -->

                <!-- --------------------------------------------------------------------------------------- -->
                <!-- Conteúdo de Uma Aba -->
                <div role="tabpanel" class="tab-pane" id="mensalidade-pagas">

                    <?php include_once('listagem-parcelas-pagas.php'); ?>

                </div>
                <!-- Conteúdo de Uma Aba -->
                <!-- --------------------------------------------------------------------------------------- -->

            </div>
        </div>

    </div>

</section>
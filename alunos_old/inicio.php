<?php
include_once('../config.php');
include_once('funcoes_painel.php');
$aluno = Alunos::find(idAluno());
?>

<!-- Start Content -->
<section class="padding-10">

    <h1 class="headline">Olá <?php echo $aluno->nome ?></h1>
    <p>Seja bem-vindo à Área do Aluno IOWA.</p>

    <div class="flex">

        <!-- Media, Title, and Description area -->
        <div class="pmd-card pmd-card-default pmd-z-depth coluna-3">

            <!-- Card header -->
            <div class="pmd-card-title">
                <h2 class="pmd-card-title-text">Minhas Mensalidade</h2>
                <span class="pmd-card-subtitle-text">Consulta todos a suas mensalidade de cada matrícula.</span>
            </div>

            <!-- Card action -->
            <div class="pmd-card-actions">
                <button class="btn pmd-btn-raised pmd-ripple-effect btn-danger" type="button" id="bt-mensalidades">Ver Mensalidade</button>
            </div>
        </div>

    </div>

</section>
<div class="pmd-sidebar-overlay"></div>

<script src="js/inicio.js"></script>
<?php
include_once('../config.php');
include_once('funcoes_painel.php');
$empresa = Empresas::find(idEmpresa());
$estagio = filter_input(INPUT_POST, 'estagio', FILTER_SANITIZE_STRING);

/*
$turmas = Turmas::find_by_sql("
    select
    *
    from
    matriculas
    inner join empresas on matriculas.id_empresa_pedagogico = empresas.id
    inner join turmas on matriculas.id_turma = turmas.id
    where
    matriculas.id_empresa_pedagogico = '".idEmpresa()."'
    and matriculas.status = '".$estagio."'
    group by turmas.id
    order by turmas.nome asc;
");
*/

$turmas = Turmas::find_by_sql("select turmas.id as id_turma, turmas.id_unidade, turmas.nome, turmas.status, matriculas.* from matriculas inner join turmas on matriculas.id_turma = turmas.id where responsavel_pedagogico = 2 and id_empresa_pedagogico = '".idEmpresa()."' and turmas.status = '".$estagio."' GROUP BY turmas.id;")

?>

<!-- Start Content -->
<section class="padding-10">

    <h1 class="headline">Olá <?php echo $empresa->nome_fantasia ?></h1>
    <p>Seja bem-vindo à Área da Empresa</p>
    <p>Aqui você poderá visualizar as turmas das quais seus funcionários fazem parte, bem como sua frequencia, numero de faltas e datas de cada falta. Poderá visualizar também as faltas que foram abonadas pelos gestores e suas justificativas.</p>
    <div class="espaco20"></div>

    <!-- Form de Pesquisa -->
    <form action="" name="formPesquisa" id="formPesquisa" method="post">

        <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed coluna-3">
            <select name="estagio" id="estagio" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                <option <?php echo $estagio == 'a' ? 'selected' : '' ?> value="a">Estágios Atuais</option>
                <option <?php echo $estagio == 'i' ? 'selected' : '' ?> value="i">Estágios Anteriores</option>
            </select>
            <span class="select2 select2-container select2-container--bootstrap select2-container--open select2-container--focus select2-container--above" dir="ltr" style="width: 236px;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="true" tabindex="0" aria-labelledby="select2-akzg-container" aria-owns="select2-akzg-results" aria-activedescendant="select2-akzg-result-uq2y-Detroit Lions"><span class="select2-selection__rendered" id="select2-akzg-container" title=""></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span><span class="pmd-textfield-focused"></span>
        </div>
        <div class="clear"></div>

        <button type="submit" name="gerar-relatorio" id="gerar-relatorio" value="Gerar Relatório" class="btn btn-info pmd-btn-raised">Visualizar</button>
        <div class="espaco20"></div>
    </form>
    <!-- Form de Pesquisa -->

    <div class="flex">

        <?php
        if(!empty($turmas)):
            foreach ($turmas as $turma):
                $unidade = Unidades::find($turma->id_unidade);
                $alunos_turma = Alunos::find_by_sql("
                    select 
                    * 
                    from 
                    matriculas 
                    inner join empresas on matriculas.id_empresa_pedagogico = empresas.id 
                    inner join turmas on matriculas.id_turma = turmas.id
                    where
                    matriculas.id_empresa_pedagogico = '".idEmpresa()."'
                    /*and (matriculas.status = 'a' || matriculas.status = 't')*/
                    and (matriculas.status = 'a')
                    and turmas.id = '{$turma->id_turma}'
                ");

                echo "
                <div class=\"pmd-card pmd-card-default pmd-z-depth coluna-3\">

                    <!-- Card header -->
                    <div class=\"pmd-card-title\">
                        <h2 class=\"pmd-card-title-text\">{$turma->nome}</h2>
                        <span class=\"pmd-card-subtitle-text\">{$unidade->nome_fantasia}</span><br>
                        <span class=\"pmd-card-subtitle-text\">".count($alunos_turma)." alunos</span>
                    </div>
        
                    <!-- Card action -->
                    <div class=\"pmd-card-actions\">
                        <button class=\"btn pmd-btn-raised pmd-ripple-effect btn-danger ver-alunos\" type=\"button\" turma='{$turma->id_turma}'>Ver Alunos</button>
                    </div>
                </div>
                ";
            endforeach;
        endif;
        ?>

    </div>

</section>
<div class="pmd-sidebar-overlay"></div>

<script src="js/inicio.js"></script>

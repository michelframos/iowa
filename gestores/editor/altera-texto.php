<?php
    include_once('../../config.php');
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $registro = Textos::find($id);
?>

<script src="js/textos.js"></script>
<script src="js/ckeditor/ckeditor.js"></script>

<div tabindex="-1" class="modal fade" id="duplicidade-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Registro Duplicado</h2>
            </div>
            <div class="modal-body">
                <p>Já existe um Idioma com este nome.</p>
            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-danger" type="button">OK</button>
            </div>
        </div>
    </div>
</div>


<div tabindex="-1" class="modal fade" id="alterado-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Alterações</h2>
            </div>
            <div class="modal-body">
                <p>Alterações salvas com sucesso.</p>
            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-primary" type="button">OK</button>
            </div>
        </div>
    </div>
</div>


<div tabindex="-1" class="modal fade" id="permissao-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Permissão Negada</h2>
            </div>
            <div class="modal-body">
                <p id="msg-permissao-dialog"></p>
            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-primary" type="button">OK</button>
            </div>
        </div>
    </div>
</div>

<!-- Start Content -->

    <div class="espaco20"></div>
    <div class="titulo">
        <i class="material-icons texto-laranja pmd-md">edit</i>
        <h1>Cadastro / Alteração de Texto</h1>
    </div>

    <section class="pmd-card pmd-z-depth padding-10">

        <div class="espaco20"></div>
        <a href="javascript:void(0);" class="btn btn-danger pmd-btn-raised" id="bt-voltar">Voltar</a>
        <div class="espaco20"></div>

        <form action="" name="formDados" id="formDados" method="post" style="max-width: 1000px;">

            <div class="form-group pmd-textfield pmd-textfield-floating-label">
                <label for="regular1" class="control-label">Título do Texto</label>
                <input type="text" name="titulo" id="titulo" value="<?php echo $registro->titulo ?>" class="form-control" required><span class="pmd-textfield-focused"></span>
            </div>

            <div class="dropdown pmd-dropdown">
                <a id="dLabel" data-target="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                    Inserir campo no texto
                    <span class="caret"></span>
                </a>

                <div class="pmd-dropdown-menu-container"><div class="pmd-dropdown-menu-bg"></div>
                    <ul class="dropdown-menu pm-ini" aria-labelledby="dLabel" style="clip: rect(0px, 0px, 0px, 0px);">
                        <li><a class="dropdown-item" campo="{{LoginAluno}}">Login do ALuno</a></li>
                        <li><a class="dropdown-item" campo="{{NomeResponsavel}}">Nome do Responsável</a></li>
                        <li><a class="dropdown-item" campo="{{ProfissaoResponsavelFinanceiro}}">Profissão do Responsável</a></li>
                        <li><a class="dropdown-item" campo="{{RGResponsavel}}">RG do Responsável</a></li>
                        <li><a class="dropdown-item" campo="{{CPFResponsavel}}">CPF do Responsável</a></li>
                        <li><a class="dropdown-item" campo="{{EnderecoResponsavel}}">Endereço do Responsável</a></li>
                        <li><a class="dropdown-item" campo="{{CompEnderecoResponsavel}}">Complemento Endereço do Responsável</a></li>
                        <li><a class="dropdown-item" campo="{{CidadeResponsavel}}">Cidade do Responsável</a></li>
                        <li><a class="dropdown-item" campo="{{EstadoResponsavel}}">Estado do Responsável do Responsável</a></li>
                        <li><a class="dropdown-item" campo="{{DataAtual}}">Data Atual</a></li>
                        <li><a class="dropdown-item" campo="{{DataAtualExtenso}}">Data Atual por Extenso</a></li>
                    </ul>
                </div>
            </div>
            <div class="espaco20"></div>

            <div class="form-group pmd-textfield pmd-textfield-floating-label">
                <textarea class="form-control" name="texto" id="texto" style="height: 500px;"><?php echo $registro->texto ?></textarea>
            </div>

            <button type="submit" name="salvar" id="salvar" value="Salvar" registro="<?php echo $registro->id ?>" class="btn btn-info pmd-btn-raised">Salvar</button>
            <div class="espaco20"></div>

            <div class="oculto" id="ms-dp-modal" data-target="#duplicidade-dialog" data-toggle="modal"></div>
            <div class="oculto" id="ms-ok-modal" data-target="#alterado-dialog" data-toggle="modal"></div>
            <div class="oculto" id="ms-permissao-modal" data-target="#permissao-dialog" data-toggle="modal"></div>

        </form>

    </section>

    <script>
        // Replace the <textarea id="editor1"> with a CKEditor
        // instance, using default configuration.
        CKEDITOR.replace('texto');

        $(function(){

            $('.dropdown-item').click(function(){

               var campo = $(this).attr('campo');
               CKEDITOR.instances.texto.insertText(campo);

            });

        });
    </script>
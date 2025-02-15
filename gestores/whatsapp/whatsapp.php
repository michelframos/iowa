<?php
    include_once('../../config.php');
    include_once('../funcoes_painel.php');

    /*Verificando Permissões*/
    //verificaPermissao(idUsuario(), 'Relatório - Frequencia', 'c', 'index');

?>

<div tabindex="-1" class="modal fade" id="envio-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Enviando</h2>
            </div>
            <div class="modal-body">
                <p>Enviando Mensagens, por favor aguarde...</p>
            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right oculto">
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-primary oculto" type="button" id="bt-enviou">Cancelar</button>
            </div>
        </div>
    </div>
</div>


<div tabindex="-1" class="modal fade" id="envio-ok-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Mensagens Enviada</h2>
            </div>
            <div class="modal-body">
                <p>Mensagens enviadas com sucesso!</p>
            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right">
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-danger" type="button">OK</button>
            </div>
        </div>
    </div>
</div>

<script src="js/whatsapp.js"></script>

<!-- Start Content -->
<div class="espaco20"></div>
<div class="titulo">
    <i class="material-icons texto-laranja pmd-md">description</i>
    <h1>WhatsApp</h1>
</div>

<section class="pmd-card pmd-z-depth padding-10">

    <!-- Form de Pesquisa -->
    <form action="" name="formPesquisa" id="formPesquisa" method="post">
        <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed coluna-4">
            <label>Unidade</label>
            <select name="id_unidade" id="id_unidade" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                <option value="">Todas</option>
                <?php
                $unidades = Unidades::all(array('conditions' => array('status = ?', 'a'), 'order' => 'nome_fantasia asc'));
                if(!empty($unidades)):
                    foreach($unidades as $unidade):
                        echo '<option value="'.$unidade->id.'">'.$unidade->nome_fantasia.'</option>';
                    endforeach;
                endif;
                ?>
            </select>
        </div>

        <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed coluna-4">
            <label>Idioma</label>
            <select name="id_idioma" id="id_idioma" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                <option value="">Todos</option>
                <?php
                $idiomas = Idiomas::all(array('conditions' => array('status = ?', 'a'), 'order' => 'idioma asc'));
                if(!empty($idiomas)):
                    foreach($idiomas as $idioma):
                        echo '<option value="'.$idioma->id.'">'.$idioma->idioma.'</option>';
                    endforeach;
                endif;
                ?>
            </select>
        </div>

        <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed coluna-4">
            <label>Turma</label>
            <select name="id_turma" id="id_turma" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                <option value="">Todas</option>
                <?php
                $turmas = Turmas::all(array('order' => 'nome asc'));
                if(!empty($turmas)):
                    foreach($turmas as $turma):
                        echo '<option value="'.$turma->id.'">'.$turma->nome.'</option>';
                    endforeach;
                endif;
                ?>
            </select>
        </div>

        <div class="form-group pmd-textfield pmd-textfield-floating-label pmd-textfield-floating-label-completed coluna-4">
            <label>Tipo</label>
            <select name="tipo" id="tipo" class="select-simple form-control pmd-select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                <option value="">Todos</option>
                <option value="aluno">Aluno</option>
                <option value="responsavel">Responsável</option>
            </select>
        </div>
        <div class="clear"></div>

        <div class="form-group pmd-textfield pmd-textfield-floating-label">
            <label for="regular1" class="control-label">Nome do Aluno</label>
            <input type="text" name="nome" id="nome" value="" class="form-control"><span class="pmd-textfield-focused"></span>
        </div>
        <div class="clear"></div>

        <button type="button" name="pesquisar" id="pesquisar" value="Pesquisar" class="btn btn-info pmd-btn-raised">Pesquisar</button>
        <div class="espaco20"></div>
    </form>
    <!-- Form de Pesquisa -->

</section>

<section class="pmd-card pmd-z-depth padding-10">

    <div id="contatos" style="max-height: 350px; overflow: auto;"></div>

</section>

<section class="pmd-card pmd-z-depth padding-10">

    <form action="" method="post" name="formMensagem" id="formMensagem" enctype="multipart/form-data">

        <div class="form-group pmd-textfield pmd-textfield-floating-label">
            <label for="regular1" class="control-label">Código do País</label>
            <input type="text" name="codigo_pais" id="codigo_pais" value="55" class="form-control"><span class="pmd-textfield-focused"></span>
        </div>
        <div class="espaco20"></div>

        <div class="form-group pmd-textfield pmd-textfield-floating-label">
            <input type="file" class="form-control" name="arquivo" id="arquivo"/>
        </div>
        <div class="espaco20"></div>

        <div class="form-group pmd-textfield pmd-textfield-floating-label">
            <textarea class="form-control" name="mensagem" id="mensagem" rows="15" placeholder="Digite aqui sua mensagem"></textarea>
        </div>

        <button type="button" name="enviar" id="enviar" value="Enviar Mensagem" class="btn btn-info pmd-btn-raised">Enviar Mensagem</button>
        <button type="button" name="enviar" id="desconectar" value="Sair" class="btn btn-info pmd-btn-raised">Desconectar WhatsApp</button>
        <div class="espaco20"></div>

    </form>

</section>

<div class="oculto" id="ms-envio-dialog" data-target="#envio-dialog" data-toggle="modal"></div>
<div class="oculto" id="ms-envio-ok-dialog" data-target="#envio-ok-dialog" data-toggle="modal"></div>

<script type="text/javascript">
    $("#data_inicial, #data_final").datetimepicker({
        format: "DD/MM/YYYY"
    });
</script>
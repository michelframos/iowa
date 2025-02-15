<script src="js/whatsapp-autorizar.js"></script>

<!-- Start Content -->
<div class="espaco20"></div>
<div class="titulo">
    <i class="material-icons texto-laranja pmd-md">description</i>
    <h1>WhatsApp</h1>
</div>

<section class="pmd-card pmd-z-depth padding-10">

    <div class="espaco20"></div>
    <div id="img-autorizacao" class="texto-centro">Clique em verificar status</div>
    <div class="espaco20"></div>

    <div class="texto-centro">
        <button type="button" name="status" id="status" value="Verificar Status" class="btn btn-info pmd-btn-raised">Veriricar Status</button>
    </div>

</section>

<div class="oculto" id="chama-modal-conectando-dialog" data-target="#conectando-dialog" data-toggle="modal"></div>

<div tabindex="-1" class="modal fade" id="conectando-dialog" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="pmd-card-title-text">Verificando</h2>
            </div>
            <div class="modal-body">
                <p>Verificando status do WhatsApp no sistema, por favor aguarde, este processo pode demorar um pouco.</p>
            </div>
            <div class="pmd-modal-action pmd-modal-bordered text-right oculto">
                <button data-dismiss="modal" class="btn pmd-btn-raised pmd-ripple-effect btn-primary oculto" type="button" id="bt-concectou">Cancelar</button>
            </div>
        </div>
    </div>
</div>
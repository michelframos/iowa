/*
btSalvarPerfil = document.querySelector('#salvar_perfil');
btSalvarPerfil.addEventListener('click', async event => {

    let continuar = true;


    let campos = [
        'caracteristicas',
        'objetivo',
        'historico',
        'promessa',
    ];

    for(let i = 0; i < campos.length; i++){
        if(document.querySelector(`#${campos[i]}`).value === ''){
            continuar = false;
        }
    }

    if(!continuar){
        let modal = document.querySelector('#mensagem-dialog');
        modal.querySelector('#titulo-modal').innerHTML = 'Erro';
        modal.querySelector('#mensagem-modal').innerHTML = 'Todos os campos do perfil são obrigatórios.';
        document.querySelector('#ms-mensagem-dialog').click();
        return ;
    }

    let id_aluno = event.currentTarget.getAttribute('registro');

    let dados = new FormData();
    dados.append('acao', 'salvar-perfil');
    dados.append('id_aluno', id_aluno);
    dados.append('caracteristicas', document.querySelector('#caracteristicas').value);
    dados.append('objetivo', document.querySelector('#objetivo').value);
    dados.append('historico', document.querySelector('#historico').value);
    dados.append('promessa', document.querySelector('#promessa').value);

    let response = await fetch(
        'alunos/acoes.php', {
            method: 'post',
            body: dados,
        }
    );

    let data = await response.json();

    if(data.status === 'ok')
    {
        document.querySelector('#ms-perfil-salvo-dialog').click();
    }

});
*/

btExportarPDF = document.querySelector('#exportar_pdf');
btExportarPDF.addEventListener('click', async event => {

    let id_aluno = event.currentTarget.getAttribute('registro');

    let dados = new FormData();
    dados.append('acao', 'exportar-pdf-perfil');
    dados.append('id_aluno', id_aluno);

    let response = await fetch(
        'alunos/acoes.php', {
            method: 'post',
            body: dados,
        }
    );

    let data = await response.json();

    if(data.status === 'ok')
    {
        window.open(data.url, '_blank');
    }

});
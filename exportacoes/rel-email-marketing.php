<?php

use Geral\Utilidades;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

include_once ('../config.php');

if(empty($_POST['situacao_aluno'])):
    $situacao = '%';
else:
    $situacao = $_POST['situacao_aluno'];
endif;

/*Data Matricula*/
if(!empty($_POST['data_inicial_matricula'])):
    $data_inicial_matricula = implode('-', array_reverse(explode('/', $_POST['data_inicial_matricula'])));
else:
    $data_inicial_matricula = '';
endif;

if(!empty($_POST['data_final_matricula'])):
    $data_final_matricula = implode('-', array_reverse(explode('/', $_POST['data_final_matricula'])));
else:
    $data_final_matricula = '';
endif;

/*Data Inativação*/
if(!empty($_POST['data_inicial_nativado'])):
    $data_inicial_nativado = implode('-', array_reverse(explode('/', $_POST['data_inicial_nativado'])));
else:
    $data_inicial_nativado = '';
endif;

if(!empty($_POST['data_final_inativado'])):
    $data_final_inativado = implode('-', array_reverse(explode('/', $_POST['data_final_inativado'])));
else:
    $data_final_inativado = '';
endif;

if(!empty($data_inicial_matricula) && empty($data_final_matricula)):
    $data_final_matricula = $data_inicial_matricula;
endif;

if(!empty($data_inicial_nativado) && empty($data_final_inativado)):
    $data_final_inativado = $data_inicial_nativado;
endif;

/*Consulta*/
$sql_data_matricula = !empty($data_inicial_matricula) ? "and matriculas.data_matricula between '{$data_inicial_matricula}' and '{$data_final_matricula}'" : "";
$sql_data_inativavao = !empty($data_inicial_nativado) ? "and matriculas.data_desistencia between '{$data_inicial_nativado}' and '{$data_final_inativado}' and matriculas.id_situacao_aluno_turma = 2" : "";
$registros = Matriculas::find_by_sql("
        select 
            alunos.`status` as status_aluno,
            alunos.data_alteracao as data_alteracao_aluno,
            alunos.nome,
            alunos.email1,
            alunos.email2,
            alunos.celular,
            matriculas.id, 
            matriculas.id_turma, 
            matriculas.id_aluno, 
            matriculas.id_situacao_aluno_turma, 
            matriculas.id_motivo_desistencia, 
            matriculas.data_desistencia, 
            matriculas.status,
            matriculas.data_matricula,
            unidades.nome_fantasia as unidade,
            turmas.nome as turma_nome,
            turmas.data_inicio as data_inicio_turma,
            turmas.data_termino as data_termino_turma,
            turmas.`status` as status_turma,
            turmas.data_alteracao as data_alteracao_turma
        from 
            matriculas 
            inner join alunos
            on matriculas.id_aluno = alunos.id
            inner join turmas
            on matriculas.id_turma = turmas.id
            inner join unidades
            on turmas.id_unidade = unidades.id
        where 
            alunos.status like '{$situacao}'
            and matriculas.data_matricula = (select max(data_matricula) from matriculas where id_aluno = alunos.id)
            {$sql_data_matricula}
            {$sql_data_inativavao}
            group by
            matriculas.id_aluno
            order by
            matriculas.id_aluno asc
    ");

$arquivo = new Spreadsheet();
$tabela = $arquivo->getActiveSheet();
$tabela->setCellValue('A1', 'Nome do Aluno');
$tabela->setCellValue('B1', 'E-mail');
$tabela->setCellValue('C1', 'Celular');
$tabela->setCellValue('D1', 'Turma');
$tabela->setCellValue('E1', 'Unidade');

$cont = 2;
if(!empty($registros)):
    foreach ($registros as $registro):

        if(!empty($registro)):
            $tabela->setCellValue('A'.$cont, $registro->nome);
            $tabela->setCellValue('B'.$cont, (!empty($registro->email1) ? $registro->email1 : $registro->email2));
            $tabela->setCellValue('C'.$cont, $registro->celular);
            $tabela->setCellValue('D'.$cont, $registro->turma_nome);
            $tabela->setCellValue('E'.$cont, $registro->unidade);
            $cont++;
        endif;

    endforeach;
endif;

$escrever = new Xlsx($arquivo);
$nome_arquivo = date('d_m_Y_H_i_s').'email-marketing.xlsx';
$escrever->save($nome_arquivo);

echo json_encode(['status' => 'ok', 'arquivo' => $nome_arquivo]);
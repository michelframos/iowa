<?php

class PerfisAlunosModel extends \ActiveRecord\Model
{

    public static $table_name = 'perfis_alunos';

    public static function salvar($dados)
    {

        $id_aluno = filtra_int($dados['id_aluno']);

        !self::find_by_id_aluno($id_aluno)
            ? $registro = new self()
            : $registro = self::find_by_id_aluno($id_aluno);

        !self::find_by_id_aluno($id_aluno)
            ? dadosCriacao($registro)
            : dadosAlteracao($registro);

        $registro->id_aluno = $id_aluno;
        $registro->caracteristicas = $dados['caracteristicas'];
        $registro->objetivo = $dados['objetivo'];
        $registro->historico = $dados['historico'];
        $registro->promessa = $dados['promessa'];
        $registro->save();

        return 'ok';
    }

}
<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of matriculadisciplinacontroller
 *
 * @author enan
 */
require_once '../Views/matriculadisciplinaview.class.php';
//require_once '../Views/matriculadisciplinaviewmostra.class.php';
require_once '../Models/matriculadisciplinamodel.class.php';
require_once '../Ados/matriculadisciplinaado.class.php';
require_once '../Ados/estudanteado.class.php';
require_once '../Ados/disciplinaado.class.php';

class MatriculaDisciplinaController
{

    private $matriculaDisciplinaView = null;
    private $matriculaDisciplinaModel = null;
    private $matriculaDisciplinaAdo = null;
    private $acao = null;

    public function __construct()
    {

        $this->matriculaDisciplinaView = new MatriculaDisciplinaView();
        $this->matriculaDisciplinaModel = new MatriculaDisciplinaModel();
        $this->matriculaDisciplinaAdo = new MatriculaDisciplinaAdo();

        $this->acao = $this->matriculaDisciplinaView->getAcao();
        switch ($this->acao) {
            case 'con' :
                $this->consultaMatriculaDisciplina();

                break;

            case 'inc' :
                $this->incluimatriculaDisciplina();

                break;

            case 'alt' :
                $this->alteraMatriculaDisciplina();

                break;

            case 'exc' :
                $this->excluimatriculaDisciplina();

                break;
            default :
                //$this->buscaDisciplinas();
                break;
        }

        $this->matriculaDisciplinaView->displayInterface($this->matriculaDisciplinaModel);
    }

    public function __destruct()
    {

    }

    private function consultaMatriculaDisciplina()
    {
        $this->matriculaDisciplinaModel = $this->matriculaDisciplinaView->getDados();
        if ($model = $this->matriculaDisciplinaAdo->consulta($this->matriculaDisciplinaModel)) {
            $this->matriculaDisciplinaModel = $model;
        } else {
            // Continua com a model vazia
        }

        if ($this->matriculaDisciplinaModel) {
            //continue

        } else {
            //  $this->matriculaDisciplinaModel = new MatriculaModel();
            $this->matriculaDisciplinaView->adicionaMensagem($this->matriculaDisciplinaAdo->getMensagem());
            return;
        }
    }

    private function buscaDisciplinas()
    {

        $this->matriculaDisciplinaModel->setMatrzDiscCodigo($this->matriculaDisciplinaAdo->consultaArrayDeObjeto());
        if ($this->matriculaDisciplinaModel) {
            //continue
        } else {
            //  $this->matriculaDisciplinaModel = new MatriculaModel();
            $this->matriculaDisciplinaView->adicionaMensagem($this->matriculaDisciplinaAdo->getMensagem());
            return;
        }
    }

    private function incluimatriculaDisciplina()
    {
        $this->matriculaDisciplinaModel = $this->matriculaDisciplinaView->getDados();

        try {
            if ($this->matriculaDisciplinaAdo->insereObjeto($this->matriculaDisciplinaModel)) {
                // Limpa os dados
                $this->matriculaDisciplinaModel = new MatriculaDisciplinaModel();
            }
            $this->matriculaDisciplinaView->adicionaMensagem($this->matriculaDisciplinaAdo->getMensagem());

        } catch (ErroNoBD $e) {
            $this->matriculaDisciplinaView->adicionaMensagem("Erro na inclusão. contate o analista.");
            //descomente para debugar
            //$this->matriculaDisciplinaView->adicionaMensagem($e->getMessage());
        }
    }

    private function alteraMatriculaDisciplina()
    {
        $this->matriculaDisciplinaModel = $this->matriculaDisciplinaView->getDados();

        try {
            $this->matriculaDisciplinaAdo->alteraObjeto($this->matriculaDisciplinaModel);
            $this->matriculaDisciplinaView->adicionaMensagem("A matricula foi alterada com sucesso");
            $this->consultaMatriculaDisciplina();
        } catch (ErroNoBD $e) {
            $this->matriculaDisciplinaView->adicionaMensagem($e->getMessage());
        }
    }

    private function excluimatriculaDisciplina()
    {
        $this->matriculaDisciplinaModel = $this->matriculaDisciplinaView->getDados();

        try {
            $this->matriculaDisciplinaAdo->excluiObjeto($this->matriculaDisciplinaModel);
            $this->buscaDisciplinas();
            $this->matriculaDisciplinaView->adicionaMensagem($this->matriculaDisciplinaAdo->getMensagem());
        } catch (ErroNoBD $e) {
            $this->matriculaDisciplinaView->adicionaMensagem($e->getMessage());
        }
    }

}
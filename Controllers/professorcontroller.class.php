<?php

/**
 * Classe Controller de Professor
 *
 */
require_once "../Views/professorview.class.php";
require_once "../Models/professormodel.class.php";
require_once "../Ados/professorado.class.php";

class ProfessorController {

    private $professorView = null;
    private $professorModel = null;
    private $professorAdo = null;
    private $acao = null;

    public function __construct() {
               

        $this->professorView = new ProfessorView();
        $this->professorModel = new ProfessorModel();
        $this->professorAdo = new ProfessorAdo();

        $this->acao = $this->professorView->getAcao();
        switch ($this->acao) {
            case 'con' :
                $this->consultaSiape();

                break;

            case 'inc' :
                $this->incluiProfessor();

                break;

            case 'alt' :
                $this->alteraProfessor();

                break;

            case 'exc' :
                $this->excluiProfessor();

                break;
        }
        $this->professorView->displayInterface($this->professorModel);
    }

    public function __destruct() {
        
    }
/**
 * consulta professor pelo siape
 * 
 */
    private function consultaSiape() {
        $this->professorModel = $this->professorView->getDados();

        $this->professorModel = $this->professorAdo->buscaPeloSiape($this->professorModel->getProfSiape());

        if ($this->professorModel) {
            //continue
        } else {
          //  $this->professorModel = new MatriculaModel();
            $this->professorView->adicionaMsgErro($this->professorAdo->getMensagem());
            return;
        }
    }
    /**
     * inclui professor
     * 
     */
    private function incluiProfessor() {
        $this->professorModel = $this->professorView->getDados();

        if ($this->professorModel->VerificaObjeto($this->professorModel)) {}
        else {
            $this->professorView->adicionaMsgErro('Preencha todos os campos.');
            return false;
        }
        
        try {
            if ($this->professorAdo->insereObjeto($this->professorModel)) {
                // Limpa os dados
                $this->professorModel = new professormodel();
            }
            $this->professorView->adicionaMsgSucesso($this->professorAdo->getMensagem());
        } catch (ErroNoBD $e) {
            $this->professorView->adicionaMensagem("Erro na inclusão. contate o analista.");
            //descomente para debugar
            $this->professorView->adicionaMsgErro($e->getMessage());
        }
    }
    /**
     * altera professor
     */
    private function alteraProfessor() {
        $this->professorModel = $this->professorView->getDados();

        try {
            $this->professorAdo->alteraObjeto($this->professorModel);
            $this->professorView->adicionaMsgSucesso($this->professorAdo->getMensagem());
        } catch (ErroNoBD $e) {
            $this->professorView->adicionaMsgErro($e->getMessage());
        }
    }
/**
 * exclui professor
 */
    private function excluiProfessor() {
        $this->professorModel = $this->professorView->getDados();

        try {
            $this->professorAdo->excluiObjeto($this->professorModel);
            $this->professorView->adicionaMsgSucesso($this->professorAdo->getMensagem());
            $this->professorModel = new ProfessorModel();
        } catch (ErroNoBD $e) {
            $this->professorView->adicionaMsgErro($e->getMessage());
        }
    }
}

?>
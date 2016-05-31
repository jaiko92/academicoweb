<?php

require_once 'minhainterface.class.php';

class CursoView extends MinhaInterface
{

    public function montaMeio($cursomodel)
    {
        $cursoAdo = new CursoAdo();
        $cursos = $cursoAdo->listaCursos();

        $cursNome = $cursomodel->getCursNome();
        $cursId = $cursomodel->getCursId();
        $arrayDeBotoes = parent::montaArrayDeBotoes();


        $this->meio = " <div id= 'meio'> 
                            <form method='post' action=''>
                                <select name='cursId'>";
        if ($cursos) {
            foreach ($cursos as $curso) {

                $selecionado = ($cursId == $curso->curs_id) ? ' selected="true" ' : null;

                $this->meio .= "        <option $selecionado value='{$curso->curs_id}'> {$curso->curs_nome}</option>";
            }
        } else {
            $this->meio .= "        <option value=''> Nenhuma opção selecionada </option>";
        }
        $this->meio .= "        </select>";
        $this->meio .= " 
                                {$arrayDeBotoes['con']}
                                <br><br>
                                <b>Entre com os dados</b><br>
                                <br>
                                    
                                <br>Nome    <input type='text' name='cursNome' value='{$cursNome}'>
                                   
                                <br><br>
                                {$arrayDeBotoes['inc']}{$arrayDeBotoes['alt']}{$arrayDeBotoes['exc']}
                            </form>
                        </div>";

        // Codigo  <input type='text' name='cursId' value='{$cursId}'>
    }

    public function montaTitulo()
    {
        $this->titulo = "Inscrição do curso";
    }

    public function getDados()
    {
        $cursNome = $_POST['cursNome'];
        $cursId = $_POST['cursId'];

        return new CursoModel($cursId, $cursNome);
    }

}

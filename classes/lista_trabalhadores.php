<?php
require_once 'cadastro_avulso_bd.php';

class lista_trabalhadores{


    private $html;
    public function __construct() {
        $this->html = file_get_contents('../html/consulta_trabalhadores.html');
    }


    public function load(){
        try{

            $cadastro = cadastro_avulso_bd::lista_trabalhadores();

            $itens='';
            if ($cadastro){
            foreach ($cadastro as $valores) {
                $sindicato = cadastro_avulso_bd::nome_sindicato((int)$valores['sindicato']);
                $item  = file_get_contents('../html/itens_tabela_trabalhadores.html');
                $item  = str_replace('{id}',$valores['id'],$item);
                $item  = str_replace('{data}',$valores['data_registro'],$item);
                $item  = str_replace('{cpf}',$valores['cpf'],$item);
                $item  = str_replace('{trabalhador}',$valores['nome'],$item);
                $item  = str_replace('{cnpj}',$valores['cnpj'],$item);
                $item  = str_replace('{empresa}',$valores['razao_social'],$item);
                $item  = str_replace('{sindicato}',$sindicato['nome'],$item);
                $item  = str_replace('{data_registro}',$valores['data_registro'],$item);
                $itens.= $item;
                }
                $this->html = str_replace('{itens}', $itens, $this->html);
            }
            else{
                print "Nenhum registro encontrado.";
            }
        }
        catch(Exception $e){
        
            print $e->getMessage();
        }

    }

    public function show(){
        $this->load();
        print $this->html;
    }
}
?>
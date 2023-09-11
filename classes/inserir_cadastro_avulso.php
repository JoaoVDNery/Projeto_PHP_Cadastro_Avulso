<?php

require_once 'cadastro_avulso_bd.php';
require_once 'combo.php';

class inserir_cadastro_avulso{

    private $html;
    private $data;
    private $modal;
    private $html_erro_form;
    private $html_duplicidade;
    public function __construct() {
         
        $this->html = file_get_contents('../html/cadastro_avulso.html');
        $this->modal = file_get_contents('../html/modal_analise.html');
        $this->html_erro_form = file_get_contents('../html/modal_erro_form.html');
        $this->html_duplicidade = file_get_contents('../html/modal_duplicidade.html');

        $this->data = ['id'=>null,
            'nome'=>null,
            'cpf'=>null,
            'nascimento'=>null,
            'admissao'=>null,
            'status'=>null,
            'razao_social'=>null,
            'sindicato'=>null,
            'cnpj'=>null,
            'justificativa'=>null,
            'historico'=>null,
            'data_registro'=>null];

            $sindicatos='';
            foreach (combo::lista_combo_sindicato() as $sindicato) {

                $sindicatos.= "<option value='{$sindicato['id']}'> {$sindicato['nome']} </option>";
            }
            $this->html = str_replace('{sindicato}',$sindicatos,$this->html);
    }
    public function save($param,$arquivos){
        
        $erro_form = "";
        if(empty($param['trabalhador'])){
            $erro_form.="Nome do trabalhador <br>";
        }
        if(empty($param['cpf']) or strlen($param['cpf']) != 14){
            $erro_form.="CPF <br>";
        }
        if(empty($param['nascimento'])){
            $erro_form.="Data de Nascimento <br>";
        }
        if(empty($param['admissao'])){
            $erro_form.="Data de Admissão  <br>";
        }
        if(empty($param['id_sindicato'])){
            $erro_form.="Sindicato <br>";
        }
        if(empty($param['razao_social']) or $param['razao_social'] === "CNPJ inválido"){
            $erro_form.="Razão Social <br>";
        }
        if(empty($param['cnpj']) or strlen($param['cnpj']) != 18){
            $erro_form.="CNPJ <br>";
        }
        if(empty($param['justificativa'])){
            $erro_form.="Justificativa <br>";
        }
        if(empty($arquivos["ctps_upload"]['name']) or $arquivos["ctps_upload"]['size'] > 5242880) {
            $erro_form.="ctps (arquivos permitidos: jpge,png e pfd, tamanho máximo 5mb) <br>";
        }
        else{
            $permitidos = ['png', 'jpeg', 'jpg', 'pdf'];
            $ctps_arquivo = $arquivos["ctps_upload"]['name'];
            $ctps_extensao = strtolower(pathinfo($ctps_arquivo, PATHINFO_EXTENSION));
            if(!in_array($ctps_extensao,$permitidos)){
                $erro_form.='ctps - arquivo não permitido <br>';
            }
        }
        if(empty($arquivos["identificacao"]['name']) or $arquivos["identificacao"]['size'] > 5242880) {
            $erro_form.="Documento de Identificação (arquivos permitidos: jpge,png e pfd, tamanho máximo 5mb) <br>";
        }
        else{
            $identificacao_arquivo = $arquivos["identificacao"]['name'];
            $identificacao_extensao = strtolower(pathinfo($identificacao_arquivo, PATHINFO_EXTENSION));
            if(!in_array($identificacao_extensao,$permitidos)){
                $erro_form.='Documento de Identificação - arquivo não permitido <br>';
            }
        }

        if(!empty($arquivos['complementar']['name'])){
            if($arquivos["complementar"]['size'] > 5242880) {
                $erro_form.="Documentos complementares (arquivos permitidos: jpge,png e pfd, tamanho máximo 5mb) <br>";
            }
            else{
                $complementar_arquivo = $arquivos["complementar"]['name'];
                $complementar_extensao = strtolower(pathinfo($complementar_arquivo, PATHINFO_EXTENSION));
                if(!in_array($complementar_extensao,$permitidos)){
                    $erro_form.='Documentos complementares - arquivo não permitido';
                }
            }
        }

        if(!empty($arquivos['manifestacao']['name'])){
            if($arquivos["manifestacao"]['size'] > 5242880) {
                $erro_form.="Manifestação (arquivos permitidos: jpge,png e pfd, tamanho máximo 5mb) <br>";
            }
            else{
                $manifestacao_arquivo = $arquivos["manifestacao"]['name'];
                $manifestacao_extensao = strtolower(pathinfo($manifestacao_arquivo, PATHINFO_EXTENSION));
                if(!in_array($manifestacao_extensao,$permitidos)){
                    $erro_form.='Manifestação - arquivo não permitido';
                }
            }
        }

        if(!empty($erro_form)){
            $this->html_erro_form = str_replace('{erros_form}',$erro_form,$this->html_erro_form);
            return print $this->html_erro_form;
            die();
        }
        else{
            $duplicidade = cadastro_avulso_bd::verifica_duplicidade($param['cpf'],$param['cnpj']);
            if($duplicidade == 'sim'){
                return print $this->html_duplicidade;
            }
        }
        
        $ctps_diretorio = '../uploads/ctps/'; 
        $ctps_rename = $ctps_arquivo . uniqid();
        move_uploaded_file($arquivos["ctps_upload"]['tmp_name'],$ctps_diretorio . $ctps_rename . '.' . $ctps_extensao);
        $path[] = $ctps_diretorio . $ctps_rename . '.' . $ctps_extensao;

        $identificacao_diretorio = '../uploads/identidade/'; 
        $identificacao_rename = $identificacao_arquivo . uniqid();
        move_uploaded_file($arquivos["identificacao"]['tmp_name'],$identificacao_diretorio . $identificacao_rename . '.' . $identificacao_extensao);
        $path []= $identificacao_diretorio . $identificacao_rename . '.' . $identificacao_extensao;

        if(!empty($arquivos['complementar']['name'])){
            $complementar_diretorio = '../uploads/complementares/'; 
            $complementar_rename = $complementar_arquivo . uniqid();
            move_uploaded_file($arquivos["complementar"]['tmp_name'],$complementar_diretorio . $complementar_rename . '.' . $complementar_extensao);
            $path []= $complementar_diretorio . $complementar_rename . '.' . $complementar_extensao;
        }

        if(!empty($arquivos['manifestacao']['name'])){
            $manifestacao_diretorio = '../uploads/manifestacoes/'; 
            $manifestacao_rename = $manifestacao_arquivo . uniqid();
            move_uploaded_file($arquivos["manifestacao"]['tmp_name'],$manifestacao_diretorio . $manifestacao_rename . '.' . $manifestacao_extensao);
            $path []= $manifestacao_diretorio . $manifestacao_rename . '.' . $manifestacao_extensao;
        }
        
        try{
            cadastro_avulso_bd::insert_cadastro_avulso($param);
            cadastro_avulso_bd::cadastro_arquivos($path);
            print $this->modal;
        }
        catch(exception $e){
            print $e-> getMessage();
        }
    }
    public function show(){

        print $this->html;
    }
}

?>
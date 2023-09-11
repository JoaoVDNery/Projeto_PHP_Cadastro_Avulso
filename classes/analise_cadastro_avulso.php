<?php

require_once 'cadastro_avulso_bd.php';
require_once 'combo.php';

class analise_cadastro_avulso{

    private $html;
    private $modal;
    private $data;
    private $html_erro_form;
    private $html_duplicidade;
    public function __construct() {
         
        $this->html = file_get_contents('../html/analise_avulso.html');
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


            $busca_status='';
            foreach (combo::lista_combo_status() as $status) {

                $busca_status.= "<option value='{$status['id']}'> {$status['nome']} </option>";
            }
            $this->html = str_replace('{status}',$busca_status,$this->html);

            $sindicatos='';
            foreach (combo::lista_combo_sindicato() as $sindicato) {

                $sindicatos.= "<option value='{$sindicato['id']}'> {$sindicato['nome']} </option>";
            }
            $this->html = str_replace('{sindicato}',$sindicatos,$this->html);

    }

    public function edit($param){

        try{
            $id = (int) $param['id'];
            $this->data = cadastro_avulso_bd::get_cadastro($id);
        }
        catch(exception $e){
            print $e-> getMessage();
        }

    }
    public function update($param,$arquivos){
        $erro_form = "";
        if(empty($param['id_status'])){
            $erro_form.="Status <br>";
        }
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
        if(empty($param['cnpj'] or strlen($param['cnpj']) != 18)){
            $erro_form.="CNPJ <br>";
        }
        if(!empty($arquivos["ctps_upload"]['name'])){
            if($arquivos["ctps_upload"]['size'] > 5242880) {
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
        }
        if(!empty($arquivos["identificacao"]['name'])){
            if( $arquivos["identificacao"]['size'] > 5242880) {
                $erro_form.="Documento de Identificação (arquivos permitidos: jpge,png e pfd, tamanho máximo 5mb) <br>";
            }
            else{
                $permitidos = ['png', 'jpeg', 'jpg', 'pdf'];
                $identificacao_arquivo = $arquivos["identificacao"]['name'];
                $identificacao_extensao = strtolower(pathinfo($identificacao_arquivo, PATHINFO_EXTENSION));
                if(!in_array($identificacao_extensao,$permitidos)){
                    $erro_form.='Documento de Identificação - arquivo não permitido <br>';
                }
            }
        }
        if(!empty($arquivos['complementar']['name'])){
            if($arquivos["complementar"]['size'] > 5242880) {
                $erro_form.="Documentos complementares (arquivos permitidos: jpge,png e pfd, tamanho máximo 5mb) <br>";
            }
            else{
                $permitidos = ['png', 'jpeg', 'jpg', 'pdf'];
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
                $permitidos = ['png', 'jpeg', 'jpg', 'pdf'];
                $manifestacao_arquivo = $arquivos["manifestacao"]['name'];
                $manifestacao_extensao = strtolower(pathinfo($manifestacao_arquivo, PATHINFO_EXTENSION));
                if(!in_array($manifestacao_extensao,$permitidos)){
                    $erro_form.='Manifestação - arquivo não permitido';
                }
            }
        }

        if(!empty($erro_form)){
            $this->html_erro_form = str_replace('{erros_form}',$erro_form,$this->html_erro_form);
            $this->edit($param);
            return print $this->html_erro_form;
        }
        else{
            if(!empty($arquivos["ctps_upload"]['name'])){ 
                $ctps_diretorio = '../uploads/ctps/'; 
                $ctps_rename = $ctps_arquivo . uniqid();
                move_uploaded_file($arquivos["ctps_upload"]['tmp_name'],$ctps_diretorio . $ctps_rename . '.' . $ctps_extensao);
                $path[] = $ctps_diretorio . $ctps_rename . '.' . $ctps_extensao;
            }
            if(!empty($arquivos["identificacao"]['name'])){
                $identificacao_diretorio = '../uploads/identidade/'; 
                $identificacao_rename = $identificacao_arquivo . uniqid();
                move_uploaded_file($arquivos["identificacao"]['tmp_name'],$identificacao_diretorio . $identificacao_rename . '.' . $identificacao_extensao);
                $path []= $identificacao_diretorio . $identificacao_rename . '.' . $identificacao_extensao;
            }

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
        }
        try{
            cadastro_avulso_bd::analise_avulso($param);
            if(!empty($path)){
                cadastro_avulso_bd::cadastro_arquivos($path,$param['id']);
            }
                $this->edit($param);
            }
            catch(exception $e){
                print $e-> getMessage();
            }

            $duplicidade = cadastro_avulso_bd::verifica_duplicidade($param['cpf'],$param['cnpj']);

            if($param['id_status'] == 2 and $duplicidade == 'nao')
            {
                cadastro_avulso_bd::cadastro_aprovado($param);
            }
            return print $this->modal;
    }

    public function show(){

        $this->html=str_replace('{id}', $this->data['id'], $this->html);
        $this->html=str_replace('{trabalhador}', $this->data['nome'], $this->html);
        $this->html=str_replace('{cpf}', $this->data['cpf'], $this->html);
        $this->html=str_replace('{nascimento}', $this->data['nascimento'], $this->html);
        $this->html=str_replace('{admissao}', $this->data['admissao'], $this->html);
        $this->html=str_replace('{data_registro}', $this->data['data_registro'], $this->html);
        $this->html=str_replace('{status}', $this->data['status'], $this->html);
        $this->html=str_replace('{cnpj}', $this->data['cnpj'], $this->html);
        $this->html=str_replace('{empresa}', $this->data['razao_social'], $this->html);
        $this->html=str_replace('{sindicato}', $this->data['sindicato'], $this->html);
        $this->html=str_replace('{justificativa}', $this->data['justificativa'], $this->html);
        $this->html=str_replace('{historico}', $this->data['historico'], $this->html);

        $this->html = str_replace("<option value='{$this->data['status']}'>","<option selected=1 value='{$this->data['status']}'>",$this->html);
        $this->html = str_replace("<option value='{$this->data['sindicato']}'>","<option selected=1 value='{$this->data['sindicato']}'>",$this->html);
        
        $arquivos_ctps="";
        $arquivos_identificacao="";
        $arquivos_outros="";
        $arquivos_manifestacao="";
        foreach (cadastro_avulso_bd::getArquivos("{$this->data['id']}") as $arquivo) {
            if (strpos($arquivo['path'],'../uploads/ctps/') !== false) {
                $arquivos_ctps .= "<a target='_blank' href='{$arquivo['path']}'>'{$arquivo['path']}'</a> <br>";
            }
            if (strpos($arquivo['path'],'../uploads/identidade/') !== false) {
                $arquivos_identificacao .= "<a target='_blank' href='{$arquivo['path']}'>'{$arquivo['path']}'</a> <br>";
            }
            if (strpos($arquivo['path'],'../uploads/complementares/') !== false) {
                $arquivos_outros .= "<a target='_blank' href='{$arquivo['path']}'>'{$arquivo['path']}'</a> <br>";
            }
            if (strpos($arquivo['path'],'../uploads/manifestacao/') !== false) {
                $arquivos_manifestacao .= "<a target='_blank' href='{$arquivo['path']}'>'{$arquivo['path']}'</a> <br>";
            }
        }
        $this->html=str_replace('{arquivos_ctps}',$arquivos_ctps, $this->html);
        $this->html=str_replace('{arquivos_identificacao}',$arquivos_identificacao, $this->html);
        $this->html=str_replace('{arquivos_outros}',$arquivos_outros, $this->html);
        $this->html=str_replace('{arquivos_manifestacao}',$arquivos_outros, $this->html);

        print $this->html;
    }
}
?>
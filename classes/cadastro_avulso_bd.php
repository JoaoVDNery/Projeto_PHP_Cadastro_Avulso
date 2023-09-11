<?php

class cadastro_avulso_bd{
        
        public static function getConnection(){
                  
                $conexao = new PDO('mysql:host="";port="";dbname=','root',NULL);
                $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                return $conexao;

        }

        public static function get_cadastro($id){

                $conexao = self::getConnection();

                $sql = "SELECT * from cadastro_avulso where id = :id";

                $resultado = $conexao->prepare($sql);
                $resultado->execute([':id'=>$id]);

                return $resultado->fetch();
        }

        public static function insert_cadastro_avulso($cadastro){
                $conexao = self::getConnection();

                $data = date("Y-m-d");
                $status = 1;
               
                
                $sql = "insert into cadastro_avulso (nome,cpf,nascimento,admissao,cnpj,razao_social,justificativa,status,data_registro,sindicato) 
                values (
                        :trabalhador,
                        :cpf,
                        :nascimento,
                        :admissao,
                        :cnpj,
                        :razao_social,
                        :justificativa,
                        :status,
                        :data,
                        :sindicato)";
                
                $resultado = $conexao->prepare($sql);
                $resultado->execute([':trabalhador' => $cadastro['trabalhador'],
                                        ':cpf'=> $cadastro['cpf'],
                                        ':nascimento'=>$cadastro['nascimento'],
                                        ':admissao'=>$cadastro['admissao'],
                                        ':cnpj'=>$cadastro['cnpj'],
                                        ':razao_social'=>$cadastro['razao_social'],
                                        ':justificativa' =>$cadastro['justificativa'],
                                        ':status' => $status,
                                        ':data'=> $data,
                                        ':sindicato' => $cadastro['id_sindicato']]);
                
                $conexao = null;
                return $resultado;

        }

        public static function verifica_duplicidade($cpf,$cnpj){
                $conexao = self::getConnection();

                $sql = "SELECT COUNT(id) AS count FROM trabalhadores WHERE cpf = :cpf AND cnpj = :cnpj";
                $resultado = $conexao->prepare($sql);
                $resultado->bindParam(':cpf', $cpf);
                $resultado->bindParam(':cnpj',$cnpj);
                $resultado->execute();

                $verifica = $resultado->fetch(PDO::FETCH_ASSOC);

                if ($verifica['count'] > 0) {
                        return 'sim';
                }
                else{   
                        return 'nao';
                }
        } 

        public static function analise_avulso($cadastro){
                $conexao = self::getConnection();

                $sql ="UPDATE cadastro_avulso SET
                status        = :status,
                nome          = :trabalhador,
                cpf           = :cpf,
                nascimento    = :nascimento,
                admissao      = :admissao,
                sindicato     = :sindicato,
                razao_social  = :razao_social,
                cnpj          = :cnpj,
                justificativa = :justificativa,
                historico     = :historico
                WHERE id      = :id";
                
                $resultado = $conexao->prepare($sql);
                $resultado->execute([':status' => $cadastro['id_status'],
                                             ':trabalhador' => $cadastro['trabalhador'],
                                             ':cpf' => $cadastro['cpf'],
                                             ':nascimento'=> $cadastro['nascimento'],
                                             ':admissao' => $cadastro['admissao'],
                                             ':sindicato'=>$cadastro['id_sindicato'],
                                             ':razao_social'=>$cadastro['razao_social'],
                                             ':cnpj'=>$cadastro['cnpj'],
                                             ':justificativa'=>$cadastro['justificativa'],
                                             ':historico'=>$cadastro['historico'],
                                             ':id'=>$cadastro['id']]);
                $conexao = null;
                return $resultado;
        }
        public static function lista_cadastros(){
                
                $conexao = self::getConnection();
                $resultado = $conexao->query("SELECT * from cadastro_avulso ORDER BY id");
                return $resultado->fetchAll();
        }

        public static function lista_trabalhadores(){
                
                $conexao = self::getConnection();
                $resultado = $conexao->query("SELECT * from trabalhadores ORDER BY id");
                return $resultado->fetchAll();
        }

        public static function cadastro_aprovado($cadastro){
                $conexao = self::getConnection();

                $data_registro = date("Y-m-d");
                $sql = "insert into trabalhadores (nome,cpf,nascimento,admissao,cnpj,razao_social,data_registro,sindicato) 
                values (
                        :trabalhador,
                        :cpf,
                        :nascimento,
                        :admissao,
                        :cnpj,
                        :razao_social,
                        :data,
                        :sindicato)";
                
                $resultado = $conexao->prepare($sql);
                $resultado->execute([':trabalhador' => $cadastro['trabalhador'],
                                        ':cpf'=> $cadastro['cpf'],
                                        ':nascimento'=>$cadastro['nascimento'],
                                        ':admissao'=>$cadastro['admissao'],
                                        ':cnpj'=>$cadastro['cnpj'],
                                        ':razao_social'=>$cadastro['razao_social'],
                                        ':data'=> $data_registro,
                                        ':sindicato' => $cadastro['id_sindicato']]);
                
                $conexao = null;
        }
        
        public static function cadastro_arquivos($path,$id=null){
                $conexao = self::getConnection();
                        $id_cadastro_solicitado = $id;
                        if(empty($id_cadastro_solicitado)){
                        $sql = "SELECT MAX(id) as ultimoID FROM cadastro_avulso";
                        $result = $conexao->query($sql);
                        $row = $result->fetch(PDO::FETCH_ASSOC);

                        $ultimoID = $row['ultimoID'];
                }
                else{
                        $ultimoID = $id;
                }
                        foreach ($path as $arquivos) {
                                $sql = "insert into arquivos (id_cadastro,path) values (:id_cadastro,:path)";
        
                                $resultado = $conexao->prepare($sql);
                                $resultado->execute([':id_cadastro'=>$ultimoID,
                                                ':path'=>$arquivos]);
                        }
                        $cadastro = null;
        }

        public static function getArquivos($id){
                $id_cadastro = $id;
                $conexao = self::getConnection();

                $sql = "select path from arquivos where id_cadastro = :id";
                $resultado = $conexao->prepare($sql);
                $resultado->execute([':id' =>$id_cadastro]);
                return $resultado->fetchAll();
                $conexao = null;
        }

        public static function nome_status($id_status){
                $conexao = self::getConnection();
                $resultado =  $conexao->query("SELECT nome FROM status_bss  WHERE id = $id_status");
                return $resultado->fetch();
        }

        public static function nome_sindicato($id_sindicato){
                $conexao = self::getConnection();
                $resultado =  $conexao->query("SELECT nome FROM sindicato  WHERE id = $id_sindicato");
                return $resultado->fetch();
        }
}

?>      
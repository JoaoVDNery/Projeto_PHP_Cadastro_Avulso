<?php

class combo{

    public static function getConnection(){
                  
        $conexao = new PDO('mysql:host="";port="";dbname=','root',NULL);
        $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conexao;

}

    public static function lista_combo_status(){
        $conexao = self::getConnection();

        $resultado = $conexao->query("SELECT * from status_bss ORDER BY id");
        return $resultado->fetchAll();
    }

    public static function lista_combo_sindicato(){      
        $conexao = self::getConnection();

        $resultado = $conexao->query("SELECT * from sindicato ORDER BY id");
        return $resultado->fetchAll();
    }

}

?>
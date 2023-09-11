<?php

spl_autoload_register(function($class){
    if(file_exists($class . '.php')){

        require_once $class . '.php';
    }
});
$classe = isset($_REQUEST['class']) ? $_REQUEST['class'] : null ;
$metodo = isset($_REQUEST['method']) ? $_REQUEST['method'] : null;

if ( class_exists($classe) ){

    $pagina = new $classe($_REQUEST,$_FILES);

    if(!empty($metodo) and method_exists($classe,$metodo)){
        $pagina->$metodo($_REQUEST,$_FILES);
    }
    $pagina->show();
}
?>
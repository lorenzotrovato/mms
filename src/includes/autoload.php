<?php
	
	spl_autoload_register(function($className){
		$array=explode('\\', $className);
		$inclusion=$_SERVER["DOCUMENT_ROOT"].'/src/classes/'.$array[count($array)-1].'.php';
		require_once $inclusion;
	});
	
?>
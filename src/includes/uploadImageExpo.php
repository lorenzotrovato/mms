<?php
	namespace MMS;
    require_once './autoload.php';
    use MMS\Security;
    use MMS\Expo as Expo;
    Security::init();
    if(!Security::verSession()){
        die(json_encode(array('result'=>'permesso negato')));
    }
    
    //id expo
    $idexpo = intval(Security::escape($_POST['expoid']));
    $expo = new Expo($idexpo);
    
	//dati file immagine
	$fileName = $_FILES['image']["name"]; 
    $fileTmpLoc = $_FILES['image']["tmp_name"]; 
    $fileType = $_FILES['image']["type"];
    $fileSize = $_FILES['image']["size"];
    
    if($fileType!="image/jpeg" && $fileType!="image/png"){
    	die(json_encode(array('result'=>'file non supportato')));
    }
    
    if($expo instanceof Expo){
    	$new_path = '/var/www/html/src/images/covers/'.md5($idexpo) . '.'.pathinfo($fileName, PATHINFO_EXTENSION);
    	if(move_uploaded_file($fileTmpLoc, $new_path)){
    		echo json_encode(array('result'=>'ok','imagepath'=>'./images/covers/'.md5($idexpo) . '.'.pathinfo($fileName, PATHINFO_EXTENSION)));
    	}else{
    		die(json_encode(array('result'=>'errore nell\'upload')));
    	}
    }else{
    	die(json_encode(array('result'=>'l\'esposizione non esiste')));
    }

?>
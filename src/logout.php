<?php
	namespace MMS;
	define('PAGENAME', 'logout');
	require_once 'includes/autoload.php';
	use MMS\Security as Security;
	Security::init();
	
	if(Security::verSession()){
		$lUser = Security::getUserFromSession();
		if($lUser){
			Security::removeAutoLoginCookies($lUser);
		}
		session_unset();
		session_destroy();
	}
	header('location: signin.php');
?>
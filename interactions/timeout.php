<?php
	if(isset($_SESSION['Name'])){
		if((time() - $_SESSION['time']) > 120){
			session_start();
			session_unset();
			session_destroy();
			header("Location: index.php?timeout");
			exit();
		} else{
			$_SESSION['time'] = time();
		}
	}
?>
<?php 

	if (session_status() !== PHP_SESSION_ACTIVE)
	{
		session_start();
		session_regenerate_id();
	}

	unset($_SESSION['user']);

	//	Enregistrement du message de réussite
	$_SESSION['alertMessages']['success'][] = 'Vous êtes bien déconnecté !';
	
	header('Location:forms.php');
	exit();
 ?>
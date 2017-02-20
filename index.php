<?php 
	if (session_status() !== PHP_SESSION_ACTIVE)
	{
		session_start();	//permet de démarrer la session, mais une seule fois par script, compliqué avec les include --> d'où la mise en place d'une condition
		session_regenerate_id();	//change l'id de session dès qu'on a une activité sur le site
	}

	//	var_dump($_SESSION);

	if (!array_key_exists('user', $_SESSION))
	{
		header('Location:forms.php');
		exit();
	}

	$user = $_SESSION['user'];

	$connection = new PDO
					('mysql:host=aerellotdooceane.mysql.db;dbname=aerellotdooceane;charset=utf8',
					'aerellotdooceane',
					'AereBDD25',
					[
						PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
						PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
					]
					);

	$query = 
		'SELECT
		Messages.id, content, publishingDate, idUser, username
		FROM
		Messages	
		INNER JOIN	
		Users on Messages.idUser = Users.id
		ORDER BY
		publishingDate DESC
		LIMIT 
		10
		';
	//	var_dump($query);

	$resultSet = $connection -> query($query);
	$messages = $resultSet -> fetchAll();				

require("page-utilisateur.phtml");
 ?>
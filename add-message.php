<?php 
var_dump($_POST);
	if(isset($_POST['message']))
 	{
 		if (session_status() !== PHP_SESSION_ACTIVE)
 		{
 			session_start();	//permet de démarrer la session, mais une seule fois par script, compliqué avec les include --> d'où la mise en place d'une condition
 			session_regenerate_id();	//change l'id de session dès qu'on a une activité sur le site
 		}

 		//	Si l'utilisateur n'est pas authentifié
 		if(!array_key_exists('user', $_SESSION))
 		{
 			//	Enregistrement du message d'échec
 			$_SESSION['alertMessages']['error'][] = 'Veuillez vous authentifier !';

 			//	Redirection vers la page d'authentification et de création de compte
 			header('Location: forms.php');
 			exit();
 		}

var_dump($_SESSION);
 		$message = $_POST['message'];
var_dump($message);

		if(!empty($message))
	 	{	//Ajout du message
	 		$connection = new PDO
			('mysql:host=aerellotdooceane.mysql.db;dbname=aerellotdooceane;charset=utf8',
				'aerellotdooceane',
				'AereBDD25',
				[
					PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
					PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
				]
			);
		 	$idUser = $_SESSION['user']['id'];
		 	//$date = date('Y-m-d H:i:s');
var_dump($idUser, $date);
		  	$query = 
			'INSERT INTO
			Messages(content, publishingDate, idUser)		
			VALUES
			(?, NOW(), ?)
			';
var_dump($query);
			$resultSet = $connection -> prepare($query);
			$resultSet -> execute([$message, $idUser]);
			//	Récupération du résultat de l'ajout (réussite ou échec)
			$messageAdded = ($resultSet->rowCount() == 1);

			//	Si le message a bien été ajouté
			if($messageAdded)
			{
				//	Enregistrement du message de réussite
				$_SESSION['alertMessages']['success'][] = 'Le message a bien été créé !';
			}
			//	Si le message n'a pas été ajouté
			else
			{
				//	Enregistrement du message d'échec
				$_SESSION['alertMessages']['error'][] = 'L\'ajout du message a échoué !';
			}
		}
	}
	header('Location: index.php');
	exit();

 ?>
<?php 

//	var_dump($_POST);

	if(array_key_exists('pseudo', $_POST) && array_key_exists('password', $_POST))
 	{
 		if (session_status() !== PHP_SESSION_ACTIVE)
 			{
 				session_start();
 				session_regenerate_id();
 			}
 			
		if(!empty($_POST['pseudo'])&&!empty($_POST['password']))
		{	
			$connection = new PDO
				('mysql:dbname=aerellotdooceane;host=aerellotdooceane.mysql.db;charset=utf8;port=',
				'aerellotdooceane',
				'AereBDD25',
				[
					PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
					PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
				]
			);

			$username = trim($_POST['pseudo']);

			$query = 
			'SELECT
			username
			FROM
			Users	
			WHERE
			username = ?	
			';

			$resultSet = $connection -> prepare($query);
			$resultSet -> execute([$username]);
		//	var_dump($resultSet);
			$previousUser = $resultSet -> fetch();
		//	var_dump($user);
			
			if ($previousUser == false)
			{
				$password = trim($_POST['password']);
			  	$password_hash = password_hash($password, PASSWORD_DEFAULT);
			//	var_dump($password_hash);

			  	$query = 
				'INSERT INTO
				Users(username, passwordHash)		
				VALUES
				(?,?)
				';
			//	var_dump($query);

				$resultSet = $connection -> prepare($query);
				$resultSet -> execute([$username, $password_hash]);
				//	Récupération du résultat de l'ajout (réussite ou échec)
				$userAdded = ($resultSet->rowCount() == 1);

				if (session_status() !== PHP_SESSION_ACTIVE)
				{
					session_start();
					session_regenerate_id();
				}

				//	Si l'utilisateur a bien été ajouté
						if($userAdded)
						{
							//	Enregistrement du message de réussite
							$_SESSION['alertMessages']['success'][] = 'Le compte a bien été créé !<br>Merci de vous authentifier.';
						}
						//	Si l'utilisateur n'a pas été ajouté
						else
						{
							//	Enregistrement du message d'échec
							$_SESSION['alertMessages']['error'][] = 'La création du compte a échoué !';
						}

				header('Location: forms.php');
				exit();
			}
		  	else
		  	{
		  		$_SESSION['alertMessages']['error'][] = 'Ce pseudo existe déjà.';
		  	}
		}
		else
		{
			$_SESSION['alertMessages']['error'][] ='Veuillez entrer un pseudo et un mot de passe valides.';
		}
	}  

header('Location: forms.php');
exit();

 ?>
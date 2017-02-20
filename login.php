<?php 

var_dump($_POST);

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
				('mysql:host=aerellotdooceane.mysql.db;dbname=aerellotdooceane;charset=utf8',
				'aerellotdooceane',
				'AereBDD25',
				[
					PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
					PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
				]
				);

			$username = trim($_POST['pseudo']);
			$password = trim($_POST['password']);
			
		  	$query = 
			'SELECT
			id, username, passwordHash
			FROM
			Users		
			WHERE
			username = ?
			';
//	var_dump($query);

			$resultSet = $connection -> prepare($query);
			$resultSet -> execute([$username]);
			$user = $resultSet -> fetch();
//	var_dump($user);

			if ($user !== false && password_verify($password, $user['passwordHash']))	// = le pseudo existe
			{
				if (session_status() !== PHP_SESSION_ACTIVE)
				{
					session_start();
					session_regenerate_id();
				}

				$_SESSION['user'] = 
					[
						'id' => $user['id'],
						'username' => $user['username']
					];
				header('Location: index.php');
				exit();
			}
			else
			{
				$_SESSION['alertMessages']['error'][] = 'Le pseudo et le mot de passe ne correspondent pas.';
			}
		}  
		else 
		{
			$_SESSION['alertMessages']['error'][] = 'Veuillez entrer un pseudo et un mot de passe valides.';
		}
	}

header('Location: forms.php');
exit();

 ?>
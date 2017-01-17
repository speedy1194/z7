<?php

	session_start();
	
	if ((!isset($_POST['login'])) || (!isset($_POST['haslo'])))
	{
		header('Location: index.php');
		exit();
	}

	require_once "connect.php";

	$polaczenie = @new mysqli($servername, $username, $password, $dbname);
	
	if ($polaczenie->connect_errno!=0)
	{
		echo "Error: ".$polaczenie->connect_errno;
	}
	else
	{
		$login = $_POST['login'];
		$haslo = $_POST['haslo'];
		
		$login = htmlentities($login, ENT_QUOTES, "UTF-8");
		$haslo = htmlentities($haslo, ENT_QUOTES, "UTF-8");
		$rezultat2 = $polaczenie->query("SELECT * FROM zad7");
		if ($rezultat = @$polaczenie->query(
		sprintf("SELECT * FROM zad7 WHERE user='%s' AND pass='%s'",
		mysqli_real_escape_string($polaczenie,$login),
		mysqli_real_escape_string($polaczenie,$haslo))))
		{	$row = $rezultat2->fetch_assoc();
			$fails=$row['fails'];
			$blocked=$row['blocked'];
			$rezultat2->free_result();
			if($fails >= 2)
				$polaczenie->query("UPDATE zad7 SET blocked = 1 WHERE user = '$login'");
			$ilu_userow = $rezultat->num_rows;
			if($ilu_userow>0)
			{
				if($blocked==0)
				{
				$polaczenie->query("INSERT INTO logs VALUES (NULL, '$login', NULL , 1)");
				$polaczenie->query("UPDATE zad7 SET fails = 0 WHERE user = '$login'");
				$_SESSION['zalogowany'] = true;
				
				$wiersz = $rezultat->fetch_assoc();
				$_SESSION['user'] = $wiersz['user'];				
				unset($_SESSION['blad']);
				$rezultat->free_result();
				header('Location: home.php');
				}
				else
				{
					$_SESSION['blad']='<span style="color:red">Konto zostało zablokowane. </span>';
					header('Location: index.php');
				}
			} else {
				$polaczenie->query("UPDATE zad7 SET fails = fails + 1 WHERE user = '$login'");
				$polaczenie->query("INSERT INTO logs VALUES (NULL, '$login', NULL , 0)");
				$_SESSION['blad'] = '<span style="color:red">Nieprawidłowy login lub hasło!</span>';
				header('Location: index.php');
				
			}
			
		}
		
		$polaczenie->close();
	}
	
?>
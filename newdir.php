<?php
session_start();
if(isset($_POST['dir']))
$dirname = $_POST['dir'];
if(isset( $_SESSION['user']))
$user = $_SESSION['user'];
if(!is_dir("katalogi/$user/$dirname"))
{
	mkdir ("katalogi/$user/$dirname", 0777);
	header('Location: home.php');	
}
else
{
	echo ("B³¹d tworzenia folderu");
}
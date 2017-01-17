<?php
session_start();
$path = $_POST['path'];
$user = $_SESSION['user'];
echo $path;
 if (is_uploaded_file($_FILES['plik']['tmp_name']))
 {
// echo 'Odebrano plik: '.$_FILES['plik']['name'].'<br/>';
 move_uploaded_file($_FILES['plik']['tmp_name'],
 $_SERVER['DOCUMENT_ROOT']."/z7/$path/".$_FILES['plik']['name']);
 }
 //else {echo 'B³¹d przy przesy³aniu danych!';}
 header("location: home.php")
?>
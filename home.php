
<html>
 <head>
<style>
table { border: 1px solid gray;}
td { text-align: center; padding: 25; border: 1px solid gray;}
</style>
<?php
session_start();
require_once "connect.php";
$user = $_SESSION['user'];
if(isset($_POST['dirs']))
{
	$dirs=$_POST['dirs'];
}
$x=0; //zmienna do pętli
if(isset($dirs)&&($dirs!=$user))
	$path="katalogi/$user/$dirs";
else
{
	$path="katalogi/$user";
}

$polaczenie = @new mysqli($servername, $username, $password, $dbname);
if ($polaczenie->connect_errno!=0)
	{
		echo "Error: ".$polaczenie->connect_errno;
	}
	else
	{
		if($rezultat = @$polaczenie->query("SELECT * FROM logs WHERE logged = 0 AND user = '$login'  ORDER BY date DESC"));
		{
			$row = $rezultat->fetch_assoc();
			if($row['date']!=NULL)
			{
			$date=$row['date'];
			echo "Last incorrect login: ".$date;
			}
		}
	}
	
?>
</head>
<body>

<center>
<h1> File Uploader </h1> 
</center>
<br>
<?php

echo "Hello <b> ".$user. "</b><br><br>";
echo "<a href='logout.php'>Log Out</a> <br><br>";
?>



<?php
$i=0;
if ($handle = opendir($path)) 
{
	if(!empty($handle))
	{
		echo "<table>";
		echo "<tr>";
		echo "<td>";
		echo "<b>File</b>";
		echo "</td>";
		echo "<td>";
		echo "<b>Size</b>";
		echo "</td>";
		echo "<tr>";
		while (false !== ($file = readdir($handle))) 
			{ 
			   if ($file != "." && $file != "..") 
			   {
				   if(!is_dir("$path/$file"))
				   {
					   $size=filesize("$path/$file");
					   echo "<tr>";
					   echo "<td>";
					   echo "<a href='$path/$file'>$file</a> <br>"; 
					   echo "</td>";
					   echo "<td>";
					   echo "$size";
					   echo "</td>";
					   echo "</tr>";
					}
					if(is_dir("$path/$file"))				   
				   {
						$i++;
						$dir[]=$file;
				   }
				}		   
			}
		echo "</table>";
   }
   
	closedir($handle); 
}
?> 
<form method="POST">
<b>Choose your folder:</b> <br>
	<select name="dirs">
		<option><?php echo $user; ?></option>
		<?php 
		while($i>$x)
		{
			echo "<option>" . $dir[$x] . "</option>";
			$x++;		
		}
		?>
	</select>
	<input type="submit" value="Choose" />
</form>
<form action="newdir.php" method="POST">
<b>Create a new folder:</b> <br /> <input type="text" name="dir" /> <br />
<input type="submit" value="Create" />
</form>
 
 <h2> Send files </h2>
 <form action="odbierz.php" method="POST"
 ENCTYPE="multipart/form-data">
 <input type="file" name="plik"/>
 <input type="submit" value="Send file"/>
 <input type="hidden" name="path" value="<?php echo $path; ?>">
 </form>
 </body>
</html>

<?php
//Pobranie zmiennych
$time = date('H:i:s', time());
$user = $_POST['user']; 
$post = $_POST['post'];
$add_file = $_POST['add_file'];
$topic = $_POST['topic'];
$connection = mysqli_connect('localhost', 'kosierap_z7', 'Laboratorium123', 'kosierap_z7');
if (!$connection)
{
echo " MySQL Connection error." . PHP_EOL;
echo "Error: " . mysqli_connect_errno() . PHP_EOL; echo "Error: " . mysqli_connect_error() . PHP_EOL; exit;
}
//Jeżeli wybrano opcję dodania pliku
if(isset($add_file)){
//Utwórz nowy folder
mkdir("../user_folders/$user", 0755, true);
//Dodaj plik do folderu
$target_file = "../users/".  $user ."/". basename($_FILES["fileToUpload"]["name"]); 
if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) $result = mysqli_query($connection, "INSERT INTO message (message, user, topic) VALUES ('$target_file', '$user' , '$topic');") or die ("DB error: $dbname");
else echo "Error uploading file.";     
}
//Jeżeli wybrano napisanie wiadomości
 if (isset($post) && !empty($post)) $result = mysqli_query($connection, "INSERT INTO message (message, user, topic) VALUES ('$post', '$user', '$topic');") or die ("DB error: $dbname");
mysqli_close($connection); 
//Powrót do chatu
header ('Location: chat.php');?>
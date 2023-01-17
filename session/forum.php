<?php 
//Spradzanie sesji
declare(strict_types=1);
session_start();
if (!isset($_SESSION['loggedin'])) //Jezeli nie ma sesji
{
  $_SESSION['login'] = "GOSC";
}
  $connection = mysqli_connect('localhost', 'kosierap_z7', 'Laboratorium123', 'kosierap_z7');
  	if (!$connection){
		echo " MySQL Connection error." . PHP_EOL;
		echo "Errno: " . mysqli_connect_errno() . PHP_EOL; echo "Error: " . mysqli_connect_error() . PHP_EOL; exit;
	}
        $findUser = mysqli_query($connection, "Select * from user") or die ("DB error 2: $dbname");
 while ($row = mysqli_fetch_array ($findUser)) if($row[1] == $_SESSION['login'])  {
if($row[5]==1){
  print "<h1>NASTĄPIŁA BLOKADA KONTA, WYLOGOWUJE</h1>";
  header('Refresh: 2; URL=logout.php');
}
   $_SESSION['userup'] = $row[3];
    $_SESSION['userid'] = $row[0];
 }
  ?>
    <!-- Prosty CSS -->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">
  <style>
    table{
      float:left;
      background-color:rgba(218, 238, 242, 1);
      max-width:50%;
    }
    .log, .topics { 
    }
    input[type=submit] {
  background-color: white;
  color: black;
  border: 2px solid #555555;
}

input[type=submit]:hover {
  background-color: #555555;
  color: white;
}
        button {
  background-color: white;
  color: black;
  border: 2px solid #555555;
}

button:hover {
  background-color: #555555;
  color: white;
}
    .left{
      width: 300px;
    }
    .right{
      width:700px;
      height:500px;
    }
    img,audio,video {
  width:100%;
    max-width:250px;
  }
      .imgDel, .imgBlk, img > .right, .addimg {
   max-width:25px; 
  }
    .resMess{
      border-bottom: 5px solid red; 
    }
    .profileH  {
       border-top: 5px solid blue; 
    }
    .lgout{
      border-bottom: 5px solid blue; 
    }
    h2 {
      color:red;
    }
 .logi{
   color: red;
    }
  </style>
  
  <script>
    //Funkcje JS na wyswietlanie odpowiednich elementow
var addTopic = document.getElementById("addTopic");
var log = document.getElementById("log");
var addMess = document.getElementById("addMessage");
   var showMTO = document.getElementById("showMTO"); 
   var showAddM = document.getElementById("showAddM");
function addTopicFunction() {
  var lorem = document.getElementById("lorem");
var txt = document.getElementById("txt");
  var messageName = document.getElementById("messageName"); 
  var showBM = document.getElementById("showBM"); 
  if (txt.style.display === "block") {
    txt.style.display = "none";
    
  } else {
    txt.style.display = "block";
   
  }
}
    function showMessagesToMe() {
  var lorem = document.getElementById("lorem");
var txt = document.getElementById("txt");
      var showBM = document.getElementById("showBM"); 
       var myMessages = document.getElementById("myMessages"); 
  if (myMessages.style.display === "block") {
txt.style.display = "none";
    myMessages.style.display = "none";
    showBM.style.display = "none";
    lorem.style.display = "block";
  } else {
txt.style.display = "block";
    myMessages.style.display = "block";
    showBM.style.display = "none";
    lorem.style.display = "none";
  }
}
       function showMessage() {       
 var messOpt = document.getElementById("messOpt");
         
  if (messOpt.style.display === "block") {
messOpt.style.display = "none";
  } else {
messOpt.style.display = "block";
  }
  
} 
</script>
  <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
 </head>
<BODY>
  <!--Stworzenie tabeli -->
<table CELLPADDING=5 BORDER=1>
  <tr>
    <!--Lewa strona z tematami i logowaniem -->
    <td id="left" class="left">
    <?php
     if($_SESSION['login']!="GOSC") {
      print "Zalogowano jako: <a id='logi' class='logi'>".$_SESSION['login']."</a>";
        print " <button onclick='showMessagesToMe()' type='button' id='showMTO' class='showMTO'>Wiadomosci do mnie</button>";
    	 print "<div id='lgout' class='lgout'><form action='logout.php'><br> <input type='submit' value='LOGOUT'/></form></div>";
    }
    else{
      print "<form method='POST' action='index3.php'>
			 <input type='submit' value='LOGIN'/>
			</form> ";
      print "<form method='post' action='rejestruj.php'><input id='register' class='register' type='submit' value='Register'></form>";
    }
    ?>
  <div id="topics" class="topics">
<?php
    //Wyswietlanie tematow i opcji usuwania jezeli jest to admin lub moderator
	$result = mysqli_query($connection, "Select * from topic join user on topic.idu = user.idu order by idt asc") or die ("DB error: $dbname");
	while ($topic = mysqli_fetch_array ($result)){
      	$_SESSION['userTopic'] = $topic[4];
      $value = $topic[1];
      $_SESSION['topicV'] = $topic[0];
		print "<h3><a href='forum.php?topic=$topic[0]&userTopic=$topic[4]'>$value</a></h3>";
      if($_SESSION['userup']=="admin" || $_SESSION['userup']=="moderator"){
       print "<label for='$topic[0]'>
		<form method='POST' action='forum.php?topic=$topic[0]&deleteTopic=$topic[0]' enctype='multipart/form-data'>
		<img id='imgDel' class='imgDel' src='./icons/delete.png'>
    	<input type='submit' id='$topic[0]' name='$topic[0]' value='Delete' style='display:none'></form></label>";
      }
    }
    ?>
  </div>
  <br>
  <br>
      <br> 
      <!--Opcja dodania tematu -->
       <button onclick='addTopicFunction()' type='button' id='addTopic' class='addTopic'>Dodaj temat</button>
              <div id="txt" class="txt" style="display:none">    
               <form method="POST" action="forum.php" enctype="multipart/form-data">
    <input type="text" name="topicName" id="topicName" value="Topic">
    <input type="submit" id="createTopic" name="createTopic" value="Add"/>      
  </form>
      </div>
    </td> 
    <!--Prawa tabela-->
 <td id="right" class="right" rowspan="2">
   <!--Wiadomosci wyslane do zalogowanego uzytkownika -->
 <div id="myMessages" class="myMessages" style='display:none'>
   <?php
   $privID = $_SESSION['userid'];
   $resultPrivateMessages = mysqli_query($connection, "Select * from privateMessage join user on privateMessage.idfu = user.idu where privateMessage.idtu='$privID' order by idpm asc") or die ("DB error: $dbname");
	while ($mess = mysqli_fetch_array ($resultPrivateMessages)){
   print "Uzytkownik: ".$mess[6] ." przesłał wiadomość: ". $mess[3]; print "<br>";
   }
   ?>
   </div>
   <!--Wyswietlanie wiadomosci z danego tematu-->
      <div id="lorem" class="lorem">     
        <?php
        $topicVV = $_GET['topic'];
        $_SESSION["topic"] = $_GET['topic'];
        if(isset($_GET['topic'])){
        $resultMessages = mysqli_query($connection, "Select * from message join user on message.idu = user.idu where topic=$topicVV order by idm asc") or die ("DB error: $dbname");
	while ($mess = mysqli_fetch_array ($resultMessages)){
    print "<div id='resMess'class='resMess'>";
      $message = $mess[2];
       if(end(explode(".",$message)) =="png" || end(explode(".",$message)) =="jpg" || end(explode(".",$message)) =="jpeg" || end(explode(".",$message)) =="gif") print "<img src='$message'/>\n";
    else if(end(explode(".",$message)) =="mp3") print "<audio controls src='$message'><a href='$message'>Download audio</a></audio>\n";
    else if(end(explode(".",$message)) =="mp4") print "<video controls autoplay src='$message'></video>\n";
    else print $message." (by <a href='forum.php?userProfile=$mess[6]&userID=$mess[4]'>".$mess[6]."</a>)"; 
      if($_SESSION['userid']==$mess[4] || $_SESSION['userup'] == "admin")  print "<label for='$mess[0]'>
	<form method='POST' action='forum.php?topic=$topicVV&deleteMessage=$mess[0]' enctype='multipart/form-data'>
	<img id='imgDel' class='imgDel' src='./icons/delete.png'>
    <input type='submit' id='$mess[0]' name='$mess[0]' value='Delete' style='display:none'></form></label>";
     print "</div><br>";
    }
        }  
        ?>
<!--Opcja dodania wiadomosci -->
      <button type="button" onclick="showMessage()" id="showBM" class="showBM" style='display:none'>Dodaj wiadomosc</button>
      <div id="addL" name="addL">        
	<div id="messOpt" class="messOpt" style='display:none'>
	<form method="POST" action="forum.php?topic=<?php echo $_GET['topic'];  ?>" enctype="multipart/form-data">
      <h4>Dodaj Wiadomość:</h4>
    <input type="text" name="messageName" id="messageName" value="Wiadomosc" >
    <input type="submit" id="createMessage" name="createMessage" value="Add"/>      
  </form>
        <!--Opcja dodania pliku -->
        <br>
       <h4>Dodaj plik:</h4>
         <form name="formAdd" id="formAdd" method="POST" action="upload.php" enctype="multipart/form-data">
<label for="fileToUpload">
    	<input type="file" name="fileToUpload" id="fileToUpload" >
  <input type="submit" value="Send"/>
  	</label>
	</form>
        </div>
        </div>
      </div>
   <!--Przegladanie uzytkownika -->
         <div id="uProfile" class="uProfile">
      <h2><?php print "Przeglądasz użytkownika: ". $_GET['userProfile']; 
        //Opcja usuniecia go bedac zalogowanym jako admin
        if($_SESSION['userup'] == "admin")  {
          print "<label for='".$_GET['userID']."'>
	<form method='POST' action='forum.php?&deleteUser=".$_GET['userID']."' enctype='multipart/form-data'>
	<img id='imgDel' class='imgDel' src='./icons/delete.png'>
    <input type='submit' id='".$_GET['userID']."' name='".$_GET['userID']."' value='Delete' style='display:none'></form></label>";     
        print "<label for='blockUser'>
	<form method='POST' action='forum.php?&blockUser=".$_GET['userID']."' enctype='multipart/form-data'>
	<img id='imgBlk' class='imgBlk' src='./icons/block.png'>
    <input type='submit' id='blockUser' name='blockUser' value='Block' style='display:none'></form></label>";
                                            }?></h2>
           <!--Przegladanie uzytkownika - tematy -->
      <h4 id="profileH" class="profileH">Topics</h4>
         <?php
          if(isset($_GET['userProfile'])){
          $use = $_GET['userProfile'];
          $resultMessages2 = mysqli_query($connection, "Select * from topic join user on topic.idu = user.idu where user.login='$use' order by idt asc") or die ("DB error: $dbname");
          while ($message = mysqli_fetch_array ($resultMessages2)){
              $value = $message[2];
    	 print $message[1]." (by <a href='forum.php?topic=$message[6]'>".$message[6]."</a>)"; 
      	if($_SESSION['userid']==$message[4] || $_SESSION['userup'] == "admin")  print "<label for='$message[0]'>
	<form method='POST' action='forum.php?topic=$topicVV&deleteTopic=$message[0]' enctype='multipart/form-data'>
	<img id='imgDel' class='imgDel' src='./icons/delete.png'>
    <input type='submit' id='$message[0]' name='$message[0]' value='Delete' style='display:none'></form></label>";
     print "<br>";}}?>
		<!--Przegladanie uzytkownika - wiadomosci -->
     <h4 id="profileH" class="profileH">Messages</h4>
         <?php
          if(isset($_GET['userProfile'])){
          $use = $_GET['userProfile'];
          $resultMessages2 = mysqli_query($connection, "Select * from message join user on message.idu = user.idu where user.login='$use' order by idm asc") or die ("DB error: $dbname");
          while ($message = mysqli_fetch_array ($resultMessages2)){
             if(end(explode(".",$message[2])) =="png" || end(explode(".",$message[2])) =="jpg" || end(explode(".",$message[2])) =="jpeg" || end(explode(".",$message[2])) =="gif") print "<img src='$message[2]'/>\n";
    else if(end(explode(".",$message[2])) =="mp3") print "<audio controls src='$message[2]'><a href='$message[2]'>Download audio</a></audio>\n";
    else if(end(explode(".",$message[2])) =="mp4") print "<video controls autoplay src='$message[2]'></video>\n";
    else print $message[2]." (by <a href='forum.php?userProfile=$message[6]&userID=$message[4]'>".$message[6]."</a>)"; 
      if($_SESSION['userid']==$message[4] || $_SESSION['userup'] == "admin")  print "<label for='$message[0]'>
	<form method='POST' action='forum.php?deleteMessage=$message[0]' enctype='multipart/form-data'>
	<img id='imgDel' class='imgDel' src='./icons/delete.png'>
    <input type='submit' id='$message[0]' name='$message[0]' value='Delete' style='display:none'></form></label>";
     print "<br>";      
          }
          }
          ?>
<!--Wysylanie prywatnej korespondencji -->  
<h4 id="profileH" class="profileH">Send messange to: <?php print $_GET['userProfile'];  
        ?></h4>
      <div id="sendMessage" class="sendMessage">
        <form method="POST" action="forum.php?userID=<?php echo $_GET['userID']?>" enctype="multipart/form-data">
    <input type="text" name="pMessageText" id="pMessageText" value="Wiadomosc">
    <input type="submit" id="pMessage" name="pMessage" value="Send"/>      
  </form></div></div></td></tr></table>
  <?php
  //Pobranie zmiennych
  $createTopic = $_POST['createTopic'];
  $topicName = $_POST['topicName'];
  $createMessage = $_POST['createMessage'];
  $deleteMessage = $_GET['deleteMessage'];
  $deleteTopic = $_GET['deleteTopic'];
  $deleteUser = $_GET['deleteUser'];
  $userProfile = $_GET['userProfile'];
  $blockUser = $_GET['blockUser'];
    $addL = $_GET['topic'];
    //Tworzenie tematu
    if(isset($createTopic)){
        $userid = $_SESSION['userid'];
		$resultTopic = mysqli_query($connection, "INSERT INTO topic (topicName, idu) VALUES ('$topicName', '$userid');") or die ("DB error: $dbname");
	}
  $messageName = $_POST['messageName'];
  $topicValue = $_GET['topic'];
  $pMessage = $_POST['pMessage'];
  $pMessageText = $_POST['pMessageText'];
  $idfu = $_SESSION['userid'];
  $idtu = $_GET['userID'];
  $_SESSION['top'] = $topicValue;
  //Sprawdzanie profilu
    if(isset($userProfile)){
      print '<script> 
       var labelAdd = document.getElementById("addL");
       labelAdd.style.display = "block";
       var pMessage = document.getElementById("sendMessage");
       var profileH = document.getElementById("profileH");
		profileH.style.display = "block";
		pMessage.style.display = "block";
      </script>';
	}
  else{ 
       echo '<script> 
		var labelAdd = document.getElementById("addL");
		var pMessage = document.getElementById("sendMessage");
		pMessage.style.display = "block";
		uProfile.style.display = "none";
		profileH.style.display = "none";
       labelAdd.style.display = "none";
       </script>'; 
  }
    //Tworzenie wiadomosci oraz sprawdzanie przeklenstwa
      if(isset($createMessage)){
        if(stripos($messageName, "cholera") !== false){
          $resultMessages3 = mysqli_query($connection, "Select * from user where idu='".$_SESSION['userid']."' order by idu asc") or die ("DB error: $dbname");
           while ($messag = mysqli_fetch_array ($resultMessages3)){
             if($messag[4]==0){
                $messageName =  date("Y-m-d h:i:s")." Usunięto post użytkownika ". $_SESSION['login'].", ze względu na użyty wulgaryzm. Jest to oficjalne ostrzeżenie dla użytkownika ". $_SESSION['login'].", przy kolejnym użytym wulgaryzmie użytkownik ten zostanie zabanowany.";
              $resultMessage = mysqli_query($connection, "update user set warning=1 where idu='".$_SESSION['userid']."';") or die ("DB error: $dbname");
             }
             else if($messag[4]==1){
                $messageName =  date("Y-m-d h:i:s")." Usunięto post użytkownika ". $_SESSION['login'].", ze względu na użyty wulgaryzm. Użytkownik ". $_SESSION['login']." został zabanowany z powodu używania wulgaryzmów.";
              $resultMessage = mysqli_query($connection, "update user set warning=2 where idu='".$_SESSION['userid']."';") or die ("DB error: $dbname");
                $resultMessage = mysqli_query($connection, "update user set block=1 where idu='".$_SESSION['userid']."';") or die ("DB error: $dbname");
             }
           }}
      $topicV = $_SESSION['top'];
      $userid = $_SESSION['userid'];
	$resultMessage = mysqli_query($connection, "INSERT INTO message (message,topic, idu) VALUES ('$messageName','$topicV', '$userid');") or die ("DB error: $dbname");
	}
    //Wysylanie prywatnej korespondencji oraz sprawdzanie przeklenstw
      if(isset($pMessage)){
        if(stripos($messageName, "cholera") !== false){
          $resultMessages3 = mysqli_query($connection, "Select * from user where idu='".$_SESSION['userid']."' order by idu asc") or die ("DB error: $dbname");
           while ($messag = mysqli_fetch_array ($resultMessages3)){
             if($messag[4]==0){
                $messageName =  date("Y-m-d h:i:s")." Usunięto post użytkownika ". $_SESSION['login'].", ze względu na użyty wulgaryzm. Jest to oficjalne ostrzeżenie dla użytkownika ". $_SESSION['login'].", przy kolejnym użytym wulgaryzmie użytkownik ten zostanie zabanowany.";
              $resultMessage = mysqli_query($connection, "update user set warning=1 where idu='".$_SESSION['userid']."';") or die ("DB error: $dbname");
             }
             else if($messag[4]==1){
                $messageName =  date("Y-m-d h:i:s")." Usunięto post użytkownika ". $_SESSION['login'].", ze względu na użyty wulgaryzm. Użytkownik ". $_SESSION['login']." został zabanowany z powodu używania wulgaryzmów.";
              $resultMessage = mysqli_query($connection, "update user set warning=2 where idu='".$_SESSION['userid']."';") or die ("DB error: $dbname");
                $resultMessage = mysqli_query($connection, "update user set block=1 where idu='".$_SESSION['userid']."';") or die ("DB error: $dbname");
             }
           }
}        
  $idtu = $_GET['userID'];
    print $idfu." - ".$idtu." - ".$pMessageText;
		$resultMessage = mysqli_query($connection, "INSERT INTO privateMessage (idfu,idtu, message) VALUES ('$idfu','$idtu', '$pMessageText');") or die ("DB error: $dbname");
	}
  	//Usuwanie tematu
  	if(isset($deleteTopic)){
        	$resultDeleteMessagesFromTopic = mysqli_query($connection, "Delete from message where topic=$deleteTopic;") or die ("DB2 error: $dbname");
  		$resultDeleteTopic = mysqli_query($connection, "Delete from topic where idt=$deleteTopic;") or die ("DB error: $resultDeleteTopic");
}
	//Usuwanie wiadomosci
    if(isset($deleteMessage)){
  		$result = mysqli_query($connection, "Delete from message where idm='".$_GET['deleteMessage']."';") or die ("DB error: $dbname");
}
    //Usuwanie uzytkownika
       if(isset($deleteUser)){
       	$resultDeleteMessagesFromTopic = mysqli_query($connection, "Delete from message where idu='".$_GET['deleteUser']."'") or die ("DB2 error: $dbname");
  		$resultDeleteTopic = mysqli_query($connection, "Delete from topic where idu='".$_GET['deleteUser']."'") or die ("DB error: $resultDeleteTopic");
		$resultDeleteUser = mysqli_query($connection, "Delete from user where idu='".$_GET['deleteUser']."';") or die ("DB2 error: $dbname");
}     
   //Blokowanie uzytkownika
  	if(isset($blockUser)){
  		$resultMessage = mysqli_query($connection, "update user set block=1 where idu='".$_GET['blockUser']."';") or die ("DB error: $dbname");
}            
    //Wyswietlanie elementow  
      if(isset($uProfile)){
      echo '<script> 
      
      var userProfile = document.getElementById("userProfile");
      var lorem = document.getElementById("lorem");
       userProfile.style.display = "block";
    lorem.style.display = "none";
       
      </script>';
	}
  else {
         echo '<script> 
       var lorem = document.getElementById("lorem");
      var userProfile = document.getElementById("userProfile");
       userProfile.style.display = "none";
    lorem.style.display = "block";
      var lorem = document.getElementById("lorem");
      //lorem.style.display = "none";
      </script>'; 
  }
   if($_SESSION["login"]!="GOSC"){ 
      echo '<script> 
       var addTopic = document.getElementById("addTopic");
      addTopic.style.display = "block";
      </script>';
  }
              else {
                echo '<script> 
      var addTopic = document.getElementById("addTopic");
      addTopic.style.display = "none";
      </script>';
              
   }
  if(isset($addL) && $_SESSION["login"]!="GOSC"){
    echo '<script> 
      var showBM = document.getElementById("showBM");
      showBM.style.display = "block";
      </script>';
  }
              else {
                echo '<script> 
      var showBM = document.getElementById("showBM");
      showBM.style.display = "none";
      </script>';
              }
  ?>
  </BODY>
  </HTML>

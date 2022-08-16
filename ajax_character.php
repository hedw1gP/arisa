<?php

   require('db_config.php');
   // $sql = "SELECT Name FROM character_charactercards WHERE Name LIKE '%".$_GET['characterName']."%';"; 
   $command = "SELECT * FROM character_charactercards WHERE Name LIKE '%".$_GET['characterName']."%';";
   $result = mysqli_query($mysqli, $command);
   $json = [];
   while($row = mysqli_fetch_array($result)) {
        $json[$row['Id']] = $row['Name'];
   }
   echo json_encode($json, JSON_UNESCAPED_UNICODE);
?>
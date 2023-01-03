<?php 

   $dbhost = 'localhost';
   $dbname = 'blood_donation';
   $dbuser = 'root';
   $dbpass = 'mysql';

   try{
      $db = new PDO("mysql:host = {$dbhost};dbname={$dbname}",$dbuser,$dbpass);
      $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   }
   catch(PDOException $e){
      echo "Failed to connect database:".$e->getMessage();
   }

?>
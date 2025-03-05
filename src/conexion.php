<?php
  class Conexion {
    
    function ConexionDB() {
      $host = 'localhost';
      $user = 'root';
      $password = '';
      $dbName = 'mydb';

      $connexion = null;

      try{
        $connexion = new PDO ("pgsql:host=$host;dbname=$dbName", $user, $password);
        echo " Sucessfull connection";
      }
      catch (PDOException $error){
        echo "Error: " . $error->getMessage();
      }
      return $connexion;
    }
  }
?>
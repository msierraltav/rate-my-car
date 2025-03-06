<?php
  class Conexion {


    function ConexionDB() {


      $hosturl = getenv('POSTGRES_HOST');
      $hostport = getenv('POSTGRES_PORT');

      $host = $hosturl . ":" . $hostport;
      $user = getenv('POSTGRES_USER');
      $password = getenv('POSTGRES_PASSWORD');
      $dbName = getenv('POSTGRES_DB');
      $dbPort = getenv('POSTGRES_PORT');
      $connexion = null;

      try{
        $connexion = new PDO ("pgsql:host=".$hosturl."; dbname=".$dbName, $user, $password);
        echo " Sucessfull connection";
      }
      catch (PDOException $error){
        echo "Error: " . $error->getMessage();
      }
      return $connexion;
    }
  }
?>
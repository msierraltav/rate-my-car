<?php
  class Conexion {

    function ConexionDB() {

      $hosturl = getenv('POSTGRES_HOST');
      $hostport = getenv('POSTGRES_PORT');

      $user = getenv('POSTGRES_USER');
      $password = getenv('POSTGRES_PASSWORD');
      $dbName = getenv('POSTGRES_DB');
      $dbPort = getenv('POSTGRES_PORT');
      $connexion = null;

      $connectionString = "pgsql:host=$hosturl;port=$hostport;dbname=$dbName;user=$user;password=$password";
      
      try{
        $connexion = new PDO ($connectionString);
        echo " Sucessfull connection";
      }
      catch (PDOException $error){
        echo "Error: " . $error->getMessage();
      }
      return $connexion;
    }
  }
?>
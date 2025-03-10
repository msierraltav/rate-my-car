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
      }
      catch (PDOException $error){
        echo "Error: " . $error->getMessage();
      }
      return $connexion;
    }

    function query($query) {
      $conn = $this->ConexionDB();
      $stmt = $conn->query($query);
      $data = [];
      if ($stmt) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
          $data[] = $row;
        }
      }
      $conn = null; // end the connection
      return $data;
    }

    function queryBuilder($year, $manufacturer, $model, $mileage) {

      $query = "SELECT * FROM vehicles";

      if (!empty($year)) {
        $query .= " WHERE model_year='$year'";
      }

      if (!empty($manufacturer)) {
        $query .= " AND LOWER(manufacturer) LIKE '%$manufacturer%'";
      }

      if (!empty($model)) {
        $query .= " AND  LOWER(vehicle_model) LIKE '%$model%'";
      }

      if ($mileage >= 0) {
        $query .= " AND listing_mileage = '$mileage'";
      }

      $query .= " AND listing_price IS NOT NULL AND listing_mileage IS NOT NULL";

      return $this->query($query);
    }
  }
?>
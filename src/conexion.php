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

    function execQuery($query) {
      $conn = $this->ConexionDB();
      $stmt = $conn->query($query);
      $data = [];
      if ($stmt) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
          $data[] = $row;
        }
      }
      $conn = null;
      return $data;
    }

    function queryBuilder($year, $manufacturer, $model, $mileage, $limit = 0, $where = true, $between = false) {

      $query = $where ? "SELECT * FROM vehicle_inventory" : "FROM vehicle_inventory";

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
        $lower = $mileage * 0.80;
        $upper = $mileage * 1.20;
        $query .= $between 
          ? " AND listing_mileage BETWEEN '" . $lower . "' AND '". $upper ."'"
          : " AND listing_mileage = '". $mileage . "'";
      }

      $query .= " AND listing_price IS NOT NULL AND listing_mileage IS NOT NULL";
      
      $query .= $limit == 0 ? "" : " LIMIT $limit" ;

      return $query;
    }

    function linearRegresion($year, $manufacturer, $model, $mileage){

      if($mileage == 0){
        $average_query = "SELECT AVG(listing_price) average " . $this->queryBuilder($year, $manufacturer, $model, $mileage , 0, false, true);
        $average_resuslt = $this->execQuery($average_query);
        $average = $average_resuslt[0]['average'];
        return $average;
      }
      else{
        $slope_query = "SELECT regr_slope(listing_price, listing_mileage) slope " . $this->queryBuilder($year, $manufacturer, $model, $mileage , 0, false, true);
        $intercept_query = "SELECT regr_intercept(listing_price, listing_mileage) intercept " . $this->queryBuilder($year, $manufacturer, $model, $mileage , 0, false, true);
  
        $slope_result = $this->execQuery($slope_query);
        $intercept_result = $this->execQuery($intercept_query);
  
        $slope = $slope_result[0]['slope'];
        $intercept = $intercept_result[0]['intercept'];
  
        $predicted_price = $intercept + ($slope * $mileage);
        return $predicted_price;
      }
    }

    function getVehicleList($year, $manufacturer, $model, $mileage){
      $cars_query = $this->queryBuilder($year, $manufacturer, $model, $mileage, 100, true, true);
      $result = $this->execQuery($cars_query);
      return $result;
    }
  }
?>
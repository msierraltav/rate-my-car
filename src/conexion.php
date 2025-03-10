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

    function queryBuilder($year, $manufacturer, $model, $mileage, $limit = 0, $where = true) {

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
        $query .= " AND listing_mileage = '$mileage'";
      }

      $query .= " AND listing_price IS NOT NULL AND listing_mileage IS NOT NULL";

      // zero is limitless  (???)
      $query .= $limit == 0 ? "" : " LIMIT $limit" ;

      return $query;
    }

    function linearRegresion($year, $manufacturer, $model, $mileage){

      $slope_query = "SELECT regr_slope(listing_price, listing_mileage) slope " . $this->queryBuilder($year, $manufacturer, $model, $mileage, 0, false);
      $intercept_query = "SELECT regr_intercept(listing_price, listing_mileage) intercept " . $this->queryBuilder($year, $manufacturer, $model, $mileage, 0, false);

      $slope_result = $this->execQuery($slope_query);
      $intercept_result = $this->execQuery($intercept_query);

      $slope = $slope_result[0]['slope'];
      $intercept = $intercept_result[0]['intercept'];

      $predicted_price = $intercept + ($slope * $mileage);
      return $predicted_price;
    }

    function getVehicleList($year, $manufacturer, $model, $mileage){
      $cars_query = $this->queryBuilder($year, $manufacturer, $model, $mileage, 100);
      $result = $this->execQuery($cars_query);
      return $result;
    }

    function linearRegresionSecondVersion($year, $manufacturer, $model, $mileage){

      $result = [
        'cars' => [],
        'predicted_price' => 0,
        'debug' => ''
      ];

      $query = $this->queryBuilder($year, $manufacturer, $model, $mileage, 100);

      //$result['debug'] = $query;

      $result['cars'] = $this->execQuery($query);
      
      // lets calculate the predicted price using linear regression
      // X = mileage, Y = price
      $total = count($result['cars']);
      $mileage_sum = 0;
      $price_sum = 0;
      $mileage_avg = 0;
      $price_avg = 0;

      foreach ($result['cars'] as $car) {
        $mileage_sum += $car['listing_mileage'];
        $price_sum += $car['listing_price'];
      }

      $mileage_avg = $mileage_sum / $total;
      $price_avg = $price_sum / $total;

      $numerator = 0;
      $denominator = 0;

      foreach ($result['cars'] as $car) {
        $numerator += ($car['listing_mileage'] - $mileage_avg) * ($car['listing_price'] - $price_avg);
        $denominator += ($car['listing_mileage'] - $mileage_avg) * ($car['listing_mileage'] - $mileage_avg);
      }

      $beta1 = $numerator / $denominator;
      $beta0 = $price_avg - $beta1 * $mileage_avg;

      $result['predicted_price'] = $beta0 + $beta1 * $mileage;

      return $result;

    }
  }
?>
<?php
  include_once('conexion.php');


  $json = file_get_contents("php://input");
  $data = json_decode($json);
  $year = $data->year;
  $manufacturer = $data->manufacturer;
  $model = $data->model;
  $mileage = $data->mileage;


  $conexion = new Conexion();

  $prediction = $conexion->linearRegresion($year, $manufacturer, $model, $mileage);
  // get a max of 100 vehicles with the same year, manufacturer and model.
  $result = $conexion->getVehicleList($year, $manufacturer, $model, $mileage);

  $data = [];
  $predictedPrice = 0;

  if($prediction){
    $predictedPrice = $prediction;
  }

  if($result){
    $data = $result;
  }

  $mileageMessage = "";
  if($mileage > 0){
    $lower_mileage = $mileage * 0.80;
    $upper_mileage = $mileage * 1.20;
    $mileageMessage = "Note: showing ". number_format(count($data)) ." cars with similar mileage between $lower_mileage and $upper_mileage";
  }
?>
<div class="results">
  <div>
    <div class="prediction">Price prediction: $<?php echo number_format($predictedPrice, 2); ?></div>
    <div class="notes"><?php echo $mileageMessage; ?></div>
  </div>
  <?php if (count($data) > 0): ?>
    <table class="result-table">
      <thead>
        <tr>
          <th>Vehicle</th>
          <th>Price</th>
          <th>Mileage</th>
          <th>Location</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($data as $vehicle): ?>

          <?php
            $vehicle_name = $vehicle['model_year'].' '.$vehicle['manufacturer'].' '.$vehicle['vehicle_model'];
          ?>

          <tr>
            <td><?php echo htmlspecialchars($vehicle_name); ?></td>
            <td><?php echo $vehicle['listing_price'] !== null ? '$'.number_format($vehicle['listing_price'], 2) : 'No price'; ?></td>
            <td><?php echo $vehicle['listing_mileage'] !== null ? number_format($vehicle['listing_mileage']) : 'No Mileage registered'; ?></td>
            <td class="location">
              <p><?php echo $vehicle['dealer_city'] !== null ? htmlspecialchars($vehicle['dealer_city']) : '';?></p>
              <p><?php echo $vehicle['dealer_state'] !== null ? htmlspecialchars($vehicle['dealer_state']) : '';?></p>
          </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p>No vehicle data found.</p>
  <?php endif; ?>
</div>

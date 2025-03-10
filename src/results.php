<?php
  include_once('conexion.php');


  $json = file_get_contents("php://input");
  $data = json_decode($json);
  $year = $data->year;
  $manufacturer = $data->manufacturer;
  $model = $data->model;
  $mileage = $data->mileage;


  $conexion = new Conexion();
  $result = $conexion->queryBuilder($year, $manufacturer, $model, $mileage);
  $data = [];

  if($result){
    $data = $result;
  }
?>
<div class="results">
  <?php if (count($data) > 0): ?>
    <table class="result-table">
      <thead>
        <tr>
          <th>VIN</th>
          <th>Year</th>
          <th>Manufacturer</th>
          <th>Model</th>
          <th>Price</th>
          <th>Mileage</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($data as $vehicle): ?>

          <tr>
            <td><?php echo htmlspecialchars($vehicle['vin']); ?></td>
            <td><?php echo htmlspecialchars($vehicle['model_year']); ?></td>
            <td><?php echo htmlspecialchars($vehicle['manufacturer']); ?></td>
            <td><?php echo htmlspecialchars($vehicle['vehicle_model']); ?></td>
            <td><?php echo $vehicle['listing_price'] !== null ? '$'.number_format($vehicle['listing_price'], 2) : 'No price'; ?></td>
            <td><?php echo $vehicle['listing_mileage'] !== null ? number_format($vehicle['listing_mileage']) : 'No Mileage registered'; ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p>No vehicle data found.</p>
  <?php endif; ?>
</div>

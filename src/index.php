<!DOCTYPE html>
<html>
    <head>
        <title>Rate my Car</title>
        <link rel="stylesheet" href="styles.css">
        <script src="scripts.js"></script>
    </head>
    <body>
        <h1>Rate my Car</h1>

        <?php
          include_once('conexion.php');
          $conexion = new Conexion();
          $conexion->ConexionDB();
        ?>
        
        <section>
          <div class="input-form">
            <form method="post" action="#">
              <div class="search-inputs"> 
                <div class="input-group">
                  <label for="vehicle">Car</label>
                  <input type="text" id="vehicle" placeholder="2015 Toyota Camry CE ">
                </div>
                <div class="input-group">
                  <label for="mileage">Mileage</label>
                  <input type="text" id="mileage" placeholder="6544">
                </div>
                <input class="submit-button" type="submit" value="Submit" onclick="submitForm()">
              </div>
            </form>
          </div>
        </section>
    </body>
</html>
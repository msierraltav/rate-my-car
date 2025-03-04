<!DOCTYPE html>
<html>
    <head>
        <title>Rate my Car</title>
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
        <h1>Rate my Car</h1>
        <section>
          <div class="input-form">
            <form>
              <div class="search-inputs"> 
                <div class="input-group">
                  <label for="vehicle">Car</label>
                  <input type="text" id="vehicle" placeholder="2015 Toyota Camry CE ">
                </div>
                <div class="input-group">
                  <label for="mileage">Mileage</label>
                  <input type="text" id="mileage" placeholder="Car model">
                </div>
                <input class="submit-button" type="submit" value="Submit">
              </div>
            </form>
          </div>
        </section>
        <?php
            $saludo = 'Hola mundo';
            echo $saludo;
        ?>
    </body>
</html>
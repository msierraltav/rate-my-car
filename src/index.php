<!DOCTYPE html>
<html>
    <head>
        <title>Rate my Car</title>
        <link rel="stylesheet" href="styles.css">
        <script src="scripts.js"></script>
    </head>
    <body>
        <h1>Rate my Car</h1>
        <section class="tips">
        <div>
          <p>Tips for your search: </p>
        </div>
        <div>
           <ul>
            <li>Follow the order : year , maker , model</li>
            <li>Enter the Year</li>
            <li>Enter the Maker with '-' separated  eg: Rolls-Royce</li>
            <li>The model is opcional but recomended.</li>
          </ul>
        </div>
        </section>

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
                <input class="submit-button" type="submit" value="Submit" onclick="parseCarInput(document.getElementById('vehicle').value, document.getElementById('mileage').value)">
              </div>
            </form>
          </div>
        </section>
        <section>
          <div id="results">
          </div>
        </section>

    </body>
</html>
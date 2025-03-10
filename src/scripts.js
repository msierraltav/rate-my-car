function parseCarInput(vehicle, mileage) {

  if (typeof event !== 'undefined') {
    event.preventDefault();
  }

  if (mileage !== '' && (isNaN(parseInt(mileage, 10)) || parseInt(mileage, 10) < 0)) {
    alert("Mileage must be a number greater than zero or empty");
    return;
  }

  let car = {
    year: 0,
    manufacturer: '',
    model: '',
    mileage: -1
  };

  const yearMatch = vehicle.match(/\b(\d{4})\b/);
  if (yearMatch) {
    car.year = parseInt(yearMatch[1], 10);
    vehicle = vehicle.replace(yearMatch[1], '').trim();
  }

  const manufacturerPattern = /^(\w+(-\w+)?)\b/;
  const matches = vehicle.match(manufacturerPattern);

  if (matches && matches[1]) {
    car.manufacturer = (matches[1]).toLowerCase();
    const modelPart = (vehicle.substring(matches[0].length).trim()).toLowerCase();
    if (modelPart) {
      car.model = modelPart;
    }
  } else if (vehicle.trim()) {
    car.manufacturer = (vehicle.trim()).toLowerCase();
  }

  car.mileage = mileage === '' ? -1 : parseInt(mileage, 10);

  if (car.year === 0 || car.manufacturer === '') {
    alert("Please enter almost a year and a manufacturer");
  }
  else {
    submitForm(car);
  }
}
function submitForm(car) {

  const resultsContainer = document.getElementById("results");
  resultsContainer.innerHTML = "Loading data...";

  const getData = async (car) => {
    try {
      const response = await fetch("results.php", {
        method: "POST",
        body: JSON.stringify(car),
        headers: {
         "Content-Type": "application/json; charset=UTF-8"
        }
      });
      
      if (response.ok) {
        const htmlContent = await response.text();
        return htmlContent;
      } else {
        throw new Error(`Error: ${response.status}`);
      }
    } catch (error) {
      console.error("Fetch error:", error);
      return "Error loading data";
    }
  };

  getData(car)
    .then((htmlContent) => {
      resultsContainer.innerHTML = htmlContent;
    })
    .catch((error) => {
      console.error("Error:", error);
      resultsContainer.innerHTML = "Error loading data";
    });
}
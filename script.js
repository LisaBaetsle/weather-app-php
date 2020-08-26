//When submit button is clicked
document.getElementById('submit').addEventListener('click', handleSubmitButton)

// When enter is pressed in submit button
document.getElementById('cityInput').addEventListener("keyup", function (event) {
  if (event.keyCode === 13) {
    event.preventDefault();
    handleSubmitButton();
  }
});

// function to handle the sumbit when clicked on button
function handleSubmitButton() {
  document.getElementById('spinner').style.display = 'block';

  let cityInput = document.getElementById('cityInput').value;

  const api = `https://api.openweathermap.org/data/2.5/forecast?q=${cityInput}&units=metric&appid=655a19af3d0a8572719255b11fb9c8d0`

  fetch(api)
    .then(response => {
      document.getElementById('spinner').style.display = 'none';
      return response.json();
    })
    .then(handleFetch)
}


// Handle the fetch
function handleFetch(data) {
  // BUILD CHART WITH TEMPERATURE
  let ctx = document.getElementById('myChart');
  buildChart(ctx, data);
}


// Build the chart for the temperature
function buildChart(ctx, data) {
  let myChart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: [`${data.list[0].dt_txt.slice(10, 16)}`, `${data.list[1].dt_txt.slice(10, 16)}`, `${data.list[2].dt_txt.slice(10, 16)}`, `${data.list[3].dt_txt.slice(10, 16)}`, `${data.list[4].dt_txt.slice(10, 16)}`, `${data.list[5].dt_txt.slice(10, 16)}`, `${data.list[6].dt_txt.slice(10, 16)}`, `${data.list[7].dt_txt.slice(10, 16)}`, `${data.list[8].dt_txt.slice(10, 16)}`],
      datasets: [{
        data: [Math.round(data.list[0].main.temp), Math.round(data.list[1].main.temp), Math.round(data.list[2].main.temp), Math.round(data.list[3].main.temp), Math.round(data.list[4].main.temp), Math.round(data.list[5].main.temp), Math.round(data.list[6].main.temp), Math.round(data.list[7].main.temp), Math.round(data.list[8].main.temp)],
        backgroundColor: 'rgba(255, 66, 14, 0.7)',
        borderColor: 'rgba(255, 66, 14, 1)',
        borderWidth: 1,
      }, ]
    },
    options: {
      legend: {
        display: false,
      },
      scales: {
        yAxes: [{
          gridLines: {
            display: false,
            drawBorder: false,
          },
          ticks: {
            min: Math.round(data.list[0].main.temp) - 15,
            max: Math.round(data.list[0].main.temp) + 15,
            fontColor: 'rgba(255, 255, 255, 0.6)',
            display: false,
          }
        }],
        xAxes: [{
          gridLines: {
            display: false
          },
          ticks: {
            fontColor: 'rgba(255, 255, 255, 0.6)'
          }
        }],
      },
      plugins: {
        datalabels: {
          color: 'rgba(255, 66, 14, 1)',
          align: 'top',
          labels: {
            title: {
              font: {
                size: '14',
              }
            }
          }
        },

      }
    },
  });
}
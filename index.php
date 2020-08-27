<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="styles/style.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js@2.7.3/dist/Chart.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.7.0"></script>
  <title>Weather App - PHP style</title>
</head>

<body>

  <div class="container">

    <!-- Form for input of the user -->
    <div class="input-container">
      <form id="myForm" action="" method="post">
        <label for="city">Enter the city you're curious for:</label> </br>
        <input type="text" id="cityInput" name="cityInput"> </br>
        <input type="submit" value="SUBMIT" id='submit'>
      </form>
    </div>

    <!-- Get input user from POST and make the url of the API, then get the contents of the API -->
    <?php
    $url = "https://api.openweathermap.org/data/2.5/forecast?q=" . $_POST["cityInput"] . "&units=metric&appid=655a19af3d0a8572719255b11fb9c8d0";
    $contents = file_get_contents($url);
    $results = json_decode($contents, true);
    ?>

    <!-- HEADER with name of city & country and the date of today -->
    <div class="header">
      <p id="cityCountryName">
        <?php
        echo ($results["city"]["name"] . ", " . $results["city"]["country"]);
        ?>
      </p>
      <p id="date">
        <?php
        echo (date("l, F d, Y"));
        ?>
      </p>
    </div>

    <!-- info for TODAY (temperature, icon and description) -->
    <div class="mainDayInfo">
      <p id="temperature">
        <?php
        echo (round($results['list'][0]["main"]["temp"]) . "°");
        ?>
      </p>
      <img src="http://openweathermap.org/img/wn/<?php echo ($results['list'][0]["weather"][0]["icon"]) ?>@4x.png" id="weatherIcon">
      <p id="description">
        <?php
        echo ($results['list'][0]["weather"][0]["description"]);
        ?>
      </p>
    </div>

    <!-- extra info for TODAY (windspeed and humidity) -->
    <div class="mainDayExtra">
      <div class="row">
        <div class="col-s-6 dayExtra">
          <p id="windSpeed">
            <?php
            // print_r ($results['list'][0]["wind"]);
            echo ("Wind: " . round($results['list'][0]["wind"]["speed"]) . " km/h");
            ?>
          </p>
        </div>
        <div class="col-s-6 dayExtra">
          <p id="humidity">
            <?php
            // print_r ($results['list'][0]["main"]);
            echo ("Humidity: " . round($results['list'][0]["main"]["humidity"]) . "%");
            ?>
          </p>
        </div>
      </div>
    </div>

    <!-- space for the CHART, using JavaScript -->
    <canvas id="myChart" width="700px" height="350px"></canvas>

    <!-- Info for the OTHER DAYS (name of day, icon, temp_min & temp-max) -->
    <div class="otherDays">
      <div class="row">
        <!-- DAY ONE -->
        <div class="col-s-3 col-xs-6 day" id="dayOne">
          <p id="dayOneName">
            <?php
            echo (date('l', strtotime(' +1 day')));
            populateTheNextFourDays();
            $firstDataPointID;
            ?>
          </p>
          <img src="http://openweathermap.org/img/wn/<?php echo ($results['list'][$firstDataPointID]["weather"][0]["icon"]) ?>@4x.png" id="weatherIcon" class="iconOtherDays">
          <p id="dayOneMinMaxTemp">
            <?php
            echo (round($results['list'][$firstDataPointID - 3]["main"]["temp_min"]) . "° " . round($results['list'][$firstDataPointID + 1]["main"]["temp_max"]) . "°");
            ?>
          </p>
        </div>

        <!-- DAY TWO -->
        <div class="col-s-3 col-xs-6 day" id="dayTwo">
          <p id="dayTwoName">
            <?php
            echo (date('l', strtotime(' +2 day')));
            populateTheNextFourDays();
            $firstDataPointID += 8;
            ?>
          </p>
          <img src="http://openweathermap.org/img/wn/<?php echo ($results['list'][16]["weather"][0]["icon"]) ?>@4x.png" id="weatherIcon" class="iconOtherDays">
          <p id="dayTwoMinMaxTemp">
            <?php
            echo (round($results['list'][$firstDataPointID - 3]["main"]["temp_min"]) . "° " . round($results['list'][$firstDataPointID + 1]["main"]["temp_max"]) . "°");
            ?>
          </p>
        </div>

        <!-- DAY THREE -->
        <div class="col-s-3 col-xs-6 day" id="dayThree">
          <p id="dayThreeName">
            <?php
            echo (date('l', strtotime(' +3 day')));
            populateTheNextFourDays();
            $firstDataPointID += 16;
            ?>
          </p>
          <img src="http://openweathermap.org/img/wn/<?php echo ($results['list'][$firstDataPointID]["weather"][0]["icon"]) ?>@4x.png" id="weatherIcon" class="iconOtherDays">
          <p id="dayThreeMinMaxTemp">
            <?php
            echo (round($results['list'][$firstDataPointID - 3]["main"]["temp_min"]) . "° " . round($results['list'][$firstDataPointID + 1]["main"]["temp_max"]) . "°");
            ?>
          </p>
        </div>

        <!-- DAY FOUR -->
        <div class="col-s-3 col-xs-6 day" id="dayFour">
          <p id="dayFourName">
            <?php
            echo (date('l', strtotime(' +4 day')));
            populateTheNextFourDays();
            $firstDataPointID += 24;
            ?>
          </p>
          <img src="http://openweathermap.org/img/wn/<?php echo ($results['list'][$firstDataPointID]["weather"][0]["icon"]) ?>@4x.png" id="weatherIcon" class="iconOtherDays">
          <p id="dayFourMinMaxTemp">
            <?php
            echo (round($results['list'][$firstDataPointID - 3]["main"]["temp_min"]) . "° " . round($results['list'][$firstDataPointID + 1]["main"]["temp_max"]) . "°");
            ?>
          </p>
        </div>
      </div>
    </div>

    <!-- Function to populate the other days. Check the time when city is inputted and change the index of the data to always show the min and max temp of the day and the icon of 12 o'clock -->
    <?php
    function populateTheNextFourDays()
    {
      global $firstDataPointID;
      $hour = date("H");
      if ($hour < 3) {
        $firstDataPointID = 12;
      } else if ($hour < 6) {
        $firstDataPointID = 11;
      } else if ($hour < 9) {
        $firstDataPointID = 10;
      } else if ($hour < 12) {
        $firstDataPointID = 9;
      } else if ($hour < 15) {
        $firstDataPointID = 8;
      } else if ($hour < 18) {
        $firstDataPointID = 7;
      } else if ($hour < 21) {
        $firstDataPointID = 6;
      } else if ($hour < 24) {
        $firstDataPointID = 5;
      }
      // echo ($firstDataPointID. "</br>". $hour. "</br>");
    };
    ?>
  </div>


  <!-- SCRIPT for the chart-->
  <script>
    const labelOne = "<?php echo ($results['list'][0]["dt_txt"]) ?>";
    const labelTwo = "<?php echo ($results['list'][1]["dt_txt"]) ?>";
    const labelThree = "<?php echo ($results['list'][2]["dt_txt"]) ?>";
    const labelFour = "<?php echo ($results['list'][3]["dt_txt"]) ?>";
    const labelFive = "<?php echo ($results['list'][4]["dt_txt"]) ?>";
    const labelSix = "<?php echo ($results['list'][5]["dt_txt"]) ?>";
    const labelSeven = "<?php echo ($results['list'][6]["dt_txt"]) ?>";
    const labelEight = "<?php echo ($results['list'][7]["dt_txt"]) ?>";
    const labelNine = "<?php echo ($results['list'][8]["dt_txt"]) ?>";

    const datasetOne = <?php echo (round($results['list'][0]["main"]["temp"])) ?>;
    const datasetTwo = <?php echo (round($results['list'][1]["main"]["temp"])) ?>;
    const datasetThree = <?php echo (round($results['list'][2]["main"]["temp"])) ?>;
    const datasetFour = <?php echo (round($results['list'][3]["main"]["temp"])) ?>;
    const datasetFive = <?php echo (round($results['list'][4]["main"]["temp"])) ?>;
    const datasetSix = <?php echo (round($results['list'][5]["main"]["temp"])) ?>;
    const datasetSeven = <?php echo (round($results['list'][6]["main"]["temp"])) ?>;
    const datasetEight = <?php echo (round($results['list'][7]["main"]["temp"])) ?>;
    const datasetNine = <?php echo (round($results['list'][8]["main"]["temp"])) ?>;


    // Build a chart
    let ctx = document.getElementById('myChart');
    let myChart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: [`${labelOne.slice(11, 16)}`, `${labelTwo.slice(11, 16)}`, `${labelThree.slice(11, 16)}`, `${labelFour.slice(11, 16)}`, `${labelFive.slice(11, 16)}`, `${labelSix.slice(11, 16)}`, `${labelSeven.slice(11, 16)}`, `${labelEight.slice(11, 16)}`, `${labelNine.slice(11, 16)}`],
        datasets: [{
          data: [datasetOne, datasetTwo, datasetThree, datasetFour, datasetFive, datasetSix, datasetSeven, datasetEight, datasetNine],
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
              min: datasetOne - 15,
              max: datasetOne + 15,
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
  </script>

</body>
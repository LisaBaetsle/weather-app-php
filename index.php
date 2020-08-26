<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@100;300;400;500;700;900&display=swap"
    rel="stylesheet">
  <link rel="stylesheet" href="styles/style.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js@2.7.3/dist/Chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.7.0"></script>
  <title>Weather App - PHP style</title>
</head>

<body>

<?php
$url = 'https://api.openweathermap.org/data/2.5/forecast?q=Ghent&units=metric&appid=655a19af3d0a8572719255b11fb9c8d0';
$contents = file_get_contents($url);
$results = json_decode($contents, true);
?>

  <div class="container">

    <div class="input-container">
    <form action="" method="post">
    <label for="city">Enter the city you're curious for:</label> </br>
      <input type="text" id="cityInput" name="cityInput" placeholder="Ghent" value="Ghent"> </br>
      <input type="submit" value="SUBMIT" id='submit'>
    </form>
    </div>

    <?php 
    $url = "https://api.openweathermap.org/data/2.5/forecast?q=". $_POST["cityInput"]. "&units=metric&appid=655a19af3d0a8572719255b11fb9c8d0";
    $contents = file_get_contents($url);
    $results = json_decode($contents, true); 
    ?>

    <div id="spinner">Loading...</div>

    <div class="header">
      <p id="cityCountryName">
      <?php
        echo($results["city"]["name"]. ", ". $results["city"]["country"]);
        ?> 
      </p>
      <p id="date">
        <?php
        echo(date("l, F d, Y"));
        ?>
      </p>
    </div>

    <div class="mainDayInfo">
      <p id="temperature"> 
        <?php 
        echo(round($results['list'][0]["main"]["temp"]). "°"); 
        ?> 
      </p>
      <img src="http://openweathermap.org/img/wn/<?php echo ($results['list'][0]["weather"][0]["icon"]) ?>@4x.png" id="weatherIcon">
      <p id="description"> 
        <?php
        echo($results['list'][0]["weather"][0]["description"]);
        ?> 
    </p>
    </div>

    <div class="mainDayExtra">
      <div class="row">
        <div class="col-s-6 dayExtra">
          <p id="windSpeed">
            <?php
            // print_r ($results['list'][0]["wind"]);
            echo("Wind: ". round($results['list'][0]["wind"]["speed"]). " km/h");
            ?>
          </p>
        </div>
        <div class="col-s-6 dayExtra">
          <p id="humidity">
            <?php
            // print_r ($results['list'][0]["main"]);
            echo("Humidity: ". round($results['list'][0]["main"]["humidity"]). "%");
            ?>
          </p>
        </div>
      </div>
    </div>

    <canvas id="myChart" width="700px" height="350px"></canvas>

    <div class="otherDays">
      <div class="row">
        <div class="col-s-3 col-xs-6 day" id="dayOne">
          <p id="dayOneName">
            <?php
            echo (date('l', strtotime(' +1 day')));
            populateTheNextFourDays();
            $firstDataPointID;
            ?>
        </p>
        <img src="http://openweathermap.org/img/wn/<?php echo ($results['list'][$firstDataPointID]["weather"][0]["icon"]) ?>@4x.png" id="weatherIcon"  class="iconOtherDays">
          <p id="dayOneMinMaxTemp">
            <?php
            echo (round($results['list'][$firstDataPointID - 3]["main"]["temp_min"]). "° ". round($results['list'][$firstDataPointID +1]["main"]["temp_max"]). "°");
            ?>
          </p>
        </div>

        <div class="col-s-3 col-xs-6 day" id="dayTwo">
          <p id="dayTwoName">
            <?php
            echo (date('l', strtotime(' +2 day')));
            populateTheNextFourDays();
            $firstDataPointID+=8;
            ?>
          </p>
          <img src="http://openweathermap.org/img/wn/<?php echo ($results['list'][16]["weather"][0]["icon"]) ?>@4x.png" id="weatherIcon"  class="iconOtherDays">
          <p id="dayTwoMinMaxTemp">
          <?php
            echo (round($results['list'][$firstDataPointID - 3]["main"]["temp_min"]). "° ". round($results['list'][$firstDataPointID +1]["main"]["temp_max"]). "°");
            ?>
          </p>
        </div>

        <div class="col-s-3 col-xs-6 day" id="dayThree">
          <p id="dayThreeName">
            <?php
            echo (date('l', strtotime(' +3 day')));
            populateTheNextFourDays();
            $firstDataPointID+=16;
            ?>
          </p>
          <img src="http://openweathermap.org/img/wn/<?php echo ($results['list'][$firstDataPointID]["weather"][0]["icon"]) ?>@4x.png" id="weatherIcon"  class="iconOtherDays">
          <p id="dayThreeMinMaxTemp">
          <?php
            echo (round($results['list'][$firstDataPointID - 3]["main"]["temp_min"]). "° ". round($results['list'][$firstDataPointID +1]["main"]["temp_max"]). "°");
            ?>
          </p>
        </div>

        <div class="col-s-3 col-xs-6 day" id="dayFour">
          <p id="dayFourName">
            <?php
            echo (date('l', strtotime(' +4 day')));
            populateTheNextFourDays();
            $firstDataPointID+=24;
            ?>
          </p>
          <img src="http://openweathermap.org/img/wn/<?php echo ($results['list'][$firstDataPointID]["weather"][0]["icon"]) ?>@4x.png" id="weatherIcon"  class="iconOtherDays">
          <p id="dayFourMinMaxTemp">
          <?php
            echo (round($results['list'][$firstDataPointID - 3]["main"]["temp_min"]). "° ". round($results['list'][$firstDataPointID +1]["main"]["temp_max"]). "°");
            ?>
          </p>
        </div>
      </div>
    </div>

    <?php
    function populateTheNextFourDays() {
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

  <script src="script.js"></script>

  </body>

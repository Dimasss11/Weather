<?php
date_default_timezone_set('Europe/Kiev');
require_once "connection.php";
require_once "db.php";

$link=mysqli_connect($host, $user, $password, $database)
  or die("Error ". mysqli_error($link));

function searchWeather($name, $value)
{
  $apiKey = "e3f5fe20e8765078cd50e687e25b7571";
  if($value=="city"){
    $cityName=htmlspecialchars($name);
    $apiUrl = "http://api.openweathermap.org/data/2.5/weather?q=".$cityName."&appid=".$apiKey;
  }
  elseif($value=="coords"){
    $apiUrl = "http://api.openweathermap.org/data/2.5/weather?".$name."&appid=".$apiKey;
  }
  $crequest = curl_init();
  curl_setopt($crequest, CURLOPT_HEADER, 0);
  curl_setopt($crequest, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($crequest, CURLOPT_URL, $apiUrl);
  curl_setopt($crequest, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($crequest, CURLOPT_VERBOSE, 0);
  curl_setopt($crequest, CURLOPT_SSL_VERIFYPEER, false);
  $response = curl_exec($crequest);
  curl_close($crequest);
  $data = json_decode($response);
  $data->apiUrl=$apiUrl;
  return $data;  
}

if($_POST['coords_lng']){
  $data=searchWeather($_POST['coords_lng'], "coords");
  $coord=$data->coord->lon."&".$data->coord->lat;
    $weather=new Weather($link, $data->apiUrl, time(), $data->name, $data->sys->country, $coord, $data->id);
    $weather->save();
}

if($_POST['city']){
  $data=searchWeather($_POST['city'], "city");
  if($data->cod==200){
    $coord=$data->coord->lon."&".$data->coord->lat;
    $weather=new Weather($link, $data->apiUrl, time(), $data->name, $data->sys->country, $coord, $data->id);
    $weather->save();
  }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>weather</title>
        <link rel="stylesheet" type="text/css" href='assets/weather.css'/>
        <script src="assets/script.js"></script>
    </head>
    <body>
        <div class="search">
          <form action="" method="POST" onsubmit="searchName(this);return false;">
            <input class="ac_input" type="text" placeholder="name of city" name="city">
            <input id="inputSearch" class="search_city-submit" type="submit" value="Add">
          </form>
        </div>
        <div class="info-weather">
        <?php
        $art=Weather::get($link);
        while($artc=mysqli_fetch_assoc($art)){
         ?> 
          <div class="weather">
          <table>
            <tr>
              <th colspan="2" id=<?php echo $data->id;?>><?php echo $artc['city']; ?></th>
            </tr>
            <?php
              $data=searchWeather($artc['city'], "city");
            ?>
            <tr>
              <td>Wind</td>
              <td id=<?php echo "wind".$artc['city'];?>><?php echo $data->wind->speed; ?> m/s</td>
            </tr>
            <tr>
              <td>temperature min</td>
              <td id=<?php echo "temp_min".$artc['city'];?>><?php echo round($data->main->temp_min-273.15, 1); ?>°C</td>
            </tr>
            <tr>
              <td>temperature max</td>
              <td id=<?php echo "temp_max".$artc['city'];?>><?php echo round($data->main->temp_max-273.15, 1); ?>°C</td>
            </tr>
            <tr>
              <td>clouds</td>
              <td id=<?php echo "clouds".$artc['city'];?>><?php echo $data->clouds->all; ?>%</td>
            </tr>
            <tr>
              <td>humidity</td>
              <td id=<?php echo "humidity".$artc['city'];?>><?php echo $data->main->humidity; ?>%</td>
            </tr>
            <tr>
              <td>sunrise</td>
              <td id=<?php echo "sunrise".$artc['city'];?>><?php echo strftime("%H:%M:%S", $data->sys->sunrise) ?></td>
            </tr>
            <tr>
              <td>sunset</td>
              <td id=<?php echo "sunset".$artc['city'];?>><?php echo strftime("%H:%M:%S", $data->sys->sunset) ?></td>
            </tr>
            <tr>
              <td colspan="2">
                <a href="delete.php?id=<?php echo $artc['id_URL']; ?>">Delete</a>
              </td>
            </tr>
          </table>
          </div>
          <?php 
          }
          ?>
        </div>
    </body>
</html>
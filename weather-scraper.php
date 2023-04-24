<?php
  error_reporting(0);
$weather = "";
$error = "";
// if($_GET["city"]){ 
    // better way -->
    if(array_key_exists('city',$_GET))
    {
    $city = str_replace(' ','',$_GET['city']);

    $file_headers = @get_headers("https://www.weather-forecast.com/locations/".$city."/forecasts/latest");

    if($file_headers[0] == 'HTTP/1.1 404 Not Found'){
        $error = "City couldn't be found";
    }
    else{
        $arrContextOptions = array(
            "ssl" => array(
                "verify_peer" => false,
                "verify_peer_name" => false,
            ),
        );  
        
        $forcastPage = file_get_contents("https://www.weather-forecast.com/locations/".$city."/forecasts/latest", false, stream_context_create($arrContextOptions));
    
    $pageArray = explode('3 days)</div><p class="b-forecast__table-description-content"><span class="phrase">',$forcastPage);

    if(sizeof($pageArray) >1){
    $secondPageArray = explode('</span></p></td>',$pageArray[1]);
    if(sizeof($secondPageArray)>1){
    $weather =  $secondPageArray[0];
    }
    else{
        $error = "City couldn't be found";
    }
    }
    else{
        $error = "City couldn't be found";
    }
}

    }

?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Weather Scraper</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <style>
        html{
            background: url(sunset.avif) no-repeat center center fixed;
            background-size: cover;
        }
        body{
            background:none;
        }
        .container{
            text-align:center;
            margin-top: 100px;
            width:450px;
        }
        input{
            margin:20px 0;
        }
        #weather{
            margin-top: 15px;
        }
    </style>
</head>
  <body>
    <div class="container">
        <h1>What's the weather?</h1>
        <form>
  <div class="mb-3">
    <label for="city" class="form-label">Enter the name of a city</label>
    <input type="text" name="city" class="form-control" id="city" placeholder="Eg. London , Tokyo" 
    value = "<?php
    if(array_key_exists('city',$_GET))
    {
     echo $_GET["city"];
    }
     ?>">
    
  </div>
  <button type="submit" class="btn btn-primary">Submit</button>
</form> 
<div id="weather">
    <?php 
    if($weather){
        echo '<div class="alert alert-success" role="alert">
        '.$weather.'
      </div>';
    }
    else if($error){
        echo '<div class="alert alert-danger" role="alert">
        '.$error.'
      </div>';
    }
    ?>
</div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
  </body>
</html>
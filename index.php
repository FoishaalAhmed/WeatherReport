<?php
include 'config.php';

$limit7 = 7;

$getData = $connect->prepare("SELECT * FROM records ORDER BY id DESC limit $limit7");
$getData->execute();
$data = $getData->fetchAll();

$sunny7    = 0;
$overcast7 = 0;
$rainy7    = 0;

// 7 day
foreach ($data as $row) {
	$sunny7    += $row['sunny'];
	$overcast7 += $row['overcast'];
	$rainy7    += $row['rainy'];
}

$sunnyAvg7    = round($sunny7/$limit7);
$overcastAvg7 = round($overcast7/$limit7);
$rainyAvg7    = round($rainy7/$limit7);

// 3 day
$sunny3    = 0;
$overcast3 = 0;
$rainy3    = 0;
$limit3 = 3;
$i = 0;

foreach ($data as $row) {
	$sunny3    += $row['sunny'];
	$overcast3 += $row['overcast'];
	$rainy3    += $row['rainy'];
    if (++$i == 3) break;
}

$sunnyAvg3    = round($sunny3/$limit3);
$overcastAvg3 = round($overcast3/$limit3);
$rainyAvg3    = round($rainy3/$limit3);

// previous day
$sunnyPrv    = 0;
$overcastPrv = 0;
$rainyPrv    = 0;
$prv = 0;

foreach ($data as $row) {
	$sunnyPrv    += $row['sunny'];
	$overcastPrv += $row['overcast'];
	$rainyPrv    += $row['rainy'];
    if (++$prv == 1) break;
}

// prediction
$sunnyAvg    = round(($sunnyAvg7 + $sunnyAvg3 +  $sunnyPrv)/3);
$overcastAvg = round(($overcastAvg7 + $overcastAvg3 +  $overcastPrv)/3);
$rainyAvg    = round(($rainyAvg7 + $rainyAvg3 +  $rainyPrv)/3);

$prediction = '';

if ($sunnyAvg>$overcastAvg) {
	if($sunnyAvg>$rainyAvg)
		$prediction = 'Tommorrow will be Sunny';
	else
		$prediction = 'Tommorrow will be Rainy';
}
else {
	if ($overcastAvg>$rainyAvg)
		$prediction = 'Tommorrow will be Overcast';
	else
		$prediction = 'Tommorrow will be Rainy';
}

?>

<!doctype html>
<html lang="en">
<head>
	<title>Weather Prediction</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css">
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
</head>
<body>

<div class="container">
  <div class="row">
    <div class="col-sm">

      <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
       <div id="piechart" style="width: 350px; height: 350px;"></div>
		
		<script type="text/javascript">
			      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {

        var data = google.visualization.arrayToDataTable([
          ['Outlook', 'Percentage'],
          ['Sunny',  <?= $sunnyPrv; ?>],
          ['Overcast', <?= $overcastPrv; ?>],
          ['Rainy', <?= $rainyPrv; ?>]
        ]);

        var options = {
          title: 'Last Day'
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
      }
		</script>
   
    </div>
    <div class="col-sm">
       <div id="piechart2" style="width: 350px; height: 350px;"></div>
		
		<script type="text/javascript">
			      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {

        var data = google.visualization.arrayToDataTable([
          ['Outlook', 'Percentage'],
          ['Sunny',  <?= $sunnyAvg3; ?>],
          ['Overcast', <?= $overcastAvg3; ?>],
          ['Rainy', <?= $rainyAvg3; ?>]
        ]);

        var options = {
          title: 'Last 3 Days'
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart2'));

        chart.draw(data, options);
      }
		</script>
    </div>
    <div class="col-sm">
       <div id="piechart3" style="width: 350px; height: 350px;"></div>
		
		<script type="text/javascript">
			      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {

        var data = google.visualization.arrayToDataTable([
          ['Outlook', 'Percentage'],
          ['Sunny',  <?= $sunnyAvg7; ?>],
          ['Overcast', <?= $overcastAvg7; ?>],
          ['Rainy', <?= $rainyAvg7; ?>]
        ]);

        var options = {
          title: 'Last 7 Days'
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart3'));

        chart.draw(data, options);
      }
		</script>
    </div>
  </div>

  <h3 class="text-center"><?= $prediction; ?></h3>

</div>

<!-- Optional JavaScript -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" ></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js""></script>
</body>
</html>
<?php
 include "connection.php";                                              // Соединение с базой данных

 $url=$_SERVER['REQUEST_URI'];
 header("Refresh: 30; URL=$url"); // Refresh the webpage every 5 seconds

$result = $con->query("SELECT id FROM dht11");                   
// echo "Количество строк: $result->num_rows";                             // Вывод количества строк в таблице dht11
?>

<?php
$page = 1;                                                              //  вывод 1 страницы
if (isset($_GET['page'])){                                              // Создание переменной Страница из URL страницы
	$page = $_GET['page'];
}else $page = 1;

$myFile1 = "txt/servo_out.txt";
        $fh = fopen($myFile1, 'r');
        $theData1 = fread($fh, filesize($myFile1));


$count = 30;                                                            //количество записей для вывода в таблицу
$count_chart = 50;                                                      //количество записей для вывода в таблицу

$all_rec = $result->num_rows;                                           //всего записей
$art = ($page * $count) - $count;

$pow=$con->query("SELECT humidity FROM dht11 ORDER BY id DESC LIMIT $count_chart");         	// Вывод ВЛАЖНОСТИ для графика с сортировкой по убыванию
$ch=$con->query("SELECT temperature FROM dht11 ORDER BY id DESC LIMIT $count_chart");          		// Вывод ТЕМПЕРАТУРЫ для графика с сортировкой по убыванию

?>
<!doctype html>
<html>

<head>
	<title>Температура и влажность</title>
    <script src="js/Chart.min.js"></script>
	<script src="js/utils.js"></script>
	<style>
   		body {
    	/* background-image: url(Img/phon.jpg); */
    	}
		canvas {
			-moz-user-select: none;
			-webkit-user-select: none;
			-ms-user-select: none;
		}
		.chart-container {
			width: 650px;													/* Размер графика ВЛАЖНОСТЬ */
			margin-left: 40px;												/* Отступ от левого края. Сдвигает оба графика */
			margin-right: 40px;												/* Отступ справа. Расстояние между графиками */
		}
		.charge {
			width: 650px;													/* Размер графика ТЕМПЕРАТУРА */
			margin-left: 40px;												/* Отступ от левого края. Сдвигает оба графика */
			margin-right: 40px;												/* Отступ справа */
		}
		.container {
			display: flex;
			flex-direction: row;
			flex-wrap: wrap;
			justify-content: center;			
		}
        
	</style>
</head>

<body>
<table align="center" border="0">
   <caption><h2><b>Температура и влажность</b></h2><b></caption>   
   <tr>
	   <!-- влажность начало графика -->
   		<td align="left">		
			<div class="container">
			<div class="chart-container">
				<canvas id="chart-legend-normal"></canvas>
			</div>
			<div class="charge">
				<canvas id="charge"></canvas>
			</div>
			</div>
			<script>
			var color = Chart.helpers.color;
			function createConfig(colorName) {
				return {
					type: 'line', 				// line  bar  radar	doughnut pie 																						// Виды графиков    bar  line 
					data: {
						labels: ['1', '2', '3', '4', '5', '6','7', '8', '9', '10', '11', '12', '13', '14', '15', '16','17', '18', '19', '20','21', '22', '23', '24','25', '26', '27', '28', '29', '30','31', '32', '33', '34', '35', '36', '37', '38', '39', '40','41', '42', '43', '44','45'],  	// Сколько значений выводить Печатает снизу снизу
						datasets: [{
							label: 'Температура',
							data: [<?php while ($change = mysqli_fetch_array($ch)) { echo '"' . $change['temperature'] . '",';}?>]	

							// backgroundColor: 'rgb(220, 220, 220)',																										// Заливка графика
							// borderColor: 'rgb(255, 0, 0)',
							// borderWidth: 1,																																// линия на графике
							// pointStyle: 'circle',  // 'circle'   'cross'  'crossRot'  'dash'  'line' 'rect' 'rectRounded' 'rectRot' 'star'																													// тип точек на графике
							// pointRadius: 4,																																// Размер точек
							// pointBorderColor: 'rgb(255, 0, 0)'																											// Цвет бордюра вокруг точек на графике
						}]
					},
					options: {
						responsive: true,
						legend: {
							labels: {
								usePointStyle: false
							}
						},
						scales: {
							xAxes: [{
								display: true,
								scaleLabel: {
									display: false,
									labelString: 'Последние 24 записи'
								}
							}],
							yAxes: [{
								display: true,
								scaleLabel: {
									display: true,
									labelString: 'Температура'
								}
							}]
						},
					}
				};
			}

			function createPointStyleConfig(colorName) {
				var config = createConfig(colorName);
				config.options.legend.labels.usePointStyle = true;
				config.options.title.text = 'Point Style Legend';
				return config;
			}

			window.onload = function() {
				[{
					id: 'chart-legend-normal',
					config: createConfig('Black')
				}
				].forEach(function(details) {
					var ctx = document.getElementById(details.id).getContext('2d');
					new Chart(ctx, details.config);
				});
			};
			</script>
	   </td>
	   <!-- влажность конец графика -->
	   <td align="center">	   		
			<!-- Температура -->
			<script>
			var chargeCanvas = document.getElementById("charge");		

			var barChart = new Chart(chargeCanvas, {
			type: 'line',											// line  bar  radar	doughnut pie    
			data: {
				labels: ['1', '2', '3', '4', '5', '6','7', '8', '9', '10', '11', '12', '13', '14', '15', '16','17', '18', '19', '20','21', '22', '23', '24','25', '26', '27', '28', '29', '30','31', '32', '33', '34', '35', '36', '37', '38', '39', '40','41', '42', '43', '44','45'], 
				datasets: [{
					label: 'Влажность ',     																													// Выводит название
					data: [<?php while ($p = mysqli_fetch_array($pow)) { echo '"' . $p['humidity'] . '",';}?>],   												// Какой стобец из базы выводим
				}]
				}
			});
			
			</script>  
	   </td>
	</tr>
  </table>
  
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
  <link rel="stylesheet" type="text/css" href="style.css">

  <script>  
        function show() 
        {      
			$.ajax({  
                url: "transfer/temp-1.php",  
                cache: false,  
                success: function(html){  
                    $("#content").html(html); 
				}
             }); 
           $.ajax({  
                url: "transfer/temp-2.php",  
                cache: false,  
                success: function(html){  
                    $("#content-1").html(html); 
                }
             });    
             $.ajax({  
                url: "transfer/ledstate.php",  
                cache: false,  
                success: function(html){  
                    $("#content-3").html(html); 
                }
             }); 
             
        }
        
        $(document).ready(function(){  
            show();  
            setInterval('show()',500);  
        }); 
        
 
      function AjaxFormRequest(result_id,led,url) {
      jQuery.ajax({
      url:     url,
      type:     "POST",
      dataType: "html",
      data: jQuery("#"+led).serialize(),
         });

	
}

</script>

  </head>
    <body>
	<div class="r">
          <p class="r1">Температура</p>
          <div class="r2" style="display:inline-block;" >
          <div class="r3" id="content"></div> 
          <div class="r3"> C<sup>o</sup></div>
          </div>
          </div>


          <div class="r">
          <p class="r1">Влажность</p>
          <div class="r2" >
          <div class="r3" id="content-1"></div> 
          <div class="r3"> C<sup>o</sup></div>
          </div>
          </div>

          <div class="r">
          <div class="rl">
          <p align="center">Выключатель</p>
          <div align="center" style="font-size:30px" >
          <form  id="led" action="" method="post"  >
          <label><input type="radio" name="status" value="1"> ON </label>
          <label><input type="radio" name="status" value="2"> OFF </label>
          <br>
          <input class="submitButton" type="submit" value="Отправить" onclick="AjaxFormRequest('messegeResult', 'led', 'transfer/led.php')" >
          </form>
          </div>
          </div>
          
          <div class="rr">
          <p class="r1">Состояние</p>
          <div class="r2"style="font-size:35px" >
          <div class="r3" id="content-3"></div> 
          </div>
          </div>
          </div>
         
</body>
	</html> 
			<br>
			<br>
			<br>
			<br>
			<br>
			<br>
			<br>
			<br>
			<br>
			<br>
			<br>
			<br>
			<br>
		
			<style>
		form{
			margin:0 auto;
			padding:15px;
		}
		input[type=range]{
			writing-mode: bt-lr; /* IE */
			-webkit-appearance: slider-vertical; /* WebKit */
			width: 30px;
			height: 100px;
			padding: 0 24px;
			outline: none;
			background:transparent;
		}
		
		</style>
		<div align="center">
		<form id="servo" action=""  method="post" onsubmit="return false" oninput="level.value = flevel.valueAsNumber" >
		<p align="center">Ползунок для управления сервоприводом</p>
		<div align="center">
		<input name="flevel" id="flying" type="range" orient=vertical min="0" max="180" value="$theData1" step="5" onclick="AjaxFormRequest('messegeResult', 'servo', 'transfer/servo.php')"><br>
		<output for="flying" name="level">90</output><br>
		</form>  
</body>

		<button  type="submit" class="btn btn-primary" 
		onClick="document.location.href='/pma/excel.php'">Экспорт в Exсel
		</button>
		</div>

		<form  id="manuauto_in" action="" method="post"  >
		<label> Выберите режим: <label> <br>
		<label><input type="radio" name="manuauto" value="1" onclick="AjaxFormRequest('messegeResult', 'manuauto_in', 'transfer/manuauto_in.php')"> Ручной </label> <br>
        <label><input type="radio" name="manuauto" value="2" onclick="AjaxFormRequest('messegeResult', 'manuauto_in', 'transfer/manuauto_in.php')"> Автоматический </label>
		</form>  
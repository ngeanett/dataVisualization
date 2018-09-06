<html>
  <head>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script type="text/javascript">
    	google.load("visualization", "1", {packages:["corechart"]});
    	google.setOnLoadCallback(drawSeriesChart);

    	function drawSeriesChart() {
		
	 	var jsonData = $.ajax({
        	url: "getBubbleDatabyTypeGrouped.php",
          	dataType:"json",
          	async: false
          	}).responseText;
          
      	// Create our data table out of JSON data loaded from server.
      	var data = new google.visualization.DataTable(jsonData);

	  	var options = {
    		title: 'Utilization Rate By Member Type',
        	hAxis: {title: 'Member Type (Grouped)'},
        	hAxis: {ticks: [
        				{v:0, f:''}, 
        				{v:1, f:''}, 
        				{v:2, f:''}, 
        				{v:2.5, f:''}, 
        				{v:3, f:''}, 
        				{v:4.5, f:''}, 
        				{v:6, f:''}, 
        				{v:8, f:''}
        				] 
        		},
        	vAxis: {title: 'Utilization Rate'},
        	bubble: {opacity: 0},
        	bubble: {
      			textStyle: {fontSize: 12, fontName: 'Times-Roman', color: 'none'}	
        	}
      	};

      	var chart = new google.visualization.BubbleChart(document.getElementById('series_chart_div'));
      	chart.draw(data, options);
    	}
    </script>
  </head>
  <body>
  	<a href="index.php">Back</a>
  	<form action = "BubbleChartbyType2.php" method="get">
		<select name="type">
			<?php
				include('getBubbleDatabyType2.php');
				foreach($type_list as $type){
					echo '<option value="' . $type . '">'. $type . '</option>' . "\n";
				}
			?>
		</select>
		<input type="submit">
		</form>
    <div id="series_chart_div" style="width: 1400px; height: 800px;"></div>
  </body>
</html>
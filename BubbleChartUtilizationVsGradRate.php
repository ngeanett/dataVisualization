<html>
  <head>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script type="text/javascript">
    	google.load("visualization", "1", {packages:["corechart"]});
    	google.setOnLoadCallback(drawSeriesChart);

    	function drawSeriesChart() {
		
	 	var jsonData = $.ajax({
        	url: "getBubbleDataUtilizationvsGradRate.php",
          	dataType:"json",
          	async: false
          	}).responseText;
          
      	// Create our data table out of JSON data loaded from server.
      	var data = new google.visualization.DataTable(jsonData);

		  var options = {
			title: 'Utilization Rate Vs. Graduation Rate',
			hAxis: {title: 'Graduation Rate'},
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
    <div id="series_chart_div" style="width: 1400px; height: 800px;"></div>
  </body>
</html>
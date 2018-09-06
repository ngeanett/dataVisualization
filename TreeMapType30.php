<html>
  <head>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script type="text/javascript">
		google.load("visualization", "1", {packages:["treemap"]});
		google.setOnLoadCallback(drawChart);

		function drawChart() {
		
			var jsonData = $.ajax({
				url: "getTreeMapType30.php",
				dataType:"json",
				async: false
				}).responseText;
		  
		  	// Create our data table out of JSON data loaded from server.
		  	var data = new google.visualization.DataTable(jsonData);
	
			var options = {
				title: 'Utilization Rate Tree Map by Type for Alpha Members (Size is Trailing Month)',
				highlightOnMouseOver: true,
				maxDepth: 5,
				maxPostDepth: 5,
				//minHighlightColor: '#8c6bb1',
				//midHighlightColor: '#9ebcda',
				//maxHighlightColor: '#edf8fb',
				minColor: '#FF6666',
				midColor: '#CCEBD6',
				maxColor: '#009933',
				headerHeight: 15,
				showScale: true,
				generateTooltip: showStaticTooltip,
				height: 800,
				useWeightedAverageForAggregation: false
			  };
			
		  	var chart = new google.visualization.TreeMap(document.getElementById('tree_chart_div'));
		  	chart.draw(data, options);
		  	
		  	function showStaticTooltip(row, size, value) {
    			return '<div style="background:#fd9; padding:10px; border-style:solid">' +
           				'<span style="font-family:Courier">' + 
           				data.getValue(row, 0) +'</span><br>' +
           				'<b>' + data.getColumnLabel(1) + '</b>: ' + data.getValue(row, 1) + '<br>' +
           				'<b>' + data.getColumnLabel(2) + '</b>: ' + size + '<br>' +
           				'<b>' + data.getColumnLabel(3) + '</b>: ' + data.getValue(row, 3) + '<br>' + 
	   					//'<b>' + data.getColumnLabel(3) + '(Average)</b>: ' + value + 
	   					' </div>';
  			}
		}
    </script>
  </head>
  <body>
  	<a href="index.php">Back</a>
    <div id="tree_chart_div" style="width: 1400px; height: 800px;"></div>
  </body>
</html>
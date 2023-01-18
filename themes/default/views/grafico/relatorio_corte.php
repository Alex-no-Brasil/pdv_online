<br><br>

<div id="relatorio_corte" style="width: 100%; height: 300px;"></div>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script type="text/javascript">
    google.charts.load("current", {packages: ["corechart"]});
    google.charts.setOnLoadCallback(drawChart);
    
    function drawChart() {
    
        var data = google.visualization.arrayToDataTable(<?= $dados ?>);

        var view = new google.visualization.DataView(data);
        
        view.setColumns([0, 1,
            {calc: "stringify",
                sourceColumn: 1,
                type: "string",
                role: "annotation"},
            2]);

        var options = {
            title: "Quantidade de cortes",
            height: 400,
            bar: {groupWidth: "50%"},
            legend: {position: "none"}
        };
        
        var chart = new google.visualization.BarChart(document.getElementById("relatorio_corte"));
        
        chart.draw(view, options);
    }
</script>

<!--

    https://developers.google.com/chart/interactive/docs/gallery/barchart

-->






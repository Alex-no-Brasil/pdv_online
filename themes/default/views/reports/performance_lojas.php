<link href="<?= $assets ?>plugins/daterangepicker/daterangepicker.css" rel="stylesheet" type="text/css" />
<section class="content">
    <div class="row">
        <div class="col-md-12" style="padding-bottom: 10px;">
            <div style="width: 210px; float: right;">
                <div class="input-group">
                    <input type="text" class="form-control pull-right" id="data_range" name="data_range">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </div>
                    <input type="hidden" id="date_start" value="<?= $date_start ?>">
                    <input type="hidden" id="date_end" value="<?= $date_end ?>">
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-body">
                    <div class="col-md-4" style="padding: 0">
                        <div class="pchart" id="pie-valor" style="height:300px;"></div>
                    </div>
                    <div class="col-md-4" style="padding: 0 0 0 10px">
                        <div class="pchart" id="pie-vendas" style="height:300px;"></div>
                    </div>
                    <div class="col-md-4" style="padding: 0 0 0 10px">
                        <div class="pchart" id="pie-pecas" style="height:300px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-body">
                    <div class="col-md-12" style="padding: 5px 0">
                        <div class="pchart" id="column-valor" style="height:300px;"></div>
                    </div>
                </div>
            </div>
            <div class="box box-primary">
                <div class="box-body">
                    <div class="col-md-12" style="padding: 5px 0">
                        <div class="pchart" id="column-vendas" style="height:300px;"></div>
                    </div>
                </div>
            </div>
            <div class="box box-primary">
                <div class="box-body">
                    <div class="col-md-12" style="padding: 10px 0">
                        <div class="pchart" id="column-pecas" style="height:300px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    function load_pie(d) {

        var serie = {
            name: 'Total',
            data: []
        };

        var sum = 0;
        
        $.each(d.series, function (i, s) {
            serie.data.push({
                name: s.label,
                y: s.value,
                color: (d.colors !== null) ? d.colors[i] : Highcharts.getOptions().colors[i],
                pref: d.pref
            });
            
            sum += s.value;
        });

        Highcharts.chart('pie-' + d.id, {
            chart: {
                type: 'pie',
                borderColor: '#ecf0f5',
                borderWidth: 2
            },
            title: {
                text: d.title
            },
            subtitle: {
                text: '<b>' + d.pref + Highcharts.numberFormat(sum, (d.pref.length > 0) ? 2 : 0) + '</b>'
            },
            credits: {
                enabled: false
            },
            tooltip: {
                shared: true,
                borderWidth: 0,
                formatter: function () {
                    var leg_val = Highcharts.numberFormat(this.y, (this.point.pref.length > 0) ? 2 : 0);
                    var leg_per = Highcharts.numberFormat(this.point.percentage, 0);

                    return  '<b>' + this.point.name + ': ' + this.point.pref + leg_val + '</b> (' + leg_per + '%)';
                }
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    size: 200,
                    dataLabels: {
                        className: 'pie-label',
                        distance: '5%',
                        formatter: function () {
                            var leg_per = Highcharts.numberFormat(this.point.percentage, 0);
                            return '<span style="font-size: 10px;color: #333">' + this.point.name + ' (' + leg_per + '%)</span>';
                        }
                    }
                }
            },
            series: [serie]
        });
    }

    function load_column(d) {

        var serie = {
            name: '',
            data: [],
            dataLabels: {
                enabled: true,
                rotation: -60,
                color: '#FFFFFF',
                align: 'right',
                y: 0,
                formatter: function () {
                    var leg_val = Highcharts.numberFormat(this.point.y, (this.point.pref.length > 0) ? 2 : 0);
                    return '<span style="font-size: 10px; color: #333">' + this.point.pref + leg_val + '</span>';
                },
                useHTML: true
            },
            dataSorting: {
                enabled: true
            }
        };

        $.each(d.series, function (i, s) {
            serie.data.push({
                name: s.label,
                y: s.value,
                color: s.col_color,
                pref: d.pref
            });
        });

        Highcharts.chart('column-' + d.id, {
            chart: {
                type: 'column'
            },
            title: {
                text: d.title
            },
            credits: {
                enabled: false
            },
            tooltip: {
                shared: false,
                borderWidth: 0,
                formatter: function () {
                    var leg_val = Highcharts.numberFormat(this.point.y, (this.point.pref.length > 0) ? 2 : 0);
                    return  '<b>' + this.point.pref + leg_val + '</b>';
                }
            },
            series: [serie],
            xAxis: {
                title: '',
                type: 'category',
                rotation: -45
            },
            yAxis: {
                title: ''
            },
            legend: {
                enabled: false
            }
        });
    }

    function load_data() {

        var url = 'lojas/chart';
        url += "?start=" + $('#date_start').val();
        url += "&end=" + $('#date_end').val();

        var titles = {
            vendas: 'Vendas',
            pecas: 'Peças',
            valor: 'Receita'
        };

        var prefs = {
            vendas: '',
            pecas: '',
            valor: 'R$ '
        };

        $.get(url, function (d) {
            $.each(d, function (id, series) {
                load_pie({id: id, title: titles[id], series: series, colors: null, pref: prefs[id]});
            });

            $.each(d, function (id, series) {
                load_column({id: id, title: titles[id], series: series, colors: null, pref: prefs[id]});
            });
        });
    }

    $(document).ready(function () {
        Highcharts.setOptions({
            lang: highcharts_lang
        });

        load_data();

        $('#data_range').daterangepicker({
            locale: daterange_locale,
            startDate: moment(<?= $date_start ?>),
            endDate: moment(<?= $date_end ?>)
        }, function (start, end, label) {
            $('#date_start').val(start.format('x'));
            $('#date_end').val(end.format('x'));
            load_data();
        });
    });

</script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="<?= $assets ?>plugins/daterangepicker/moment.min.js"></script>
<script src="<?= $assets ?>plugins/daterangepicker/daterangepicker.js"></script>
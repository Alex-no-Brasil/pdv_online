<link href="<?= $assets ?>plugins/daterangepicker/daterangepicker.css" rel="stylesheet" type="text/css" />
<section class="content">
    <div class="row">
        <div class="col-md-6">
            <div style="float: left">
                <div class="checkbox">
                    <label style="padding-left: 0px; font-weight: bold">
                        <input type="radio" name="dimension" value="valor" checked>
                        Receita
                    </label>
                </div>
            </div>
            <div style="float: left; margin-left: 20px">
                <div class="checkbox">
                    <label style="padding: 0px 5px; font-weight: bold">
                        <input type="radio" name="dimension" value="pecas">
                        Peças
                    </label>
                </div>
            </div>
        </div>
        <div class="col-md-6">
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
                        <div class="pchart" id="chart-model" style="height:300px;"></div>
                    </div>
                    <div class="col-md-4" style="padding: 0 0 0 10px">
                        <div class="pchart" id="chart-category" style="height:300px;"></div>
                    </div>
                    <div class="col-md-4" style="padding: 0 0 0 10px">
                        <div class="pchart" id="chart-material" style="height:300px;"></div>
                    </div>
                    <div class="col-md-4" style="padding: 10px 0 0 0">
                        <div class="pchart" id="chart-stamp" style="height:300px;"></div>
                    </div>
                    <div class="col-md-4" style="padding: 10px 0 0 10px">
                        <div class="pchart" id="chart-manga" style="height:300px;"></div>
                    </div>
                    <div class="col-md-4" style="padding: 10px 0 0 10px">
                        <div class="pchart" id="chart-season" style="height:300px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    var chart_data = {};

    function load_chart(d) {

        var serie = {
            name: 'Total',
            data: []
        };

        var dm = $('input[name="dimension"]:checked').val();

        var sum = 0;

        $.each(d.series, function (i, s) {
            serie.data.push({
                name: s.label,
                y: parseFloat(s[dm]),
                color: (d.colors !== null) ? d.colors[i] : Highcharts.getOptions().colors[i]
            });

            sum += parseFloat(s[dm]);
        });

        var pref = 'R$ ';

        if (dm !== 'valor') {
            pref = '';
        }

        Highcharts.chart('chart-' + d.id, {
            chart: {
                type: 'pie',
                borderColor: '#ecf0f5',
                borderWidth: 2
            },
            title: {
                text: d.title
            },
            subtitle: {
                text: '<b>' + pref + Highcharts.numberFormat(sum, (pref.length > 0) ? 2 : 0) + '</b>'
            },
            credits: {
                enabled: false
            },
            tooltip: {
                shared: true,
                borderWidth: 0,
                formatter: function () {
                    var dm = $('input[name="dimension"]:checked').val();
                    var pref = 'R$ ';

                    if (dm !== 'valor') {
                        pref = '';
                    }

                    var leg_val = Highcharts.numberFormat(this.y, (pref.length > 0) ? 2 : 0);
                    var leg_per = Highcharts.numberFormat(this.point.percentage, 0);
                    
                    return  '<b>' + this.point.name + ': ' + pref + leg_val + '</b> (' + leg_per + '%)';
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

    function load_data() {
        $('.pchart').each(function (i, e) {
            var ps = e.id.split('-');

            var url = 'products/' + ps[1];
            url += "?start=" + $('#date_start').val();
            url += "&end=" + $('#date_end').val();

            $.get(url, function (d) {
                chart_data[d.id] = d;
                load_chart(d);
            });
        });
    }

    $(document).ready(function () {
        Highcharts.setOptions({
            lang: highcharts_lang
        });

        load_data();

        $('input[name="dimension"]').click(function () {
            $.each(chart_data, function (i, d) {
                load_chart(d);
            });
        });

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
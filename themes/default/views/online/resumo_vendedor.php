<link href="<?= $assets ?>plugins/daterangepicker/daterangepicker.css" rel="stylesheet" type="text/css" />
<link href="<?= $assets ?>plugins/iCheck/square/grey.css" rel="stylesheet" type="text/css" />
<style>
    .table-responsive{
        padding: 20px;
    }
    td, th{
        text-align: center;
    }
</style>

<section class="content">

    <div class="row" style="padding-bottom: 10px;">
        <div class="col-md-12">
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
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="table_online" class="table table-striped table-bordered table-condensed table-hover">
                            <thead>
                                <tr>
                                    <th>Vendedor(a)</th>
                                    <th>Quantidade de peças</th>
                                    <th>Quantidade de vendas</th>
                                    <th>Valor total</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>




<script src="<?= $assets ?>plugins/daterangepicker/moment.min.js"></script>
<script src="<?= $assets ?>plugins/daterangepicker/daterangepicker.js"></script>
<script>
    var dataTable;

    function load_data() {
        $('#table_online_processing').css('visibility', 'visible');

        var url = '<?= site_url('online/get_resumo_vendedor') ?>';
        
        url += "?start=" + $('#date_start').val();
        url += "&end=" + $('#date_end').val();
        
        $.get(url, function (d) {
            load_table(d);
            $('#table_online_processing').css('visibility', 'hidden');
        });
    }
    
    function load_table(data) {
        if (dataTable) {
            dataTable.fnDestroy();
        }
        
        dataTable = $('#table_online').DataTable({
            'sScrollY': (window.innerHeight - 300) + 'px',
            "aaSorting": [
                [1, "desc"]
            ],
            "iDisplayLength": 25,
            'bResponsive': true,
            'bProcessing': true,
            'aaData': data,
            fnRowCallback: function(tr, data, index) {
                
                $('td:eq(3)', tr).text('R$ ' + data[3]);
            }
        });
        
        $('#table_online').css('width', '100%');
    }
    
    $(document).ready(function () {
        
        load_table([]);
        
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
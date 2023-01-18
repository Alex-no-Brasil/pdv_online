<link href="<?= $assets ?>plugins/daterangepicker/daterangepicker.css" rel="stylesheet" type="text/css" />
<section class="content">
    <div class="row" style="padding-bottom: 10px;">
        <div class="col-md-6">
            <div style="float: left">
                <select class="form-control form-group-sm" id="cod_loja">
                    <option value="">Todas Lojas</option> 
                    <?php foreach ($arrLojas as $loja): ?>
                        <option value="<?= $loja ?>"><?= $loja ?></option> 
                    <?php endforeach; ?>
                </select>
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
                    <div class="table-responsive">
                        <table id="SLData" class="table table-striped table-bordered table-condensed table-hover">
                            <thead>
                                <tr class="active">
                                    <th>Código</th>
                                    <th>Data</th>
                                    <th>Hora</th>
                                    <th>Loja</th>
                                    <th>Cliente</th>
                                    <th>Peças</th>
                                    <th>Valor</th>
                                    <th>Status</th>
                                    <th>Vendedor/Origem</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="11" class="dataTables_empty"><?= lang('loading_data_from_server'); ?></td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="<?= $assets ?>plugins/daterangepicker/moment.min.js"></script>
<script src="<?= $assets ?>plugins/daterangepicker/daterangepicker.js"></script>

<script>
    function get_cod_loja(unique_key) {
        var parts = unique_key.split('-');
        return parts[0];
    }
    
    function sale_valor(valor, type) {
        if (type === 'display') {
            return 'R$ ' + valor.replace('.', ',');
        }

        return valor;
    }

    function sale_status(status, type, row) {
        if (status === 'canceled') {
            return 'Cancelada';
        }
        
        if (status === 'pend-cancel') {
            <?php if($user_group === 'admin' || $user_group === 'adm'): ?>
                return '<a href="" onclick="aprova_cancela(\''+row[1]+'\')">Aprovar Cancelamento</a>';
            <?php else: ?>
                return 'Aprovar Cancelamento';
            <?php endif;?>
        }

        return 'Ativa';
    }

    function aprova_cancela(key) {
        var parts = key.split('-');
        
        if (!window.confirm('Tem certeza que deseja cancelar a venda ' + parts[1] + ' da loja ' + parts[0] + '?')) {
            return;
        }
        
        var post = {
            key: key,
            '<?= $this->security->get_csrf_token_name() ?>' : '<?= $this->security->get_csrf_hash()?>'
        };
        
        $.post('<?= site_url('sales/canceled') ?>', post, function () {
           alert('Venda cancelada');
           dataTable._fnAjaxUpdate(); 
        });
    }
    
    function funcao_data($data){
        return date("d/m/Y", strtotime($data));
    }
    
    function funcao_hora($data){
        return date("h:i", strtotime($data));
    }
    
    var dataTable;

    var dt_data = {};

    $(document).ready(function () {

        $('#cod_loja').change(function () {
            dataTable._fnAjaxUpdate();
        });
        
        $('#data_range').daterangepicker({
            locale: daterange_locale,
            startDate: moment(<?= $date_start ?>),
            endDate: moment(<?= $date_end ?>)
        }, function (start, end, label) {
            $('#date_start').val(start.format('x'));
            $('#date_end').val(end.format('x'));

            dataTable._fnAjaxUpdate();
        });

        dataTable = $('#SLData').dataTable({
            'sScrollY': (window.innerHeight - 300) + 'px',
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, '<?= lang('all'); ?>']],
            "aaSorting": [[0, "desc"]], "iDisplayLength": <?= $Settings->rows_per_page ?>,
            'bProcessing': true, 'bServerSide': true,
            'sAjaxSource': '<?= site_url('sales/get_vendas_geral') ?>',
            'fnServerData': function (sSource, aoData, fnCallback) {
                aoData.push({
                    "name": "<?= $this->security->get_csrf_token_name() ?>",
                    "value": "<?= $this->security->get_csrf_hash() ?>"
                });

                aoData.push({name: "cod_loja", value: $('#cod_loja').val()});
                
                aoData.push({name: "start", value: $('#date_start').val()});

                aoData.push({name: "end", value: $('#date_end').val()});

                $.ajax({
                    'dataType': 'json',
                    'type': 'POST',
                    'url': sSource,
                    'data': aoData,
                    'success': function (json) {
                        dt_data = json;
                        fnCallback(json);
                    }
                });
            },
            "aoColumns": [
                {"sClass": "text-center"},
                {"sClass": "text-center", "mRender": funcao_data},
                {"sClass": "text-center", "mRender": funcao_hora},
                {"sClass": "text-center", "mRender": get_cod_loja},
                {"sClass": "text-center"},
                {"sClass": "text-center"},
                {"sClass": "text-center", "mRender": sale_valor},
                {"sClass": "text-center", "mRender": sale_status},
                {"sClass": "text-right"}
            ],
            "fnFooterCallback": function (nFoot, aData, iStart, iEnd, aiDisplay) {
                nFoot.getElementsByTagName('th')[6].innerHTML = sale_valor(String(dt_data.footer.total), 'display');
            }
        });
    });
</script>
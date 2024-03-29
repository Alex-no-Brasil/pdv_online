<script>
    function status(cod) {
        var map = {
            A:'Ativo',
            I: 'Inativo',
            F: 'Férias',
            L: 'Licença médica'
        };
        
        return map[cod];
    }
    
    $(document).ready(function () {
        $('#spData').dataTable({
            'sScrollY': (window.innerHeight - 300) + 'px',
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, '<?= lang('all'); ?>']],
            "aaSorting": [[0, "desc"]], "iDisplayLength": <?= $Settings->rows_per_page ?>,
            'bProcessing': true, 'bServerSide': true,
            'sAjaxSource': '<?= site_url('sellers/get_sellers') ?>',
            'fnServerData': function (sSource, aoData, fnCallback) {
                aoData.push({
                    "name": "<?= $this->security->get_csrf_token_name() ?>",
                    "value": "<?= $this->security->get_csrf_hash() ?>"
                });
                $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
            },
            "aoColumns": [
                null,
                null,
                {
                    mRender: status
                },
                {
                    "bSortable": false,
                    "bSearchable": false
                }]
        });
    });
</script>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title"><?= lang('list_results'); ?></h3>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="spData" class="table table-bordered table-hover table-striped">
                            <thead>
                                <tr>
                                    <th><?php echo $this->lang->line("name"); ?></th>
                                    <th><?php echo $this->lang->line("Loja"); ?></th>
                                    <th><?php echo $this->lang->line("status"); ?></th>
                                    <th><?php echo $this->lang->line("actions"); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="4" class="dataTables_empty"><?= lang('loading_data_from_server') ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
</section>

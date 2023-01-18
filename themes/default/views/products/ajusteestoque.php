<link href="<?= $assets ?>plugins/daterangepicker/daterangepicker.css" rel="stylesheet" type="text/css" />
<style>
    #table-ajuste th, #table-ajuste td {
        text-align: center;
    }
    #table-items .text-left {
        text-align: left;
    }
</style>
<section class="content">
    <div class="row" style="padding-bottom: 10px;">
        <div class="col-md-12">
            <div style="float: left">
                <select class="form-control form-group-sm select2" id="f_loja">
                    <option value="0">Todas Lojas</option> 
                    <?php foreach ($arrLojas as $loja): ?>
                        <option value="<?= $loja ?>"><?= $loja ?></option> 
                    <?php endforeach; ?>
                </select>
            </div>
            <div style="float: left; margin-left: 15px">
                <a href="#" class="btn btn-success" onclick="modal_ajuste();">
                    <i class="fa fa-plus"></i>
                    Adicionar
                </a>
            </div>
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
                    <table class="table table-hover table-striped" id="table-ajuste">
                        <thead>
                            <tr>
                                <th class="text-left">Data</th>
                                <th class="text-left">Código</th>
                                <th class="text-left">Loja</th>
                                <th class="text-left">QTD Atual</th>
                                <th class="text-left">QTD Anterior</th>
                                <th class="text-left">QTD Ajuste</th>
                                <th class="text-left">Solicitante</th>
                                <th class="text-left">Status</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
<div class="modal fade bd-example-modal-lg" id="modal_ajuste">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Ajuste de Estoque</h4>
            </div>
            <?= form_open("products/ajusteestoque_salvar", ['id' => 'form_ajusteestoque']); ?>
            <div class="modal-body">
                <div class="form-group" id="selectLojaDestino">
                    <label for="status">Loja</label>
                    <select name="cod_loja" id="cod_loja" data-placeholder="Selecione a Loja" class="form-control select2 input-tip" style="width:100%;" tabindex="-1">
                        <option value="" selected="selected"></option>
                        <?php foreach ($arrLojas as $loja): ?>
                            <option value="<?= $loja ?>"><?= $loja ?></option> 
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="row">
                    <div class="box-header">
                        <h3 class="box-title">Produtos para ajuste</h3>
                    </div>
                    <div class="box-body">
                        <table class="table" id="table-items">
                            <thead>
                                <tr>
                                    <th class="text-left">Código</th>
                                    <th class="text-left">Quantidade</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2">
                                        <div style="float: left; height: 30px; margin-left: 0; align-items: center; display: flex">
                                            <a href="#" onclick="input_ajuste(event)">
                                                <i class="fa fa-plus"></i>
                                                Adicionar
                                            </a>
                                        </div>
                                        <div style="float: left; width: 60px; margin-left: 10px">
                                            <input type="number" class="form-control input-sm" id="variant_count" step="1" min="1" value="1">
                                        </div>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success btn-flat" id="ajuste-submit" disabled>
                    <i class="fa fa-save"></i> Salvar
                </button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>
<script src="<?= $assets ?>plugins/daterangepicker/moment.min.js">
</script>
<script src="<?= $assets ?>plugins/daterangepicker/daterangepicker.js">
</script>
<script>

    function modal_ajuste() {
        $('#cod_loja').val('').change();
        $('#table-items tbody').html('');
        $('#modal_ajuste').modal();
    }

    function input_ajuste(ev) {
        ev.preventDefault();

        var count = parseInt($('#variant_count').val());

        for (var i = 1; i <= count; i++) {
            setTimeout(function () {
                ajuste_add();
            }, 100 + i);
        }
    }

    function ajuste_add() {
        $('#table-items tbody').append('\n\
            <tr>\n\
                <td class="text-left">\n\
                    <input type="text" name="ajuste_code[]" class="form-control input-sm">\n\
                </td>\n\
                <td class="text-left">\n\
                    <input type="number" name="ajuste_qty[]" class="form-control input-sm" step="1" min="0" style="width: 130px">\n\
                </td>\n\
            </tr>\n\
        ');
    }

    function load_data() {
        $('#table-ajuste_processing').css('visibility', 'visible');

        var url = '<?= site_url('products/ajusteestoque_lista') ?>';

        url += '/' + $('#f_loja').val();

        url += '?start=' + $('#date_start').val();
        url += '&end=' + $('#date_end').val();

        $.get(url, function (d) {
            load_table(d);
            $('#table-ajuste_processing').css('visibility', 'hidden');
        });
    }

    function load_table(data) {
        if (dataTable) {
            dataTable.fnDestroy();
        }

        dataTable = $('#table-ajuste').dataTable({
            sDom: 'ftr',
            iDisplayLength: -1,
            aaData: data,
            aoColumns: [
                {mData: "createdAt"},
                {mData: "cod_produto"},
                {mData: "cod_loja"},
                {mData: "quantity"},
                {mData: "loja_quantity"},
                {mData: "loja_ajuste"},
                {mData: "username"},
                {mData: "sync_time"}
            ],
            fnRowCallback: function (tr, data, index) {

                var status = 'Pendente';

                if (data.sync_time > 0) {
                    status = 'Atualizado';
                }

                $('td:eq(7)', tr).text(status);
            }
        });
    }

    var dataTable;

    $(document).ready(function () {
        $('#f_loja').change(function () {
            load_data();
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

        $(document).on('change', '#table-items input.input-sm', function () {
            $('#ajuste-submit').attr('disabled', false);
        });

        $('#form_ajusteestoque').submit(function () {
            
            $('#ajuste-submit').attr('disabled', true);
            
            $.post($(this).attr('action'), $(this).serialize(), function (resp) {
                if (resp.error) {
                    alert(resp.message);
                } else {
                    $('#f_loja').val($('#cod_loja').val());
                    load_data();
                    $('#modal_ajuste').modal('hide');
                    $('#cod_loja').val('').change();
                    $('#table-items tbody').html('');
                }
                
                $('#ajuste-submit').attr('disabled', false);
            });

            return false;
        });

        load_data();
    });
</script>
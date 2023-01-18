<link href="<?= $assets ?>plugins/daterangepicker/daterangepicker.css" rel="stylesheet" type="text/css" />
<link href="<?= $assets ?>plugins/iCheck/square/grey.css" rel="stylesheet" type="text/css" />
<!--for dropZone update image-->
<link href="<?= $assets ?>plugins/dropzone/dropzone.min.css" rel="stylesheet" type="text/css"/>

<style>

    .text-center{
        text-align: center;
    }

    #table_online_wrapper .col-xs-6, #table_online_wrapper .col-xs-12 {
        padding: 0;
    }
    
    
    /*for dropZone*/
    .var-box-image {
        padding: 0 2px;
        /*float: left;*/
        display: inline-block;
        width: 84px;
        text-align: center;
    }
    .var-image, .var-remove {
        /*float: left;*/
        display: inline-block;
    }
    .dropzone {
        min-width: 140px;
        min-height: 140px;
        border: 1px dashed #0087F7;
        border-radius: 3px;
        background: white;
        cursor: pointer;
        padding: 0;
        /*float: right;*/
    }
    .dropzone .dz-preview {
        margin: 5px;
        min-height: 80px;
    }
    .dropzone .dz-preview .dz-image {
        width: 125px;
        height: 125px;
    }
    .dropzone .dz-preview .dz-details {
        padding: 1em;
    }
    .dropzone .dz-preview .dz-details .dz-filename {
        display: none;
    }
    .bootstrap-filestyle.input-group {
        display: none;
    }
    /*end dropZone*/
</style>

<!--Modal Detalhes-->
<div class="modal fade bd-example-modal-lg" id="mdl_pedido_detalhes">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

        </div>
    </div>
</div>

<!--Modal comprovante-->
<div class="modal fade bd-example-modal-lg" id="mdlComprovante">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Comprovante</h4>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-3"><div id="dropzone">
                            <center>
                                <form action="pedido_upload_comprovante" class="dropzone needsclick" id="adicionarComprovante">
                                    <div class="dz-message needsclick">
                                        <button type="button" class="dz-button" style="margin-top:27.5px">Adicionar comprovante</button>
                                        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                                    </div>
                                </form>
                            </center>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button id="salva_comprovante" type="button" class="btn btn-success"><i class="fa fa-check"></i>&nbsp; SALVAR</button>
            </div>
        </div>
    </div>
</div>
<!--fim modal comprovante-->

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
                                    <th title="SKU do pedido">Cod</th>
                                    <th title="Data do pedido">Data</th>
                                    <th title="Nome do cliente">Cliente</th>
                                    <th title="Destinatário">Destinatário</th>
                                    <th title="Quantidade de peças">Peças</th>
                                    <th title="Valor do pedido">Valor</th>
                                    <th title="Status">Status</th>
                                    <th title="Forma de entrega">Entrega</th>
                                    <th title="Plataforma ou local do pedido">Origem</th>
                                    <th title="Vendedor(a) que realizou a venda">Vendedor(a)</th>
                                    <th title="Forma de pagamento">Pagamento</th>                                    
                                    <th title="Confirmação do pacote">Pacote</th>
                                    <th title="Confirmação de envio">Envio</th>
                                    <th style="width:140px" title="Ações">Ações</th>
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

    <input type="hidden" id="cadastro_id">
    <input type="hidden" id="arq_comprovante">
</section>

<!--for dropZone update image-->
<script src="<?= $assets ?>plugins/dropzone/dropzone.min.js"></script>
<script src="<?= $assets ?>plugins/daterangepicker/moment.min.js"></script>
<script src="<?= $assets ?>plugins/daterangepicker/daterangepicker.js"></script>
<script>
    Dropzone.autoDiscover = false;

    function openMdlComprovante(id) {
        $('#cadastro_id').val(id);
        $('#arq_comprovante').val('');
        $("#mdlComprovante").modal('show');
    }

    function sale_data(valor, type, row) {
        if (type === 'display') {
            return moment(valor).format("DD/MM/YYYY");
        }

        return valor;
    }

    function status_pacote(data, type, row) {

        if (type !== 'display') {
            return data;
        }

        if (data > 0) {
            return '<span class="label label-success">Confirmado</span>';
        } else {
            return '<span class="label label-warning">Pendente</span>';
        }
    }

    function status_envio(data, type, row) {

        if (type !== 'display') {
            return data;
        }

        if (data > 0) {
            return '<span class="label label-success">Confirmado</span>';
        } else {
            return '<span class="label label-warning">Pendente</span>';
        }
    }

    function dt_actions(dados, type, row) {

        var parts = dados.split(',');

        var id = parts[0];

        var paymentId = parts[1];

        var comprovante = parts[2];

        var actions = '<a href="#" title="Detalhes do pedido" class="tip btn btn-primary btn-xs" onclick="modal(' + id + ')">\n\
                        <i class="fa fa-list"></i>\n\
                    </a>\n\
                    <a href="#" title="Imprimir romaneio" class="tip btn btn-primary btn-xs" onclick="imprimir(' + id + ')">\n\
                        <i class="fa fa-print"></i>\n\
                    </a>';

        /* <?php if(isset($permissoes['/online/comprovante'])) : ?> */
        if ((paymentId === '5' || paymentId === '33') && !comprovante) {
            actions += '<a href="#" title="Enviar comprovante" class="tip btn btn-primary btn-xs" onclick="openMdlComprovante(' + id + ')">\n\
                        <i class="fa fa-upload"></i>\n\
                    </a>';
        }

        if (comprovante) {
            actions += '<a href="' + comprovante + '" title="Ver comprovante" class="tip btn btn-primary btn-xs" target="_blank">\n\
                        <i class="fa fa-download"></i>\n\
                    </a>';
        }
        /*<?php endif;?>*/

        /* <?php if(isset($permissoes['/online/confirma_pacote'])) : ?> */
        if (row[11] === '0') {
            actions += '<a href="#" title="Confirmar pacote" class="tip btn btn-primary btn-xs" onclick="confirmar_pacote(' + id + ')">\n\
                        <i class="fa fa-archive"></i>\n\
                    </a>';
        }
        /*<?php endif;?>*/

        /* <?php if(isset($permissoes['/online/confirma_envio'])) : ?> */
        if (row[11] !== '0' && row[12] === '0') {
            actions += '<a href="#" title="Confirmar envio" class="tip btn btn-primary btn-xs" onclick="confirmar_envio(' + id + ')">\n\
                        <i class="fa fa-truck"></i>\n\
                    </a>';
        }
        /*<?php endif;?>*/

        return '<div class="text-center">\n\
                    <div class="btn-group actions">' + actions + '</div>\n\
            </div>';
    }

    function modal(id) {
        var url = 'modal_pedido_detalhes/' + id;

        $.ajax({
            type: 'GET',
            url: url,
            success: function (data) {
                $('#mdl_pedido_detalhes .modal-content').html(data);
                $('#mdl_pedido_detalhes').modal('show');
            }
        });
    }
    function imprimir(id) {
        window.open("imprimir_romaneio/" + id);
    }

    function pedido_atualiza(post) {

        post['id'] = $('#cadastro_id').val();

        post['<?= $this->security->get_csrf_token_name() ?>'] = '<?= $this->security->get_csrf_hash() ?>';

        $.post('pedido_atualiza', post, function (resp) {
            dataTable._fnAjaxUpdate();
        });
    }
    
    function confirmar_pacote(id) {
        
        if (!window.confirm('Tem certeza que deseja confirmar o pacote?')) {
            return;
        }
        
        $('#cadastro_id').val(id);
        
        pedido_atualiza({confirma_pacote: 1});
    }
    
    function confirmar_envio(id) {
        
        if (!window.confirm('Tem certeza que deseja confirmar o envio?')) {
            return;
        }
        
        $('#cadastro_id').val(id);
        
        pedido_atualiza({confirma_envio: 1});
    }
    
    function traducao($status){
        if($status=="APPROVED"){
            return ("Aprovado");
        }
        if($status=="COMPLETED"){
            return ("Completo");
        }
    }

    $(document).ready(function () {

        dataTable = $('#table_online').DataTable({
            'sScrollY': (window.innerHeight - 300) + 'px',
            "aaSorting": [
                [1, "desc"]
            ],
            "iDisplayLength": 25,
            'bProcessing': true,
            'bServerSide': true,
            'sAjaxSource': '<?= site_url('online/get_pedidos') ?>',
            'fnServerData': function (sSource, aoData, fnCallback) {
                aoData.push({
                    "name": "<?= $this->security->get_csrf_token_name() ?>",
                    "value": "<?= $this->security->get_csrf_hash() ?>"
                });

                aoData.push({name: "start", value: $('#date_start').val()});

                aoData.push({name: "end", value: $('#date_end').val()});

                $.ajax({
                    'dataType': 'json',
                    'type': 'POST',
                    'url': sSource,
                    'data': aoData,
                    'success': fnCallback
                });
            },
            "aoColumns": [
                {"sClass": "text-center"},
                {"sClass": "text-center", mRender: sale_data},
                {"sClass": "text-center"},
                {"sClass": "text-center"},
                {"sClass": "text-center"},
                {"sClass": "text-center"},
                {"sClass": "text-center", mRender: traducao},
                {"sClass": "text-center"},
                {"sClass": "text-center"},
                {"sClass": "text-center"},
                {"sClass": "text-center"},
                {"sClass": "text-center", mRender: status_pacote},
                {"sClass": "text-center", mRender: status_envio},
                {"sClass": "text-center", bSortable: false, bSearchable: false, mRender: dt_actions}
            ]

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


        //for dropZone imagemPadrão
        new Dropzone('#adicionarComprovante', {
            acceptedFiles: "image/*,.pdf",
            thumbnailWidth: 125,
            thumbnailHeight: 125,
            addRemoveLinks: true,
            uploadMultiple: false, //for one image to upload
            dictRemoveFile: "Remover",
            init: function () {
                this.on("success", function (file, response) {
                    if (response.indexOf('http') > -1) {
                        $('#arq_comprovante').val(response);
                    } else {
                        alert(response);
                    }
                });
            },
            removedfile: function (file) {
                file.previewElement.remove();
            }
        });
        //end dropZone

        $('#salva_comprovante').click(function () {

            var arq_comprovante = $('#arq_comprovante').val();

            if (arq_comprovante.indexOf('http') > -1) {

                var post = {comprovante: $('#arq_comprovante').val()};

                pedido_atualiza(post);

                $("#mdlComprovante").modal('hide');
            }
        });
    });
</script>
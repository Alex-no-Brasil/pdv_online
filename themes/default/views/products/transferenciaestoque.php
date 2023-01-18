<link href="<?= $assets ?>plugins/iCheck/square/green.css" rel="stylesheet" type="text/css" />
<style>
    .col-xs-6, .col-xs-12 {
        padding: 0;
    }
    table.dataTable thead > tr > th {
        padding: 8px 0;
        background-color: #f5f5f5;
        border-bottom: 1px solid #ccc;
    }
    table.dataTable > tbody > tr > td {
        border: none;
        border-bottom: 1px solid #ccc;
    }
    .table-hover>tbody>tr:hover {
        background-color: #cababa;
    }
    #fileData span.label {
        font-size: 12px;
    }
    #fileData a.btn-xs {
        padding: 1px 3px;
        font-size: 16px;
        line-height: 1;
    }
    .btn-group.actions {
        width: 60px;
    }
    #fileData_filter input:last-child {
        display: none;
    }
</style>
<section class="content">
    <div class="row" style="padding-bottom: 10px;">
        <div class="col-md-6" style="padding-left: 10px">
            <div style="float: left">
                <select class="form-control form-group-sm select2" id="filtro">
                    <option value="enviadas">Enviadas</option>
                    <option value="pendentes">Pendentes</option>
                    <option value="recebidas">Recebidas</option> 
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="fileData" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 150px; text-align: left; padding: 8px">Data</th>
                                    <th style="width: 30px">Código</th>
                                    <th>Nome</th>
                                    <th style="width: 120px">Origem</th>
                                    <th style="width: 60px">Saldo</th>
                                    <th style="width: 150px">Destino</th>
                                    <th style="width: 80px">Saldo</th>
                                    <th style="width: 200px">Transferido</th>                                    
                                    <th style="width: 60px">Status</th>
                                    <th style="width: 80px">&nbsp;</th>
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
    <script src="<?= $assets ?>plugins/iCheck/icheck.min.js" type="text/javascript"></script>
    <script>
        var map_status = {
            1: '<span class="label label-warning">Pendente</span>',
            2: '<span class="label label-success">Confirmada</span>',
            3: '<span class="label label-danger">Cancelada</span>'
        };

        var dataTable;
        
        $(document).ready(function () {

            dataTable = $('#fileData').dataTable({
                "bSort": false,
                "iDisplayLength": 100,
                'bProcessing': true,
                'bServerSide': true,
                'sAjaxSource': '<?= site_url('products/lista_transferenciaestoque') ?>',
                'fnServerData': function (sSource, aoData, fnCallback) {
                    aoData.push({
                        "name": "<?= $this->security->get_csrf_token_name() ?>",
                        "value": "<?= $this->security->get_csrf_hash() ?>"
                    });

                    aoData.push({
                        "name": "filtro",
                        "value": $("#filtro").val()
                    });

                    $.ajax({
                        'dataType': 'json',
                        'type': 'POST',
                        'url': sSource,
                        'data': aoData,
                        'success': fnCallback
                    });
                },
                "aoColumns": [{
                        sClass: "text-left"
                    }, {
                        sClass: "text-center"
                    }, {
                        sClass: "text-center"
                    }, {
                        sClass: "text-center"
                    }, {
                        sClass: "text-center"
                    }, {
                        sClass: "text-center"
                    }, {
                        sClass: "text-center"
                    }, {
                        sClass: "text-center"
                    }, {
                        sClass: "text-center"
                    }, {
                        sClass: "text-center"
                    }],
                fnRowCallback: function (tr, data, index) {

                    
                    $('td:eq(8)', tr).html(map_status[data[8]]);
                    
                    var actions = '';
                    
                    if (data[8] === '1' && data[3].indexOf("Loja") === 0) {
                        
                        var url = "<?= site_url("products/confirmartransferenciaspendentes") ?>";
                        
                        actions += '<a href="' + url + '\/' + data[9] + '" title="Confirmar" onclick="return confirm(\'Tem certeza que deseja confirmar essa transferência?\')" class="tip btn btn-success btn-xs">\n\
                            <i class="fa fa-check-circle"></i>\n\
                        </a>';
                    }
                    
                    if (data[8] === '1') {
                        
                        var url = "<?= site_url("products/cancelartransferenciaestoque") ?>";
                        
                        actions += '<a href="' + url + '\/' + data[9] + '" title="Cancelar" onclick="return confirm(\'Tem certeza que deseja cancelar essa transferência?\')" class="tip btn btn-danger btn-xs">\n\
                            <i class="fa fa-times-circle"></i>\n\
                        </a>';
                                
                    }                    
                    
                    $('td:eq(9)', tr).html('<div class="btn-group actions">' + actions + '</div>');

                },
                "oLanguage": {
                    "sLengthMenu": '<select class="form-control input-xs select2">\n\
                        <option>100</option>\n\
                        <option>500</option>\n\
                        <option>1000</option>\n\
                    </select>',
                    "sSearch": '<input type="text" class="form-control input-xs">'
                },
                "fnDrawCallback": function (oSettings) {
                   
                }
            });
            
            $("#filtro").change(function () {
                dataTable._fnAjaxUpdate();
            });            
        });
    </script>
</section>
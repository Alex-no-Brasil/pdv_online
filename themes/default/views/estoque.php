<?php (defined('BASEPATH')) or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title><?= $page_title ?></title>
        <link rel="shortcut icon" href="<?= $assets ?>images/icon.png" />
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <link href="<?= $assets ?>bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= $assets ?>plugins/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css" />
        <link href="<?= $assets ?>plugins/select2/select2.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= $assets ?>plugins/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />
        <link href="<?= $assets ?>plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= $assets ?>plugins/redactor/redactor.css" rel="stylesheet" type="text/css" />
        <link href="<?= $assets ?>dist/css/jquery-ui.css" rel="stylesheet" type="text/css" />
        <link href="<?= $assets ?>dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= $assets ?>dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= $assets ?>dist/css/custom.css" rel="stylesheet" type="text/css" />
        <link href="<?= $assets ?>plugins/daterangepicker/daterangepicker.css" rel="stylesheet" type="text/css" />
        <link href="<?= $assets ?>plugins/iCheck/square/grey.css" rel="stylesheet" type="text/css" />
        <style>
            .box-header>.box-tools {
                position: relative;
                right: 0px;
                top: 20px;
            }
            #spData_wrapper .col-xs-12 {
                padding: 0;
            }
            table.dataTable thead > tr > th {
                font-size: 13px;
                text-align: center;
                padding: 8px 0;
                border-bottom: 1px solid #666;
            }
            table.dataTable > tbody > tr > td {
                text-align: center;
                padding: 8px 0;
                border: none;
                border-bottom: 1px solid #666;
                border-right: 1px solid #666;
            }

            table.dataTable > tbody > tr > td:nth-child(2) {
                text-align: left;
                padding: 8px;
            }

            table.dataTable thead > tr > th > span {
                margin-right: 16px;
            }
            .td-bold {
                font-weight: bold;
            }
            .td-bg-low {
                background-color: #ff0000;
            }
            .td-bg-med {
                background-color: #ffff00;
            }
            .td-bg-high {
                background-color: #a4c2f4;
            }
            .td-bg-est {
                background-color: #fff2cc;
            }
            .td-bg-sale {
                background-color: #b7e1cd;
            }
            .checkbox, .checkbox+.checkbox {
                margin-top: 7px;
            }
            .checkbox label {
                padding-left: 0;
            }
        </style>
        <script src="<?= $assets ?>plugins/jQuery/jQuery-2.1.4.min.js" type="text/javascript"></script>
    </head>
    <body>
        <div class="wrapper">
            <div class="content-wrapper" style="margin-left: 0">
                <section class="content-header">
                    <h1><?= $page_title ?></h1>
                </section>
                <section class="content" style="padding: 15px 0">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="box box-primary" style="margin-bottom: 0">
                                <input type="hidden" id="date_start" value="<?= $date_start ?>">
                                <input type="hidden" id="date_end" value="<?= $date_end ?>">
                            </div>
                            <div class="box-body">
                                <div class="table-responsive">
                                    <table id="spData" class="table table-bordered table-hover table-striped">
                                        <thead>
                                            <tr>
                                                <?php
                                                foreach ($thead as $col) {
                                                    echo "<th><span>$col</span></th>";
                                                }

                                                ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td colspan="<?= count($thead) ?>" class="dataTables_empty"><?= lang('loading_data_from_server') ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        <script src="<?= $assets ?>plugins/daterangepicker/moment.min.js"></script>
        <script src="<?= $assets ?>plugins/daterangepicker/daterangepicker.js"></script>
        <script>
            var dataTable;

            function load_data() {
                $('#spData_processing').css('visibility', 'visible');

                var url = '<?= site_url('reports/get_relatorios_estoque') ?>';

                url += '?alert=' + $('#input-alert').val();

                $('.estoque-check:checked').each(function (i, e) {
                    url += '&estoque[]=' + e.value;
                });

                url += "&start=" + $('#date_start').val();
                url += "&end=" + $('#date_end').val();

                $.get(url, function (d) {
                    load_table(d);
                    $('#spData_processing').css('visibility', 'hidden');
                });
            }

            function column_data(data, type, full) {
                return data[0];
            }

            var thead_th = [];

            function load_table(data) {
                if (dataTable) {
                    dataTable.fnDestroy();
                }

                dataTable = $('#spData').dataTable({
                    sDom: 'ftr',
                    "aLengthMenu": [
                        [10, 25, 50, 100, -1],
                        [10, 25, 50, 100, '<?= lang('all'); ?>']
                    ],
                    "aaSorting": [
                        [data[0] ? data[0].length - 2 : 0, "desc"]
                    ],
                    "iDisplayLength": -1,
                    'bResponsive': true,
                    'bProcessing': true,
                    'sScrollY': (window.innerHeight - 190) + 'px',
                    'aaData': data,
                    'aoColumnDefs': [
<?php
foreach ($thead as $i => $col) {
    echo "{aTargets: [$i], mRender: column_data},";
}
?>
                    ],
                    fnRowCallback: function (tr, data, index) {
                        $.each(data, function (i, d) {
                            var td = $('td:eq(' + i + ')', tr);

                            td.text(d[0]);

                            if (d[1]) {
                                td.addClass(d[1]);
                            }
                        });

                        $('td:eq(3)', tr).text('R$ ' + data[3]);
                    },
                    oLanguage: {
                        sSearch: ''
                    }
                });

                $('#spData').css('width', '100%');
            }

            $(document).ready(function () {
                load_table([]);
                load_data();
            });

        </script>
        <script>
            $('#data_range').daterangepicker({
                "locale": {
                    "format": "DD/MM/YYYY",
                    "separator": " - ",
                    "applyLabel": "Aplicar",
                    "cancelLabel": "Cancelar",
                    "daysOfWeek": [
                        "Dom",
                        "Seg",
                        "Ter",
                        "Qua",
                        "Qui",
                        "Sex",
                        "Sab"
                    ],
                    "monthNames": [
                        "Janeiro",
                        "Fevereiro",
                        "Março",
                        "Abril",
                        "Maio",
                        "Junho",
                        "Julho",
                        "Agosto",
                        "Setembro",
                        "Outubro",
                        "Novembro",
                        "Dezembro"
                    ],
                    "firstDay": 0
                },
                startDate: moment(<?= $date_start ?>),
                endDate: moment(<?= $date_end ?>)
            }, function (start, end, label) {
                $('#date_start').val(start.format('x'));
                $('#date_end').val(end.format('x'));
                load_data();
            });
        </script>

        <script src="<?= $assets ?>bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="<?= $assets ?>plugins/datatables/jquery.dataTables.min.js" type="text/javascript"></script>

    </body>
</html>
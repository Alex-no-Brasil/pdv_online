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
<script src="<?= $assets ?>plugins/daterangepicker/moment.min.js"></script>
<script src="<?= $assets ?>plugins/daterangepicker/daterangepicker.js"></script>

<section class="content">

    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="table-responsive">
                    <table id="table_online" class="table table-striped table-bordered table-condensed table-hover">
                        <thead>
                            <th>A</th>
                            <th>B</th>
                            <th>C</th>
                            <th>D</th>
                            <th>E</th>
                            <th>F</th>
                            <th>G</th>
                            <th>H</th>
                            <th>I</th>
                            <th>J</th>
                            <th>K</th>
                            <th>L</th>
                            <th>M</th>
                            <th>N</th>
                            <th>O</th>
                            <th>P</th>
                            <th>Q</th>
                            <th>R</th>
                            <th>S</th>
                            <th>T</th>
                            <th>U</th>
                            <th>V</th>
                            <th>W</th>
                            <th>X</th>
                            <th>Y</th>
                            <th>Z</th>
                        </thead>
                        <tbody>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>teste</td>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</section>

<script>
    $(document).ready(function () {
        $('#table_online').DataTable();
    });
</script>
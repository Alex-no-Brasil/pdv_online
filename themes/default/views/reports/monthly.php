<style>
    #spData th, #spData td {
        text-align: center;
    }
    #spData th {
        background-color: #f5f5f5;
        border-bottom-width: 1px;
        border-bottom: 1px solid #d2d6de;
    }
    #spData td {
        padding: 4px 8px;
    }
    thead, tfoot {
        display: block;
        width: 100%;
    }
    tbody {
        display: block;
        overflow-y: scroll;
        width: 100%;
        height: 600px;
    }
    th, td {
        width: 10%;
    }
</style>
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-body">
                        <table border="1" id="spData" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>MÊS</th>
                                    <th>LOJAS</th>
                                    <?php
                                    $total_pcs = [];
                                    $total_vendas = [];
                                    $total_cad = [];

                                    foreach ($thead as $col) {
                                        echo "<th>$col</th>";
                                        $total_pcs[$col] = 0;
                                        $total_vendas[$col] = 0;
                                        $total_cad[$col] = 0;
                                    }
                                    ?>
                                    <th>TOTAL</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($rows as $month => $lojas) : ?>
                                <tr>
                                    <td rowspan="3" style="font-weight: bold"><?= $range[$month] ?></td>
                                    <td>pcs</td>
                                    <?php $total = 0; foreach ($lojas as $cod => $data) : ?>
                                        <td><?php
                                        $total += $data['pecas'];
                                        $total_pcs[$cod] += $data['pecas'];
                                        echo $data['pecas'];
                                        ?></td>
                                    <?php endforeach; ?>
                                        <td class="text-bold"><?= $total ?></td>
                                </tr>
                                <tr>
                                     <td>vendas</td>
                                     <?php $total = 0; foreach ($lojas as $cod => $data) : ?>
                                        <td><?php
                                        $total += $data['vendas'];
                                        $total_vendas[$cod] += $data['vendas'];
                                        echo $data['vendas'];
                                        ?></td>
                                    <?php endforeach; ?>
                                     <td class="text-bold"><?= $total ?></td>
                                </tr>
                                <tr>
                                     <td>cadastros</td>
                                     <?php $total = 0; foreach ($lojas as $cod => $data) : ?>
                                        <td><?php
                                        $total += $data['cad'];
                                        $total_cad[$cod] += $data['cad'];
                                        echo $data['cad'];
                                        ?></td>
                                    <?php endforeach; ?>
                                     <td class="text-bold"><?= $total ?></td>
                                </tr>
                                 <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th rowspan="3" style="vertical-align: middle">
                                        TOTAL ANO
                                    </th>
                                    <th>pcs</th>
                                    <?php $total = 0; foreach ($total_pcs as $val) : ?>
                                        <th>
                                        <?php
                                            $total += $val;
                                            echo $val;
                                        ?>
                                        </th>
                                    <?php endforeach; ?>
                                    <th><?= $total ?></th>
                                </tr>
                                <tr>
                                    <th>vendas</th>
                                     <?php $total = 0; foreach ($total_vendas as $val) : ?>
                                        <th>
                                        <?php
                                            $total += $val;
                                            echo $val;
                                        ?>
                                        </th>
                                    <?php endforeach; ?>
                                    <th><?= $total ?></th>
                                </tr>
                                <tr>
                                    <th>cadastros</th>
                                    <?php $total = 0; foreach ($total_cad as $val) : ?>
                                        <th>
                                        <?php
                                            $total += $val;
                                            echo $val;
                                        ?>
                                        </th>
                                    <?php endforeach; ?>
                                    <th><?= $total ?></th>
                                </tr>
                            </tfoot>
                        </table>
                </div>
            </div>
        </div>
    </div>
</section>

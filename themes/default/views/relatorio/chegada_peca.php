<style>
    th, td {
        text-align: center;
    }
</style>

<section class="content">

    <div class="row" style="padding-bottom: 10px;">
        <div class="col-md-1">
            <label>Mês</label>                
            <?= form_dropdown('mes', $meses, set_value('mes', $mes), 'class="form-control form-group-sm select2" id="mes"'); ?>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-body">
                    <br>
                    <div class="table-responsive">                        
                        <table id="table_chegada_peca" class="table table-striped table-bordered table-condensed table-hover">
                            <thead>
                                <tr class="active">
                                    <th style="background:#FFA07A">Oficina</th>
                                    <th>01</th>
                                    <th>02</th>
                                    <th>03</th>
                                    <th>04</th>
                                    <th>05</th>
                                    <th>06</th>
                                    <th>07</th>
                                    <th>08</th>
                                    <th>09</th>
                                    <th>10</th>
                                    <th>11</th>
                                    <th>12</th>
                                    <th>13</th>
                                    <th>14</th>
                                    <th>15</th>
                                    <th>16</th>
                                    <th>17</th>
                                    <th>18</th>
                                    <th>19</th>
                                    <th>20</th>
                                    <th>21</th>
                                    <th>22</th>
                                    <th>23</th>
                                    <th>24</th>
                                    <th>25</th>
                                    <th>26</th>
                                    <th>27</th>
                                    <th>28</th>
                                    <th>29</th>
                                    <th>30</th>
                                    <th>31</th>
                                    <th>Total</th>                                    
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($relatorios as $oficina => $relatorio) : ?>
                                    <tr>
                                        <td><?= $oficina ?></td>

                                        <?php foreach ($relatorio as $dia => $pecas) : ?>
                                            <td><?= $pecas ?></td>
                                        <?php endforeach; ?>

                                        <td>
                                            <?= array_sum($relatorio) ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Defeito</th>

                                    <?php foreach ($defeitos as $dia => $pecas) : ?>
                                        <th><?= $pecas ?></th>
                                    <?php endforeach; ?>

                                    <th>
                                        <?= array_sum($defeitos) ?>
                                    </th> 
                                </tr>

                                <tr>
                                    <th>Total</th>

                                    <?php foreach ($totais as $dia => $pecas) : ?>
                                        <th><?= $pecas ?></th>
                                    <?php endforeach; ?>

                                    <th>
                                        <?= array_sum($totais) ?>
                                    </th>                                   
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>

<script>

    $(document).ready(function () {
        $('#table_chegada_peca').DataTable({
            'sDom': 'tip'
        });
        
        $('#mes').change(function () {
            location.href = 'chegada_peca?mes=' + this.value;
        });
    });

</script>


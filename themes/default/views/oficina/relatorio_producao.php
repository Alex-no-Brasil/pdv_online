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
                                    <th></th>
                                    <th></th>
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
                                    <th>TOTAL</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                                <?php foreach ($modelistas as $nome => $dias) : ?>
                                <tr>
                                    <th>Modelista</th>
                                    <th><?= $nome ?></th>
                                    
                                    <?php foreach ($dias as $dia => $total) : ?>
                                        <td>
                                            <?= $total ?>
                                        </td>
                                    <?php endforeach; ?>
                                    
                                    <td><?= array_sum($dias) ?></td>                                   
                                </tr> 
                                <?php endforeach; ?>

                                <?php foreach ($ampliadores as $nome => $dias) : ?>
                                <tr>
                                    <th>Ampliador</th>
                                    <th><?= $nome ?></th>
                                    
                                    <?php foreach ($dias as $dia => $total) : ?>
                                        <td>
                                            <?= $total ?>
                                        </td>
                                    <?php endforeach; ?>
                                    
                                    <td><?= array_sum($dias) ?></td>                                   
                                </tr> 
                                <?php endforeach; ?>
                                
                                <?php foreach ($piloteiras as $nome => $dias) : ?>
                                <tr>
                                    <th>Piloto</th>
                                    <th><?= $nome ?></th>
                                    
                                    <?php foreach ($dias as $dia => $total) : ?>
                                        <td>
                                            <?= $total ?>
                                        </td>
                                    <?php endforeach; ?>
                                    
                                    <td><?= array_sum($dias) ?></td>                                   
                                </tr> 
                                <?php endforeach; ?>
                            </tbody>

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
            "oLanguage": {
                "sLengthMenu": '<select class="select2">\n\
                    <option>10</option>\n\
                    <option selected>25</option>\n\
                    <option>50</option>\n\
                    <option>100</option>\n\
                    <option>200</option>\n\
                    <option>500</option>\n\
                </select>'
            },
            "iDisplayLength": 25
        });
        
        $('#mes').change(function () {
            location.href = 'relatorio_producao?mes=' + this.value;
        });
    });

</script>


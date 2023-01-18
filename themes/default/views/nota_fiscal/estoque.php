<style>
    td, th{
        text-align: center;
    }
</style>

<!--Modal de novo-->
<div class="modal fade bd-example-modal-lg" id="mdlCriar">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

        </div>
    </div>
</div>

<section class="content">

    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-body">
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="openMdlNovo()">
                        <i class="fa fa-plus"></i> Adicionar
                    </button>
                    <br><br>
                    <div class="table-responsive">                        
                        <table id="table_empresa" class="table table-striped table-bordered table-condensed table-hover">
                            <thead>
                                <tr class="active">
                                    <th>Código</th>
                                    <th>Descrição</th>                                    
                                    <th>NCM</th>
                                    <th>Unidade</th>
                                    <th>Quantidade</th>
                                    <th>Valor unitário</th>
                                    <th>Valor total</th>
                                    <th>Origem</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cadastros as $cadastro): ?>
                                    <tr>
                                        <td><?= $cadastro->codigo ?></td>
                                        <td><?= $cadastro->descricao ?></td>
                                        <td><?= $cadastro->ncm?></td>
                                        <td><?= $cadastro->unidade?></td>
                                        <td><?= $cadastro->quantidade ?></td>
                                        <td>R$ <?= $cadastro->valor_unt ?></td>
                                        <td>R$ <?= $cadastro->valor_total ?></td>
                                        <td><?= $cadastro->origem ?></td>
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
       
    function openMdlNovo(id) {
        
        var url = 'modal_cadastro_estoque';

        if (id > 0) {
            url += '/' + id;
        }

        $.ajax({
            type: 'GET',
            url: url,
            success: function (data) {
                $('#mdlCriar .modal-content').html(data);
                $('#mdlCriar').modal('show');
            }
        });
        
    }

</script>
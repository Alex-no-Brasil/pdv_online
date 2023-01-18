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
                                    <th>Nome</th>
                                    <th>Razão Social</th>                                    
                                    <th>CNPJ</th>
                                    <th>IE</th>
                                    <th>Endereço</th>
                                    <th>Bairro</th>
                                    <th>Cidade-UF</th>
                                    <th>Cep</th>
                                    <th>Telefone</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cadastros as $cadastro): ?>
                                    <tr>
                                        <td><?= $cadastro->nome ?></td>
                                        <td><?= $cadastro->razaoSocial ?></td>
                                        <td><?= $cadastro->cnpj ?></td>
                                        <td><?= $cadastro->ie ?></td>
                                        <td><?= $cadastro->endereco . ", " . $cadastro->numero . " " . $cadastro->complemento?></td>
                                        <td><?= $cadastro->bairro ?></td>
                                        <td><?= $cadastro->cidade . " - " .$cadastro->uf ?></td>
                                        <td><?= $cadastro->cep ?></td>
                                        <td><?= $cadastro->telefone ?></td>
                                        <td>
                                            <a href="#" class="btn btn-warning" onclick="openMdlNovo(<?= $cadastro->id ?>)">
                                                    <i class="fa fa-edit"></i>
                                            </a>
                                            <?php if ($Admin) : ?>
                                                <a href="#" onclick="excluir(<?= $cadastro->id ?>)" class="btn btn-danger">
                                                    <i class="fa fa-trash-o"></i>
                                                </a>
                                            <?php endif; ?>
                                        </td>
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
        
        var url = 'modal_cadastro_empresa';

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

<?php if ($Admin) : ?>
    <script>
        function excluir(id) {
            if (!window.confirm("Tem certeza que deseja excluir?")) {
                return;
            }

            var post = {
                id: id,
                '<?= $this->security->get_csrf_token_name() ?>': '<?= $this->security->get_csrf_hash() ?>'
            };

            $.post('empresa_excluir', post, function (resp) {

                if (resp === 'Ok') {
                    location.reload();
                } else {
                    alert(resp);
                }
            });
        }
    </script>
<?php endif; ?>


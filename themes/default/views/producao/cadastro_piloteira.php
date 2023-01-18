<style>
    th, td {
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
                        <table id="table_oficina" class="table table-striped table-bordered table-condensed table-hover">
                            <thead>
                                <tr class="active">
                                    <th>Código</th>
                                    <th>Nome</th>                                    
                                    <th>Telefone</th>
                                    <th>Endereço</th>
                                    <th>Bairro</th>
                                    <th>Cidade</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cadastros as $cadastro): ?>
                                    <tr>
                                        <td><?= $cadastro->id ?></td>
                                        <td><?= $cadastro->nome ?></td>
                                        <td><?= $cadastro->telefone ?></td>
                                        <td><?= $cadastro->endereco . ", " . $cadastro->numero . " " . $cadastro->complemento ?></td>
                                        <td><?= $cadastro->bairro ?></td>
                                        <td><?= $cadastro->cidade . " - " . $cadastro->uf ?></td>
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
    $(document).ready(function () {
        $('#table_oficina').DataTable({
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
    });

    function openMdlNovo(id) {
        //$("#mdlCriar").modal('show');

        var url = 'modal_cadastro_piloteira';

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

            $.post('piloteira_excluir', post, function (resp) {

                if (resp === 'Ok') {
                    location.reload();
                } else {
                    alert(resp);
                }
            });
        }
    </script>
<?php endif; ?>
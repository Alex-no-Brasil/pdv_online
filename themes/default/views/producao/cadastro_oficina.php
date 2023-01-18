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
                                    <th>REF</th>
                                    <th>Oficina</th>
                                    <th>Prefixo</th>
                                    <th>Telefone</th>
                                    <th>Endereço</th>
                                    <th>Bairro</th>
                                    <th>Cidade</th>
                                    <th>Qtd.Corte</th>
                                    <th>Qtd.Entregue</th>
                                    <th>Qtd.Falta</th>
                                    <th>Qtd.Atraso</th>
                                    <th>Média</th>
                                    <th>Nível</th>
                                    <!--
                                    <th>Maq.Retas</th>
                                    <th>Maq.Overloque</th>
                                    <th>Maq.Galoneira</th>
                                    <th>Maq.Passadoria</th>
                                    -->
                                    <th>Funcionários</th>
                                    <th style="width: 55px">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($oficinas as $oficina): ?>
                                    <tr>
                                        <td><?= $oficina->id ?></td>
                                        <td><?= $oficina->nome ?></td>
                                        <td><?= $oficina->prefixo ?></td>
                                        <td><?= $oficina->telefone ?></td>
                                        <td><?= $oficina->endereco . ", " . $oficina->numero . " " . $oficina->complemento ?></td>
                                        <td><?= $oficina->bairro ?></td>
                                        <td><?= $oficina->cidade . " - " . $oficina->uf ?></td>
                                        <td><?= $oficina->qtd_cortes ?></td>
                                        <td><?= $oficina->qtd_entregas ?></td>
                                        <td><?= $oficina->qtd_cortes - $oficina->qtd_entregas ?></td>
                                        <td><?= $oficina->qtd_atrasos ?></td>
                                        <td><?= $oficina->media ?></td>
                                        <td><?= $oficina->nivel ?></td>

                                        <td><?= $oficina->maq_funcionarios ?></td>                                        
                                        <td width="90px">
                                            <a href="#" class="btn btn-warning" onclick="openMdlNovo(<?= $oficina->id ?>)">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <?php if ($Admin) : ?>
                                                <a href="#" onclick="excluir(<?= $oficina->id ?>)" class="btn btn-danger">
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

        var url = 'modal_cadastro_oficina';

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

    function oficina_submit(form) {

        $.post(form.action, $(form).serialize(), function (resp) {

            if (resp.search('Ok') === 0) {
                location.reload();
            } else {
                alert(resp);
            }
        });

        return false;
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

            $.post('oficina_excluir', post, function (resp) {

                if (resp === 'Ok') {
                    location.reload();
                } else {
                    alert(resp);
                }
            });
        }
    </script>
<?php endif; ?>


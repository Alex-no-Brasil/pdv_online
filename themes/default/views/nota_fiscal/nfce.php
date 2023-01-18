<style>
    td, th{
        text-align: center;
    }
</style>

<!--Modal de novo-->
<div class="modal fade bd-example-modal-lg" id="mdlCriar">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">NFC-e</h4>
            </div>

            <?php echo form_open("notaFiscal/salvar_nfce", ['onsubmit' => 'return oficina_submit(this)']); ?>
            <div class="modal-body">
                <div class="row">

                    <div class="col-xs-6">
                        <div class="form-group" id="selectEmpresa">
                            <label>Empresa</label>
                            <select name="id_empresa" id="id_empresa" data-placeholder="Selecione a Empresa" class="form-control select2 select2-hidden-accessible input-tip selectLojaDestino" style="width:100%;" required>
                                <option value=""></option>
                                <?php foreach ($empresas as $id => $empresa) : ?>
                                    <option value="<?= $id ?>"> <?= $empresa ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-xs-6">
                        <div class="form-group" id="selectCliente">
                            <label>Cliente</label>
                            <select name="id_cliente" id="id_cliente" data-placeholder="Selecione o cliente" class="form-control select2 select2-hidden-accessible input-tip selectLojaDestino" style="width:100%;" required>
                                <option value=""></option>
                                <?php foreach ($clientes as $id => $cliente) : ?>
                                    <option value="<?= $id ?>"> <?= $cliente ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-xs-4">
                        <div class="form-group" id="inputStatus">
                            <div class="form-group">
                                <?= lang("Status", "Status") ?>
                                <?= form_input('status', set_value('status'), 'class="form-control" id="status" '); ?>
                            </div>
                        </div>
                    </div> 

                </div>
                <br>

                <div class="modal-footer">
                    <input type="hidden" name="id" id="modal_cadastro_id">
                    <button id="btnConfirmarTransferencia" type="submit" class="btn btn-success"><i class="fa fa-check"></i>&nbsp; CONFIRMAR</button>
                </div>
                <?= form_close(); ?>
            </div>
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
                                        <th>Empresa</th>
                                        <th>Cliente</th>                                    
                                        <th>Produtos</th>
                                        <th>Status</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($cadastros as $cadastro): ?>
                                        <tr>
                                            <td><?= $cadastro->id_empresa ?></td>
                                            <td><?= $cadastro->id_cliente ?></td>
                                            <td>Produtos</td>
                                            <td><?= $cadastro->status ?></td>
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

        function openMdlNovo() {

            $('#modal_cadastro_id').val('');
            $('#id_empresa').val('').trigger('change');
            $('#id_cliente').val('').trigger('change');
            $('#status').val('');

            $("#mdlCriar").modal('show');

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

                $.post('nfce_excluir', post, function (resp) {

                    if (resp === 'Ok') {
                        location.reload();
                    } else {
                        alert(resp);
                    }
                });
            }
        </script>
    <?php endif; ?>


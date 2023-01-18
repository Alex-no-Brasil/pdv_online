<link href="<?= $assets ?>plugins/daterangepicker/daterangepicker.css" rel="stylesheet" type="text/css" />
<style>
    th, td {
        text-align: center;
    }
    #table_andamento_wrapper .col-xs-6, #table_andamento_wrapper .col-xs-12 {
        padding: 0;
    }
</style>

<!--Modal de novo-->
<div class="modal fade bd-example-modal-lg" id="mdlCriar">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Adicionar andamento</h4>
            </div>
            <?php echo form_open("oficina/andamento_salvar", 'onsubmit="return andamento_submit()"'); ?>
            <div class="modal-body">

                <div class="row">
                    <div class="col-xs-6">
                        <div class="form-group" id="selectOficina">
                            <label>Oficina</label>
                            <select name="oficina_id" id="oficina_id" data-placeholder="Selecione a Oficina" class="form-control select2 select2-hidden-accessible input-tip selectLojaDestino" style="width:100%;" required>
                                <option value=""></option>
                                <?php foreach ($oficinas as $id => $oficina) : ?>
                                    <option value="<?= $id ?>"> <?= $oficina ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div class="form-group" id="selectOficina">
                            <label>Código do Corte</label>
                            <select name="piloto_corte_id" id="piloto_corte_id" data-placeholder="Selecione o código do piloto corte" class="form-control select2 select2-hidden-accessible input-tip selectLojaDestino" style="width:100%;" required>
                                <option value=""></option>
                                <?php foreach ($pilotocortes as $id => $pilotocorte) : ?>
                                    <option value="<?= $id ?>"> <?= $pilotocorte ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-4">
                        <div class="form-group" id="selectOficina">
                            <div class="form-group">
                                <?= lang("Código da Peça", "Código da Peça") ?>
                                <?= form_input('cod_produto', set_value('cod_produto'), 'class="form-control" id="cod_produto" placeholder="9999"'); ?>
                            </div>
                        </div>
                    </div>                    
                    <div class="col-xs-4">
                        <div class="form-group" id="selectOficina">
                            <div class="form-group">
                                <?= lang("Qtd. Peças", "Quantidade") ?>
                                <?= form_input('qtd_pecas', set_value('quantidade'), 'class="form-control" id="qtd_pecas" required placeholder="9999"'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <div class="form-group" id="selectOficina">
                            <div class="form-group">
                                <?= lang("Valor unitário", "Valor unitário") ?>
                                <div class="input-group">
                                    <span class="input-group-addon">R$</span>
                                    <?= form_input('preco_unit', set_value('valor'), 'class="dinheiro form-control" id="preco_unit" required placeholder="99,99"'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <input type="hidden" name="id" id="modal_cadastro_id">
                <button id="btnConfirmarTransferencia" type="submit" class="btn btn-success"><i class="fa fa-check"></i>&nbsp; Salvar</button>
            </div>
            <?= form_close(); ?>
        </div>
        <!-- /.modal-content -->
    </div>
</div>



<!--Modal Acabamento-->
<div class="modal fade bd-example-modal-lg" id="mdlAcabamento">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Acabamento</h4>
            </div>

            <?php echo form_open("oficina/acabamento_salvar"); ?>
            <div class="modal-body">

                <div class="row">
                    <div class="col-xs-6">
                        <div class="form-group" id="selectAcabamento">
                            <label>Acabamento</label>
                            <select name="acabamento_id" id="acabamento_id" data-placeholder="Selecione o responsável pelo Acabamento" class="form-control select2 select2-hidden-accessible input-tip selectLojaDestino" style="width:100%;" required>
                                <option value=""></option>
                                <?php foreach ($acabamentos as $id => $acabamento) : ?>
                                    <option value="<?= $id ?>"> <?= $acabamento ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-xs-6">
                        <div class="form-group" id="quantidadedeVolta_id">
                            <div class="form-group">
                                <?= lang("Quantidade", "Quantidade") ?>
                                <?= form_input('qtd_acabamento', set_value('quantidadeVolta'), 'class="form-control" id="qtd_acabamento" required  placeholder="9999"'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div class="form-group" id="valor_id">
                            <div class="form-group">
                                <?= lang("Valor unitário", "Valor unitário") ?>
                                <div class="input-group">
                                    <span class="input-group-addon">R$</span>
                                    <?= form_input('valor_acabamento', set_value('valor'), 'class="dinheiro form-control" id="valor_acabamento" required placeholder="99,99"'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div class="form-group" id="codigoBarras_id">
                            <div class="form-group">
                                <?= lang("Código de barras", "Código de barras") ?>
                                <?= form_input('cod_barra', set_value('codigoBarras'), 'class="form-control" id="cod_barra" required  placeholder="123456789012" maxlength="12"'); ?>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
            <div class="modal-footer">
                <input type="hidden" name="id" id="acabamento_cad_id">
                <button id="btnConfirmarTransferencia" type="submit" class="btn btn-success"><i class="fa fa-check"></i>&nbsp; Salvar</button>
            </div>
            <?= form_close(); ?>
        </div>
        <!-- /.modal-content -->
    </div>
</div>



<!--Modal Data Recebimento-->
<div class="modal fade bd-example-modal-lg" id="mdlDataRecebimento">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Data recebimento</h4>
            </div>

            <div class="modal-body">

                <div class="row">
                    <br>
                    <!--data-->
                    <div class="row" style="padding-bottom: 10px;">
                        <div class="col-md-12">
                            <div style="width: 30%; margin-left: 35%">
                                <label>Selecione a data:</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <input type="date" class="form-control pull-right" id="data_recebimento">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
            <div class="modal-footer">
                <button id="salva_recebimento" type="button" class="btn btn-success"><i class="fa fa-check"></i>&nbsp; OK</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
</div>


<!--Modal Data Envio oficina-->
<div class="modal fade bd-example-modal-lg" id="mdlEnvio">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Data envio oficina</h4>
            </div>

            <div class="modal-body">

                <div class="row">
                    <br>
                    <!--data-->
                    <div class="row" style="padding-bottom: 10px;">
                        <div class="col-md-12">
                            <div style="width: 30%; margin-left: 35%">
                                <label>Selecione a data:</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <input type="date" class="form-control pull-right" id="data_envio">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
            <div class="modal-footer">
                <button id="salva_data_envio" type="button" class="btn btn-success"><i class="fa fa-check"></i>&nbsp; OK</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
</div>


<!--Modal Data Amostra-->
<div class="modal fade bd-example-modal-lg" id="mdlAmostra">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Data amostra</h4>
            </div>

            <div class="modal-body">

                <div class="row">
                    <br>
                    <!--data-->
                    <div class="row" style="padding-bottom: 10px;">
                        <div class="col-md-12">
                            <div style="width: 30%; margin-left: 35%">
                                <label>Selecione a data:</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <input type="date" class="form-control pull-right" id="data_amostra">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
            <div class="modal-footer">
                <button id="salva_data_amostra" type="button" class="btn btn-success"><i class="fa fa-check"></i>&nbsp; OK</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
</div>


<!--Modal Data Chegada-->
<div class="modal fade bd-example-modal-lg" id="mdlChegada">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Data chegada</h4>
            </div>

            <div class="modal-body">

                <div class="row">
                    <br>
                    <!--data-->
                    <div class="row" style="padding-bottom: 10px;">
                        <div class="col-md-12">
                            <div style="width: 30%; margin-left: 35%">
                                <label>Selecione a data:</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <input type="date" class="form-control pull-right" id="data_chegada">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
            <div class="modal-footer">
                <button id="salva_data_chegada" type="button" class="btn btn-success"><i class="fa fa-check"></i>&nbsp; OK</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
</div>


<!--Modal Data envio2-->
<div class="modal fade bd-example-modal-lg" id="mdlEnvioAcabamento">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Data envio acabamento</h4>
            </div>

            <div class="modal-body">

                <div class="row">
                    <br>
                    <!--data-->
                    <div class="row" style="padding-bottom: 10px;">
                        <div class="col-md-12">
                            <div style="width: 30%; margin-left: 35%">
                                <label>Selecione a data:</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <input type="date" class="form-control pull-right" id="data_acabamento_envio">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
            <div class="modal-footer">
                <button id="salva_acabamento_envio" type="button" class="btn btn-success"><i class="fa fa-check"></i>&nbsp; OK</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
</div>

<!--Modal Chegada acabamento-->
<div class="modal fade bd-example-modal-lg" id="mdlAcabamentoChegada">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Chegada acabamento</h4>
            </div>

            <div class="modal-body">

                <div class="row">
                    <div class="col-md-3">
                        <label>Data</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            <input type="date" class="form-control" id="data_acabamento_chegada">
                        </div>
                    </div>
                    <div class="col-md-4" style="margin-left:70px;">
                        <label>Qtd. Boa</label>
                        <div class="input-group">
                            <input type="number" step="1" class="form-control" id="qtd_boa">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label>Qtd. Defeito</label>
                        <div class="input-group">
                            <input type="number" step="1" class="form-control" id="qtd_defeito">
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button id="salva_acabamento_chegada" type="button" class="btn btn-success"><i class="fa fa-check"></i>&nbsp; OK</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
</div>

<section class="content">

    <!--data-->
    <div class="row" style="padding-bottom: 10px;">
        <div class="col-md-6">
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#mdlCriar" onclick="openMdlNovo()">
                <i class="fa fa-plus"></i> Adicionar
            </button>
        </div>
        <div class="col-md-6">
            <div style="width: 210px; float: right;">
                <div class="input-group">
                    <input type="text" class="form-control pull-right" id="data_range" name="data_range">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </div>
                    <input type="hidden" id="date_start" value="<?= $date_start ?>">
                    <input type="hidden" id="date_end" value="<?= $date_end ?>">
                </div>
            </div>
        </div>
    </div>    

    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-body">
                    <div class="table-responsive">                        
                        <table id="table_andamento" class="table table-striped table-bordered table-condensed table-hover">
                            <thead>
                                <tr class="active">
                                    <th style="background-color:#ADD8E6" title="Código do corte">Cód. Corte</th>
                                    <th style="background-color:#ADD8E6" title="Código da oficina">Oficina</th>
                                    <th style="background-color:#ADD8E6" title="Oficina responsável">Nome</th>
                                    <th style="background-color:#8FBC8F" title="Código da peça">Cod. Peça</th>
                                    <th style="background-color:#8FBC8F" title="Foto da peça">Foto</th>
                                    <th style="background-color:#ADD8E6" title="Número do corte">Corte</th>
                                    <th style="background-color:#ADD8E6" title="Quantidade de peças">Qtd.</th>
                                    <th style="background-color:#ADD8E6" title="Valor por peça">Valor</th>
                                    <th style="background-color:#ADD8E6" title="Data de envio para oficina">Envio</th>
                                    <th style="background-color:#ADD8E6" title="Aprovação da amostra">Amostra</th>                                   
                                    <th style="background-color:#ADD8E6" title="Data de recebimento da peça pela oficina">Recebimento</th>
                                    <th style="background-color:#ADD8E6" title="Data de chegada da peça na loja, após oficina">Chegada</th>
                                    <th style="background-color:#ADD8E6" title="Atraso da oficina">Atraso</th>
                                    <th style="background-color:#ADD8E6" title="Tempo gasto pela Oficina">Média</th>
                                    <th style="background-color:#ADD8E6" title="Nível da oficina">Nível</th>
                                    <th style="background-color:#FFDEAD" title="Responsável acabamento">Acabamento</th>
                                    <th style="background-color:#FFDEAD" title="Quantidade de peças que voltaram">Qtd</th>
                                    <th style="background-color:#FFDEAD" title="Valor do acabamento">Valor</th>
                                    <th style="background-color:#8FBC8F" title="Código de barras">Cod.B</th>
                                    <th style="background-color:#FFDEAD" title="Data de recebimento pelo acabamento">Envio</th>
                                    <th style="background-color:#FFDEAD" title="Data de chegada na loja, após acabamento">Chegada</th>
                                    <th style="background-color:#FFDEAD" title="Data de chegada na loja, após acabamento">Boa</th>
                                    <th style="background-color:#FFDEAD" title="Data de chegada na loja, após acabamento">Defeito</th>
                                    <th style="background-color:#FFDEAD" title="Média do acabamento">Média</th>
                                    <th style="background-color:#FFDEAD" title="Nível do acabamento">Nível</th>
                                    <th style="background-color:#ADD8E6" title="Pagamento Oficina">Pg.O</th>
                                    <th style="background-color:#FFDEAD" title="Pagamento Acabamento">Pg.A</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cadastros as $cadastro) : ?>
                                    <tr>
                                        <td>
                                            <?php if (empty($cadastro->data_acabamento_chegada)) : ?>
                                                <a href="#" onclick="edita_andamento(<?= $cadastro->id . "," . $cadastro->oficina_id . "," . $cadastro->piloto_corte_id . ",'" . $cadastro->cod_produto . "'," . $cadastro->qtd_pecas . "," . $cadastro->preco_unit ?>)">
                                                    <?= $cadastro->cod_corte ?>
                                                </a>
                                            <?php else: ?>
                                                <?= $cadastro->cod_corte ?>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= $cadastro->oficina_id ?></td>
                                        <td><?= $cadastro->oficina_nome ?></td>
                                        <td>
                                            <!--<?php if (empty($cadastro->data_acabamento_chegada)) : ?>
                                                    <a href="#" onclick="edita_andamento(<?= $cadastro->id . "," . $cadastro->oficina_id . "," . $cadastro->piloto_corte_id . ",'" . $cadastro->cod_produto . "'," . $cadastro->qtd_pecas . "," . $cadastro->preco_unit ?>)">
                                                <?= $cadastro->cod_produto ?>
                                                    </a>
                                            <?php else: ?>
                                                <?= $cadastro->cod_produto ?>
                                            <?php endif; ?>-->
                                            <a href="#" onclick="edita_andamento(<?= $cadastro->id . "," . $cadastro->oficina_id . "," . $cadastro->piloto_corte_id . ",'" . $cadastro->cod_produto . "'," . $cadastro->qtd_pecas . "," . $cadastro->preco_unit ?>)">
                                                <?= $cadastro->cod_produto ?>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="<?= $cadastro->arq_mostruario ?>" target="_blank">
                                                <img src="<?= $cadastro->arq_mostruario ?>" height="80">
                                            </a>
                                        </td>
                                        <td><?= $cadastro->qtd_cortes ?></td>

                                        <td><?= $cadastro->qtd_pecas ?></td>
                                        <td>R$ <?= $cadastro->preco_unit ?></td>
                                        <td>
                                            <?php if (empty($cadastro->data_envio)) : ?>
                                                <a href="#" class="btn btn-success" onclick="confirma_envio(<?= $cadastro->id ?>)">
                                                    <i class="fa fa-calendar"></i>
                                                </a>
                                            <?php endif; ?>

                                            <?php if (!empty($cadastro->data_envio) && !empty($cadastro->data_chegada)) : ?>
                                                <?= $this->tec->hrsd($cadastro->data_envio) ?>
                                            <?php endif; ?>

                                            <?php if (!empty($cadastro->data_envio) && empty($cadastro->data_chegada)) : ?>
                                                <a href="#" onclick="edita_data_envio(<?= $cadastro->id . ",'$cadastro->data_envio'" ?>)">
                                                    <?= $this->tec->hrsd($cadastro->data_envio) ?>
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if (!empty($cadastro->data_envio) && empty($cadastro->data_amostra)) : ?>
                                                <a href="#" class="btn btn-success" onclick="confirma_amostra(<?= $cadastro->id ?>)">
                                                    <i class="fa fa-calendar"></i>
                                                </a>
                                            <?php endif; ?>

                                            <?php if (!empty($cadastro->data_amostra) && !empty($cadastro->data_chegada)) : ?>
                                                <?= $this->tec->hrsd($cadastro->data_amostra) ?>
                                            <?php endif; ?>

                                            <?php if (!empty($cadastro->data_amostra) && empty($cadastro->data_chegada)) : ?>
                                                <a href="#" onclick="edita_data_amostra(<?= $cadastro->id . ",'$cadastro->data_amostra'" ?>)">
                                                    <?= $this->tec->hrsd($cadastro->data_amostra) ?>
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if (!empty($cadastro->data_amostra) && empty($cadastro->data_recebimento)) : ?>
                                                <a href="#" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#mdlDataRecebimento" onclick="openMdlDataRecebimento(<?= $cadastro->id ?>)">
                                                    <i class="fa fa-calendar"></i>
                                                </a>
                                            <?php endif; ?>

                                            <?php if (!empty($cadastro->data_recebimento) && !empty($cadastro->data_chegada)) : ?>
                                                <?= $this->tec->hrsd($cadastro->data_recebimento) ?>
                                            <?php endif; ?>

                                            <?php if (!empty($cadastro->data_recebimento) && empty($cadastro->data_chegada)) : ?>
                                                <a href="#" onclick="edita_data_recebimento(<?= $cadastro->id . ",'$cadastro->data_recebimento'" ?>)">
                                                    <?= $this->tec->hrsd($cadastro->data_recebimento) ?>
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if (!empty($cadastro->data_recebimento) && empty($cadastro->data_chegada)) : ?>
                                                <a href="#" class="btn btn-success" onclick="confirma_chegada(<?= $cadastro->id ?>)">
                                                    <i class="fa fa-calendar"></i>
                                                </a>
                                            <?php endif; ?>

                                            <?php if (!empty($cadastro->data_chegada) && $cadastro->acabamento_id > 0) : ?>
                                                <?= $this->tec->hrsd($cadastro->data_chegada) ?>
                                            <?php endif; ?>

                                            <?php if (!empty($cadastro->data_chegada) && $cadastro->acabamento_id == 0) : ?>
                                                <a href="#" onclick="edita_data_chegada(<?= $cadastro->id . ",'$cadastro->data_chegada'" ?>)">
                                                    <?= $this->tec->hrsd($cadastro->data_chegada) ?>
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?= $cadastro->atraso ?>
                                        </td>
                                        <td>
                                            <?= $cadastro->media ?>
                                        </td>
                                        <td>
                                            <?= $cadastro->nivel ?>
                                        </td>
                                        <td>
                                            <?php if ($cadastro->acabamento_id > 0 && !empty($cadastro->data_acabamento_chegada)) : ?>
                                                <!--<?= $acabamentos[$cadastro->acabamento_id] ?>
                                                editar mesmo que já tenha passado dessa etapa-->
                                                <a href="#" onclick="edita_acabamento(<?= "$cadastro->id, $cadastro->acabamento_id,  $cadastro->qtd_acabamento, $cadastro->valor_acabamento, '$cadastro->cod_barra'" ?>)">
                                                    <?= $acabamentos[$cadastro->acabamento_id] ?>
                                                </a>
                                            <?php endif; ?>

                                            <?php if ($cadastro->acabamento_id > 0 && empty($cadastro->data_acabamento_chegada)) : ?>
                                                <a href="#" onclick="edita_acabamento(<?= "$cadastro->id, $cadastro->acabamento_id,  $cadastro->qtd_acabamento, $cadastro->valor_acabamento, '$cadastro->cod_barra'" ?>)">
                                                    <?= $acabamentos[$cadastro->acabamento_id] ?>
                                                </a>
                                            <?php endif; ?>

                                            <?php if (!empty($cadastro->data_chegada) && $cadastro->acabamento_id == 0) : ?>
                                                <a href="#" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#mdlCriar" onclick="openMdlAcabamento(<?= $cadastro->id . "," . $cadastro->qtd_pecas ?>)">
                                                    <i class="fa fa-plus"></i>
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?= $cadastro->qtd_acabamento ?>
                                        </td>
                                        <td>
                                            <?php if ($cadastro->valor_acabamento > 0) : ?>
                                                R$ <?= $cadastro->valor_acabamento ?>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?= $cadastro->cod_barra ?>
                                        </td>
                                        <td>
                                            <?php if ($cadastro->acabamento_id > 0 && empty($cadastro->data_acabamento_envio)) : ?>
                                                <a href="#" class="btn btn-success" onclick="confirma_acabamento_envio(<?= $cadastro->id ?>)">
                                                    <i class="fa fa-calendar"></i>
                                                </a>
                                            <?php endif; ?>

                                            <?php if ($cadastro->acabamento_id > 0 && !empty($cadastro->data_acabamento_envio)) : ?>
                                                <?= $this->tec->hrsd($cadastro->data_acabamento_envio) ?>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if (!empty($cadastro->data_acabamento_chegada) && (!empty($cadastro->data_paga_oficina) || !empty($cadastro->data_paga_acabamento))) : ?>
                                                <?= $this->tec->hrsd($cadastro->data_acabamento_chegada) ?>
                                            <?php endif; ?>

                                            <?php if (!empty($cadastro->data_acabamento_chegada) && empty($cadastro->data_paga_oficina) && empty($cadastro->data_paga_acabamento)) : ?>
                                                <a href="#" onclick="edita_acabamento_chegada(<?= "$cadastro->id, '$cadastro->data_acabamento_chegada',$cadastro->qtd_boa,$cadastro->qtd_defeito" ?>)">
                                                    <?= $this->tec->hrsd($cadastro->data_acabamento_chegada) ?>
                                                </a>
                                            <?php endif; ?>

                                            <?php if ($cadastro->acabamento_id > 0 && !empty($cadastro->data_acabamento_envio) && empty($cadastro->data_acabamento_chegada)) : ?>
                                                <a href="#" class="btn btn-success" onclick="openMdlAcabamentoChegada(<?= $cadastro->id ?>)">
                                                    <i class="fa fa-plus"></i>
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?= $cadastro->qtd_boa ?>
                                        </td>
                                        <td>
                                            <?= $cadastro->qtd_defeito ?>
                                        </td>
                                        <td>
                                            <?= $cadastro->media_acabamento ?>
                                        </td>
                                        <td>
                                            <?= $cadastro->nivel_acabamento ?>
                                        </td>

                                        <!--paga_oficina-->

                                        <?php if (!empty($cadastro->paga_oficina) && !empty($cadastro->data_paga_oficina)) : ?>
                                            <td style="background-color: #00a65a;">
                                                R$ <?= $cadastro->paga_oficina ?>
                                            </td>
                                        <?php endif; ?>

                                        <?php if (!empty($cadastro->paga_oficina) && empty($cadastro->data_paga_oficina)) : ?>
                                            <td style="background-color: #FFA07A">
                                                <a href="#" style="color: #fff; padding: 10px 0" onclick="confirma_paga_oficina(<?= $cadastro->id ?>)">
                                                    R$ <?= $cadastro->paga_oficina ?>
                                                </a>
                                            </td>
                                        <?php endif; ?>

                                        <?php if (empty($cadastro->paga_oficina)) : ?>
                                            <td></td>
                                        <?php endif; ?>

                                        <!--paga_acabamento-->

                                        <?php if (!empty($cadastro->paga_acabamento) && !empty($cadastro->data_paga_acabamento)) : ?>
                                            <td style="background-color: #00a65a;">
                                                R$ <?= $cadastro->paga_acabamento ?>
                                            </td>
                                        <?php endif; ?>

                                        <?php if (!empty($cadastro->paga_acabamento) && empty($cadastro->data_paga_acabamento)) : ?>
                                            <td style="background-color: #FFA07A">
                                                <a href="#" style="color: #fff; padding: 10px 0" onclick="confirma_paga_acabamento(<?= $cadastro->id ?>)">
                                                    R$ <?= $cadastro->paga_acabamento ?>
                                                </a>
                                            </td>
                                        <?php endif; ?>

                                        <?php if (empty($cadastro->paga_acabamento)) : ?>
                                            <td></td>
                                        <?php endif; ?>

                                        <td>
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
    <input type="hidden" id="cadastro_id">
</section>

<!--for money mask-->
<script src="https://igorescobar.github.io/jQuery-Mask-Plugin/js/jquery.mask.min.js">
</script> 
<script src="<?= $assets ?>plugins/daterangepicker/moment.min.js"></script>
<script src="<?= $assets ?>plugins/daterangepicker/daterangepicker.js"></script>
<script>
                                                        $(document).ready(function () {
                                                            $('#table_andamento').DataTable({
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
                                                                "iDisplayLength": 25,
                                                                'sScrollY': (window.innerHeight - 350) + 'px'
                                                            });

                                                            var search = sessionStorage.getItem('search');

                                                            if (search) {
                                                                $('#table_andamento_filter input').val(search).trigger('keyup');
                                                            }

                                                            sessionStorage.removeItem('search');

                                                            $('#data_range').daterangepicker({
                                                                locale: daterange_locale,
                                                                startDate: moment(<?= $date_start ?>),
                                                                endDate: moment(<?= $date_end ?>)
                                                            }, function (start, end, label) {

                                                                location.href = 'andamento?inicio=' + start.format('x') + '&fim=' + end.format('x');
                                                            });
                                                        });

                                                        function openMdlNovo() {

                                                            $('#modal_cadastro_id').val('');
                                                            $('#oficina_id').val('').trigger('change');
                                                            $('#piloto_corte_id').val('').trigger('change');
                                                            $('#cod_produto').val('');
                                                            $('#qtd_pecas').val('');
                                                            $('#preco_unit').val('');

                                                            $("#mdlCriar").modal('show');
                                                        }

                                                        function edita_andamento(id, oficina_id, piloto_corte_id, cod_produto, qtd_pecas, preco_unit) {

                                                            $('#modal_cadastro_id').val(id);
                                                            $('#oficina_id').val(oficina_id).trigger('change');
                                                            $('#piloto_corte_id').val(piloto_corte_id).trigger('change');
                                                            $('#cod_produto').val(cod_produto);
                                                            $('#qtd_pecas').val(qtd_pecas);
                                                            $('#preco_unit').val(preco_unit);

                                                            $("#mdlCriar").modal('show');
                                                        }

                                                        function andamento_submit() {
                                                            sessionStorage.setItem('search', $('#table_andamento_filter input').val());
                                                            return true;
                                                        }

                                                        function openMdlAcabamento(id, qtd_pecas) {
                                                            $('#acabamento_cad_id').val(id);
                                                            $('#acabamento_id').val('').trigger('change');
                                                            $('#qtd_acabamento').val(qtd_pecas);
                                                            $('#valor_acabamento').val('');
                                                            $('#cod_barra').val('');

                                                            $("#mdlAcabamento").modal('show');
                                                        }

                                                        function edita_acabamento(id, acabamento_id, qtd_acabamento, valor_acabamento, cod_barra) {
                                                            $('#acabamento_cad_id').val(id);
                                                            $('#acabamento_id').val(acabamento_id).trigger('change');
                                                            $('#qtd_acabamento').val(qtd_acabamento);
                                                            $('#valor_acabamento').val(valor_acabamento);
                                                            $('#cod_barra').val(cod_barra);

                                                            $("#mdlAcabamento").modal('show');
                                                        }

                                                        function openMdlDataRecebimento(id) {
                                                            $('#cadastro_id').val(id);
                                                            $('#data_recebimento').val('');

                                                            $("#mdlDataRecebimento").modal('show');
                                                        }

                                                        function edita_data_recebimento(id, data_recebimento) {
                                                            $('#cadastro_id').val(id);
                                                            $('#data_recebimento').val(data_recebimento.split(' ')[0]);

                                                            $("#mdlDataRecebimento").modal('show');
                                                        }

                                                        function openMdlAcabamentoChegada(id) {
                                                            $('#cadastro_id').val(id);
                                                            $('#data_acabamento_chegada').val('');
                                                            $('#qtd_boa').val('');
                                                            $('#qtd_defeito').val('');

                                                            $("#mdlAcabamentoChegada").modal('show');
                                                        }

                                                        function edita_acabamento_chegada(id, data_acabamento_chegada, qtd_boa, qtd_defeito) {
                                                            $('#cadastro_id').val(id);
                                                            $('#data_acabamento_chegada').val(data_acabamento_chegada.split(' ')[0]);
                                                            $('#qtd_boa').val(qtd_boa);
                                                            $('#qtd_defeito').val(qtd_defeito);

                                                            $("#mdlAcabamentoChegada").modal('show');
                                                        }

                                                        //money mask
                                                        $('.dinheiro').mask('#.##0,00', {reverse: true});

                                                        function confirmaPagamento() {
                                                            window.confirm('Tem certeza que deseja pagar?');
                                                        }

                                                        function andamento_atualiza(post) {

                                                            post['id'] = $('#cadastro_id').val();

                                                            post['<?= $this->security->get_csrf_token_name() ?>'] = '<?= $this->security->get_csrf_hash() ?>';

                                                            $.post('andamento_atualiza', post, function (resp) {

                                                                sessionStorage.setItem('search', $('#table_andamento_filter input').val());

                                                                location.reload();
                                                            });
                                                        }

                                                        function confirma_envio(id) {

                                                            $('#cadastro_id').val(id);

                                                            $('#data_envio').val('');

                                                            $('#mdlEnvio').modal();
                                                        }

                                                        function edita_data_envio(id, data_envio) {

                                                            $('#cadastro_id').val(id);

                                                            $('#data_envio').val(data_envio.split(' ')[0]);

                                                            $('#mdlEnvio').modal();
                                                        }

                                                        $('#salva_data_envio').click(function () {

                                                            var data_envio = $('#data_envio').val();

                                                            if (data_envio) {
                                                                andamento_atualiza({data_envio: data_envio});
                                                            }
                                                        });

                                                        function confirma_amostra(id) {

                                                            $('#cadastro_id').val(id);

                                                            $('#data_amostra').val('');

                                                            $('#mdlAmostra').modal();
                                                        }

                                                        function edita_data_amostra(id, data_amostra) {

                                                            $('#cadastro_id').val(id);

                                                            $('#data_amostra').val(data_amostra.split(' ')[0]);

                                                            $('#mdlAmostra').modal();
                                                        }

                                                        $('#salva_data_amostra').click(function () {

                                                            var data_amostra = $('#data_amostra').val();

                                                            if (data_amostra) {
                                                                andamento_atualiza({data_amostra: data_amostra});
                                                            }
                                                        });

                                                        $('#salva_recebimento').click(function () {

                                                            var data_recebimento = $('#data_recebimento').val();

                                                            if (data_recebimento) {
                                                                andamento_atualiza({data_recebimento: data_recebimento});
                                                            }
                                                        });

                                                        function confirma_chegada(id) {

                                                            $('#cadastro_id').val(id);
                                                            $('#data_chegada').val('');

                                                            $('#mdlChegada').modal();
                                                        }

                                                        function edita_data_chegada(id, data_chegada) {
                                                            $('#cadastro_id').val(id);
                                                            $('#data_chegada').val(data_chegada.split(' ')[0]);

                                                            $('#mdlChegada').modal();
                                                        }

                                                        $('#salva_data_chegada').click(function () {

                                                            var data_chegada = $('#data_chegada').val();

                                                            if (data_chegada) {
                                                                andamento_atualiza({data_chegada: data_chegada});
                                                            }
                                                        });

                                                        function confirma_acabamento_envio(id) {

                                                            $('#cadastro_id').val(id);

                                                            $('#mdlEnvioAcabamento').modal();
                                                        }

                                                        $('#salva_acabamento_envio').click(function () {

                                                            var acabamento_envio = $('#data_acabamento_envio').val();

                                                            if (acabamento_envio) {
                                                                andamento_atualiza({data_acabamento_envio: acabamento_envio});
                                                            }
                                                        });

                                                        $('#salva_acabamento_chegada').click(function () {

                                                            var data_acabamento_chegada = $('#data_acabamento_chegada').val();

                                                            if (!data_acabamento_chegada) {
                                                                return;
                                                            }

                                                            var qtd_boa = $('#qtd_boa').val();

                                                            if (qtd_boa.length === 0) {
                                                                return;
                                                            }

                                                            var qtd_defeito = $('#qtd_defeito').val();

                                                            if (qtd_defeito.length === 0) {
                                                                return;
                                                            }

                                                            andamento_atualiza({
                                                                data_acabamento_chegada: data_acabamento_chegada,
                                                                qtd_boa: qtd_boa,
                                                                qtd_defeito: qtd_defeito
                                                            });
                                                        });

                                                        function confirma_paga_oficina(id) {
                                                            if (window.confirm('Tem certeza que deseja confirmar o pagamento da oficina?')) {

                                                                $('#cadastro_id').val(id);

                                                                andamento_atualiza({data_paga_oficina: 1});
                                                            }
                                                        }

                                                        function confirma_paga_acabamento(id) {
                                                            if (window.confirm('Tem certeza que deseja confirmar o pagamento do acabamento?')) {

                                                                $('#cadastro_id').val(id);

                                                                andamento_atualiza({data_paga_acabamento: 1});
                                                            }
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

            $.post('andamento_excluir', post, function (resp) {
                location.reload();
            });
        }
    </script>
<?php endif; ?>

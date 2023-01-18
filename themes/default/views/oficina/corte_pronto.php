<link href="<?= $assets ?>plugins/daterangepicker/daterangepicker.css" rel="stylesheet" type="text/css" />
<style>
    th, td {
        text-align: center;
    }
    #table_andamento_wrapper .col-xs-6, #table_andamento_wrapper .col-xs-12 {
        padding: 0;
    }
</style>

<!--Modal Adicionar-->
<div class="modal fade bd-example-modal-lg" id="mdlCriar">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Editar Código</h4>
            </div>
            <?php echo form_open("oficina/cortePronto_salvar", 'onsubmit="return andamento_submit()"'); ?>
            <div class="modal-body">

                <div class="row">
                    <div class="col-xs-4">
                        <div class="form-group" id="selectOficina">
                            <div class="form-group">
                                <?= lang("Código da Peça", "Código da Peça") ?>
                                <?= form_input('cod_produto', set_value('cod_produto'), 'class="form-control" id="cod_produto" placeholder="9999"'); ?>
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

<section class="content">

    <!--data-->
    <div class="row" style="padding-bottom: 10px;">
        <div class="col-md-12">
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
                                    <th style="background-color:#ADD8E6" title="Código da oficina">Oficina</th>
                                    <th style="background-color:#ADD8E6" title="Oficina responsável">Nome</th>
                                    <th style="background-color:#8FBC8F" title="Código da peça">Cod. Peça</th>
                                    <th style="background-color:#8FBC8F" title="Foto da peça">Foto</th>
                                    <th style="background-color:#ADD8E6" title="Número do corte">Corte</th>
                                    <th style="background-color:#ADD8E6" title="Código do corte">Cód. Corte</th>
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
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cadastros as $cadastro) : ?>
                                    <tr>
                                        <td><?= $cadastro->oficina_id ?></td>
                                        <td><?= $cadastro->oficina_nome ?></td>
                                        <td>
                                            <a href="#" onclick="edita_andamento(<?= $cadastro->id . ", '$cadastro->cod_produto'" ?>)">
                                                <?= $cadastro->cod_produto ?>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="<?= $cadastro->arq_mostruario ?>" target="_blank">
                                                <img src="<?= $cadastro->arq_mostruario ?>" height="80">
                                            </a>
                                        </td>
                                        <td><?= $cadastro->qtd_cortes ?></td>
                                        <td><?= $cadastro->cod_corte ?></td>
                                        <td><?= $cadastro->qtd_pecas ?></td>
                                        <td>R$ <?= $cadastro->preco_unit ?></td>
                                        <td><?= $this->tec->hrsd($cadastro->data_envio) ?></td>
                                        <td><?= $this->tec->hrsd($cadastro->data_amostra) ?></td>
                                        <td><?= $this->tec->hrsd($cadastro->data_recebimento) ?></td>
                                        <td><?= $this->tec->hrsd($cadastro->data_chegada) ?></a></td>
                                        <td><?= $cadastro->atraso ?></td>
                                        <td><?= $cadastro->media ?></td>
                                        <td><?= $cadastro->nivel ?></td>
                                        <td><?= $acabamentos[$cadastro->acabamento_id] ?></td>
                                        <td><?= $cadastro->qtd_acabamento ?></td>
                                        <td>R$ <?= $cadastro->valor_acabamento ?></td>
                                        <td><?= $cadastro->cod_barra ?></td>
                                        <td><?= $this->tec->hrsd($cadastro->data_acabamento_envio) ?></td>
                                        <td><?= $this->tec->hrsd($cadastro->data_acabamento_chegada) ?></td>
                                        <td><?= $cadastro->qtd_boa ?></td>
                                        <td><?= $cadastro->qtd_defeito ?></td>
                                        <td><?= $cadastro->media_acabamento ?></td>
                                        <td><?= $cadastro->nivel_acabamento ?></td>
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
                                                        'sScrollY': (window.innerHeight - 300) + 'px'
                                                    });

                                                    $('#data_range').daterangepicker({
                                                        locale: daterange_locale,
                                                        startDate: moment(<?= $date_start ?>),
                                                        endDate: moment(<?= $date_end ?>)
                                                    }, function (start, end, label) {

                                                        location.href = 'corte_pronto?inicio=' + start.format('x') + '&fim=' + end.format('x');
                                                    });
                                                });


                                                function edita_andamento(id, cod_produto) {

                                                    $('#modal_cadastro_id').val(id);
                                                    $('#cod_produto').val(cod_produto);
                                                    
                                                    $("#mdlCriar").modal('show');
                                                }
</script>


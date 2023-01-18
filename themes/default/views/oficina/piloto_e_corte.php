<link href="<?= $assets ?>plugins/daterangepicker/daterangepicker.css" rel="stylesheet" type="text/css" />
<link href="<?= $assets ?>plugins/dropzone/dropzone.min.css" rel="stylesheet" type="text/css"/>

<style>
    th, td {
        text-align: center;
    }

    #table_piloto_wrapper .col-xs-6, #table_piloto_wrapper .col-xs-12 {
        padding: 0;
    }

    /*for dropZone*/
    .var-box-image {
        padding: 0 2px;
        /*float: left;*/
        display: inline-block;
        width: 84px;
        text-align: center;
    }
    .var-image, .var-remove {
        /*float: left;*/
        display: inline-block;
    }
    .dropzone {
        min-width: 140px;
        min-height: 140px;
        border: 1px dashed #0087F7;
        border-radius: 3px;
        background: white;
        cursor: pointer;
        padding: 0;
        /*float: right;*/
    }
    .dropzone .dz-preview {
        margin: 5px;
        min-height: 80px;
    }
    .dropzone .dz-preview .dz-image {
        width: 125px;
        height: 125px;
    }
    .dropzone .dz-preview .dz-details {
        padding: 1em;
    }
    .dropzone .dz-preview .dz-details .dz-filename {
        display: none;
    }
    .bootstrap-filestyle.input-group {
        display: none;
    }
    /*end dropZone*/
</style>


<!--Modal imagem PADRÃO-->
<div class="modal fade bd-example-modal-lg" id="mdlImagemPadrao">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Imagem</h4>
            </div>

            <div class="modal-body">

                <div class="row">

                    <div class="col-xs-3">

                        <!--<label>Adicionar Imagem</label>-->

                        <div id="dropzone">
                            <center>
                                <form action="piloto_corte_upload_mostruario" class="dropzone needsclick" id="adicionarImagemPadrao">
                                    <div class="dz-message needsclick">
                                        <button type="button" class="dz-button" style="margin-top:27.5px">Adicionar imagem</button>
                                        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                                    </div>
                                </form>
                            </center>
                        </div>

                    </div>

                </div>

            </div>
            <div class="modal-footer">
                <button id="salva_mostruario" type="button" class="btn btn-success"><i class="fa fa-check"></i>&nbsp; SALVAR</button>
            </div>

        </div>
        <!-- /.modal-content -->
    </div>
</div>


<!--Modal arquivo PADRÃO-->
<div class="modal fade bd-example-modal-lg" id="mdlArquivoPadrao">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Arquivo</h4>
            </div>

            <div class="modal-body">

                <div class="row">

                    <div class="col-xs-3">

                        <div id="dropzone">
                            <center>
                                <form action="piloto_corte_upload_cad" class="dropzone needsclick" id="adicionarArquivoPadrao">
                                    <div class="dz-message needsclick">
                                        <button type="button" class="dz-button" style="margin-top:27.5px">Subir arquivo 1</button>
                                        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                                    </div>
                                </form>
                            </center>
                        </div>

                    </div>
                    <div class="col-xs-1"></div>
                    <div class="col-xs-6" id="baixarArquivo" style="display:none; margin-bottom: 30px">
                        <label>Arquivo atual</label>
                        <br>
                        <a class="btn btn-success" target="_blank">
                            <i class="fa fa-download"></i>
                        </a>
                    </div>

                </div>

                <br><br>

                <div class="row">

                    <div class="col-xs-3">

                        <div id="dropzone">
                            <center>
                                <form action="piloto_corte_upload_cad" class="dropzone needsclick" id="adicionarArquivoPadrao2">
                                    <div class="dz-message needsclick">
                                        <button type="button" class="dz-button" style="margin-top:27.5px">Subir arquivo 2</button>
                                        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                                    </div>
                                </form>
                            </center>
                        </div>

                    </div>
                    <div class="col-xs-1"></div>
                    <div class="col-xs-6" id="baixarArquivo2" style="display:none; margin-bottom: 30px">
                        <label>Arquivo atual</label>
                        <br>
                        <a class="btn btn-success" target="_blank">
                            <i class="fa fa-download"></i>
                        </a>
                    </div>

                </div>

            </div>
            <div class="modal-footer">
                <button id="salva_arq_cad" type="button" class="btn btn-success"><i class="fa fa-check"></i>&nbsp; SALVAR</button>
            </div>

        </div>
        <!-- /.modal-content -->
    </div>
</div>


<!--Modal Adicionar-->
<div class="modal fade bd-example-modal-lg" id="mdlCriar">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Adicionar piloto e corte</h4>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Código</label>
                                <input type="number" step="1" id="cod_corte">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label>Data</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                <input type="date" class="form-control pull-right" id="data_pedido">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group" id="selectCategoria">
                                <label>Categoria</label>
                                <select name="categoria_id" id="categoria_id" data-placeholder="Selecione a Categoria" class="form-control select2 select2-hidden-accessible input-tip selectLojaDestino" style="width:100%;" required>
                                    <option value="0"></option>
                                    <?php foreach ($categorias as $id => $categoria) : ?>
                                        <option value="<?= $id ?>"> <?= $categoria ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div> 
                        </div>
                        <div class="col-md-12">
                            <label>Mostruário</label>
                            <div id="dropzone">
                                <center>
                                    <form action="piloto_corte_upload_mostruario" class="dropzone needsclick" id="adicionarMostruario">
                                        <div class="dz-message needsclick">
                                            <button type="button" class="dz-button" style="margin-top:27.5px">Adicionar foto</button>
                                            <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                                        </div>
                                    </form>
                                </center>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-6">
                        <a href="#" onclick="preview_mostruario()">
                            <img id="img_mostruario" height="300">
                        </a>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <input type="hidden" id="piloto_corte_id">
                <button id="piloto_corte_cadastro" class="btn btn-success"><i class="fa fa-check"></i>&nbsp; Salvar</button>
            </div>

        </div>
        <!-- /.modal-content -->
    </div>
</div>



<!--Modal modelista-->
<div class="modal fade bd-example-modal-lg" id="mdlModelista">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Selecionar modelista</h4>
            </div>

            <div class="modal-body">

                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group" id="selectModelista">
                            <label>Modelista</label>
                            <select name="cod_modelista" id="cod_modelista" data-placeholder="Selecione a modelista" class="form-control select2 select2-hidden-accessible input-tip selectLojaDestino" style="width:100%;" tabindex="-1" aria-hidden="true">
                                <option value=""></option>
                                <?php foreach ($modelistas as $id => $nome) : ?>
                                    <option value="<?= $id ?>"><?= $nome ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div> 
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Obs. modelista</label>
                            <input type="text" id="obs_cad" placeholder="Observação do modelista 1">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group" id="selectModelista2">
                            <label>Modelista 2</label>
                            <select name="cod_modelista 2" id="cod_modelista2" data-placeholder="Selecione a modelista 2 (se houver)" class="form-control select2 select2-hidden-accessible input-tip selectLojaDestino" style="width:100%;" tabindex="-1" aria-hidden="true">
                                <option value=""></option>
                                <?php foreach ($modelistas as $id => $nome) : ?>
                                    <option value="<?= $id ?>"><?= $nome ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div> 
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Obs. modelista 2</label>
                            <input type="text" id="obs_cad2" placeholder="Observação do modelista 2">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label>Data</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            <input type="date" class="form-control pull-right" id="data_cad">
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button id="salva_modelista" type="button" class="btn btn-success"><i class="fa fa-check"></i>&nbsp; SELECIONAR</button>
            </div>

        </div>
        <!-- /.modal-content -->
    </div>
</div>


<div class="modal fade bd-example-modal-lg" id="mdlProvado">
    <div class="modal-dialog modal-xs">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Data provado</h4>
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
                                    <input type="date" class="form-control pull-right" id="data_provado">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
            <div class="modal-footer">
                <button id="salva_provado" type="button" class="btn btn-success"><i class="fa fa-check"></i>&nbsp; Salvar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
</div>

<!--Modal confirmado-->
<div class="modal fade bd-example-modal-lg" id="mdlConfirma">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Peça confirmada</h4>
            </div>

            <div class="modal-body">

                <div class="row">

                    <div class="col-xs-12">
                        <div class="form-group" id="selectModelista">
                            <label>Confirmada?</label>
                            <select name="confirmado" id="confirmado" data-placeholder="Selecione uma opção" class="form-control select2 select2-hidden-accessible input-tip selectLojaDestino" style="width:100%;" tabindex="-1" aria-hidden="true">
                                <option value="0">Não</option>
                                <option value="1" selected="selected">Sim</option>
                            </select>
                        </div> 
                    </div>

                </div>

            </div>
            <div class="modal-footer">
                <button id="salva_confirmado" type="button" class="btn btn-success"><i class="fa fa-check"></i>&nbsp; SELECIONAR</button>
            </div>

        </div>
        <!-- /.modal-content -->
    </div>
</div>



<!--Modal piloteira-->
<div class="modal fade bd-example-modal-lg" id="mdlPiloteira">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Selecionar piloteira(o)</h4>
            </div>

            <div class="modal-body">

                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group" id="selectModelista">
                            <label>Piloteira(o)</label>
                            <select name="resp_piloto" id="resp_piloto" data-placeholder="Selecione a Piloteira(o)" class="form-control select2 select2-hidden-accessible input-tip selectLojaDestino" style="width:100%;" tabindex="-1" aria-hidden="true">
                                <option value=""></option>
                                <?php foreach ($piloteiras as $id => $nome) : ?>
                                    <option value="<?= $id ?>"><?= $nome ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div> 
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Obs. piloteiro</label>
                            <input type="text" id="obs_piloto" placeholder="Observação do piloteiro">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group" id="selectModelista">
                            <label>Piloteira(o)</label>
                            <select name="resp_piloto" id="resp_piloto2" data-placeholder="Selecione a Piloteira(o) 2 se houver" class="form-control select2 select2-hidden-accessible input-tip selectLojaDestino" style="width:100%;" tabindex="-1" aria-hidden="true">
                                <option value=""></option>
                                <?php foreach ($piloteiras as $id => $nome) : ?>
                                    <option value="<?= $id ?>"><?= $nome ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div> 
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Obs. piloteiro</label>
                            <input type="text" id="obs_piloto2" placeholder="Observação do piloteiro">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label>Data</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            <input type="date" class="form-control pull-right" id="data_piloto">
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button id="salva_piloto" type="button" class="btn btn-success"><i class="fa fa-check"></i>&nbsp; SELECIONAR</button>
            </div>

        </div>
        <!-- /.modal-content -->
    </div>
</div>



<!--Modal Ampliado-->
<div class="modal fade bd-example-modal-lg" id="mdlAmpliado">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Ampliado</h4>
            </div>

            <div class="modal-body">

                <div class="row">

                    <div class="col-xs-3">

                        <div id="dropzone">
                            <center>
                                <form action="piloto_corte_upload_cad" class="dropzone needsclick" id="adicionarAmpliado">
                                    <div class="dz-message needsclick">
                                        <button type="button" class="dz-button" style="margin-top:27.5px">Subir arquivo</button>
                                        <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                                    </div>
                                </form>
                            </center>
                        </div>

                    </div>

                    <div class="col-xs-1"></div>
                    <div class="col-xs-6" id="baixarArquivoAmpliado" style="display:none; margin-bottom: 30px">
                        <label>Arquivo atual</label>
                        <br>
                        <a class="btn btn-success" target="_blank">
                            <i class="fa fa-download"></i>
                        </a>
                    </div>

                </div>

            </div>
            <div class="modal-footer">
                <button id="salva_arq_ampliado" type="button" class="btn btn-success">
                    <i class="fa fa-check"></i>&nbsp; Salvar
                </button>
            </div>

        </div>
        <!-- /.modal-content -->
    </div>
</div>

<!--Modal AMPLIADOR-->
<div class="modal fade bd-example-modal-lg" id="mdlAmpliador">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Selecionar ampliador</h4>
            </div>

            <div class="modal-body">

                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group" id="selectModelista">
                            <label>Ampliador</label>
                            <select name="usuario_ampliador" id="usuario_ampliador" data-placeholder="Selecione o Ampliador" class="form-control select2 select2-hidden-accessible input-tip selectLojaDestino" style="width:100%;" tabindex="-1" aria-hidden="true">
                                <option value=""></option>
                                <?php foreach ($ampliadores as $id => $nome) : ?>
                                    <option value="<?= $id ?>"><?= $nome ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div> 
                    </div>

                    <div class="col-md-3">
                        <label>Data</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            <input type="date" class="form-control pull-right" id="data_ampliado">
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button id="salva_ampliador" type="button" class="btn btn-success"><i class="fa fa-check"></i>&nbsp; SELECIONAR</button>
            </div>

        </div>
        <!-- /.modal-content -->
    </div>
</div>

<!--Modal cortador-->
<div class="modal fade bd-example-modal-lg" id="mdlCortador">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Selecionar cortador(a)</h4>
            </div>

            <div class="modal-body">

                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group" id="selectModelista">
                            <label>Cortador(a)</label>
                            <select name="resp_corte" id="resp_corte" data-placeholder="Selecione o Cortador(a)" class="form-control select2 select2-hidden-accessible input-tip selectLojaDestino" style="width:100%;" tabindex="-1" aria-hidden="true">
                                <option value=""></option>
                                <?php foreach ($cortadores as $id => $nome) : ?>
                                    <option value="<?= $id ?>"><?= $nome ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div> 
                    </div>

                    <div class="col-md-3">
                        <label>Data</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            <input type="date" class="form-control pull-right" id="data_corte">
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button id="salva_corte" type="button" class="btn btn-success"><i class="fa fa-check"></i>&nbsp; SELECIONAR</button>
            </div>

        </div>
        <!-- /.modal-content -->
    </div>
</div>


<section class="content">
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
                        <table id="table_piloto" class="table table-striped table-bordered table-condensed table-hover">
                            <thead>
                                <tr class="active">
                                    <th style="background-color:#ADD8E6" title="Código gerado automáticamente pelo sistema">Código</th>
                                    <th style="background-color:#ADD8E6" title="Data que foi realizado o pedido da peça">Pedido</th>
                                    <th style="background-color:#ADD8E6" title="Categoria da peça">Categoria</th>
                                    <th style="background-color:#ADD8E6" title="Arquivo de amostra">Mostruário</th>
                                    <th style="background-color:#8FBC8F" title="Data de entrega para modelista">Data</th>
                                    <th style="background-color:#8FBC8F" title="Modelista responsável">CAD</th>
                                    <th style="background-color:#8FBC8F" title="Foto feita pela modelista">Arquivo</th>
                                    <th style="background-color:#FFDEAD" title="Data entregue para piloteira(o)">Data</th>
                                    <th style="background-color:#FFDEAD" title="Piloto responsável">Piloto</th>
                                    <th style="background-color:#836FFF" title="Data de provação da peça">Provado</th>
                                    <th style="background-color:#836FFF" title="Aprovação da estilista">Confirmado</th>
                                    <th style="background-color:#FF7F50" title="Data entregue para modelista">Data</th>
                                    <th style="background-color:#FF7F50" title="Arquivo com mais ampliações">Ampliador</th>
                                    <th style="background-color:#FF7F50" title="Arquivo final da peça">Arquivo</th>
                                    <th style="background-color:#FF7F50" title="Tempo de espera entre o PEDIDO e ENTREGA FINAL PARA MODELISTA">Média</th>
                                    <th style="background-color:#FF4500" title="Data entregue para o cortador">Data</th>
                                    <th style="background-color:#FF4500" title="Cortador responsável">Cortador</th>
                                    <th style="background-color:#FF4500" title="Tempo entre a entrega da peça da estilista para o cortador">Media</th>
                                    <th title="Tempo total de produção">Total</th>
                                    <th>

                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cadastros as $cadastro) : ?>
                                    <tr>
                                        <td>
                                            <a href="#" onclick="editar_piloto_corte(<?= "$cadastro->id, $cadastro->cod_corte, '$cadastro->data_pedido', $cadastro->categoria_id" ?>)">
                                                <?= $cadastro->cod_corte ?>
                                            </a>
                                        </td>
                                        <td>
                                            <?= $this->tec->hrsd($cadastro->data_pedido) ?>
                                        </td>
                                        <td>
                                            <?= $categorias[$cadastro->categoria_id] ?>
                                        </td>
                                        <td>
                                            <?php if (empty($cadastro->arq_mostruario)) : ?>
                                                <a href="#" class="btn btn-success" onclick="openMdlImagemPadrao(<?= $cadastro->id ?>)">
                                                    <i class="fa fa-image"></i>
                                                </a>
                                            <?php else : ?>
                                                <a href="#" onclick="editar_piloto_corte(<?= "$cadastro->id, $cadastro->cod_corte, '$cadastro->data_pedido', $cadastro->categoria_id" ?>)">
                                                    <img id="img_mostruario_<?= $cadastro->id ?>" src="<?= $cadastro->arq_mostruario ?>" height="80">
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($cadastro->usuario_cad > 0) : ?>
                                                <?= $this->tec->hrsd($cadastro->data_cad) ?>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($cadastro->usuario_cad > 0) : ?>
                                                <a href="#" onclick="edita_modelista(<?= $cadastro->id . ',' . $cadastro->usuario_cad . ",'$cadastro->data_cad'" . ",'$cadastro->obs_cad'" . ",'$cadastro->usuario_cad2'" . ",'$cadastro->obs_cad2'" ?>)">
                                                    <?= $modelistas[$cadastro->usuario_cad] ?>
                                                    <?php if ($cadastro->usuario_cad2 > 0) : ?>
                                                        <?= $modelistas[$cadastro->usuario_cad2] ?>
                                                    <?php endif; ?>
                                                </a>
                                            <?php else : ?>
                                                <a href="#" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#mdlCriar" onclick="openMdlModelista(<?= $cadastro->id ?>)">
                                                    <i class="fa fa-plus"></i>
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                        <td>

                                            <?php if (!empty($cadastro->arq_cad) || !empty($cadastro->arq_cad2)) : ?>
                                                <a href="#" class="btn btn-success" onclick="openMdlArquivoPadrao(<?= $cadastro->id . ",'$cadastro->arq_cad'" . ", '$cadastro->arq_cad2'" ?>)">
                                                    <i class="fa fa-file"></i>
                                                </a>
                                            <?php endif; ?>

                                            <?php if ($cadastro->usuario_cad > 0 && empty($cadastro->arq_cad) && empty($cadastro->arq_cad2)) : ?>
                                                <a href="#" class="btn btn-success" onclick="openMdlArquivoPadrao(<?= $cadastro->id ?>)">
                                                    <i class="fa fa-plus"></i>
                                                </a>
                                            <?php endif; ?>

                                        </td>
                                        <td>
                                            <?php if (!empty($cadastro->resp_piloto)) : ?>
                                                <?= $this->tec->hrsd($cadastro->data_piloto) ?>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if (!empty($cadastro->resp_piloto)) : ?>
                                                <a href="#" onclick="edita_piloteira(<?= $cadastro->id . ',' . $cadastro->resp_piloto . ',' . "'$cadastro->data_piloto'" . ',' . "'$cadastro->obs_piloto'" . ',' . "'$cadastro->resp_piloto2'" . ',' . "'$cadastro->obs_piloto2'" ?>)">
                                                    <?= $piloteiras[$cadastro->resp_piloto] ?>
                                                </a>
                                            <?php endif; ?>

                                            <?php if (empty($cadastro->resp_piloto) && !empty($cadastro->arq_cad)) : ?>
                                                <a href="#" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#mdlCriar" onclick="openMdlPiloteira(<?= $cadastro->id ?>)">
                                                    <i class="fa fa-plus"></i>
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($cadastro->provado > 0) : ?>
                                                <a href="#" onclick="aprovado(<?= $cadastro->id . ",'" . date('Y-m-d', $cadastro->provado) . "'" ?>)">
                                                    <?= date('d/m/Y', $cadastro->provado) ?>
                                                </a>
                                            <?php endif; ?>

                                            <?php if ($cadastro->provado == 0 && !empty($cadastro->resp_piloto)) : ?>
                                                <a href="#" class="btn btn-success" onclick="aprovado(<?= $cadastro->id ?>)">
                                                    <i class="fa fa-check"></i>
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($cadastro->confirmado > 0) : ?>
                                                Sim
                                            <?php endif; ?>

                                            <?php if ($cadastro->provado > 0 && $cadastro->confirmado == -1) : ?>
                                                Não
                                            <?php endif; ?>

                                            <?php if ($cadastro->provado > 0 && $cadastro->confirmado == 0) : ?>
                                                <a href="#" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#mdlConfirma" onclick="openMdlConfirma(<?= $cadastro->id ?>)">
                                                    <i class="fa fa-check"></i>
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($cadastro->usuario_ampliador > 0) : ?>
                                                <?= $this->tec->hrsd($cadastro->data_ampliado) ?>
                                            <?php endif; ?>
                                        </td>
                                        <td>

                                            <?php if (!empty($cadastro->usuario_ampliador)) : ?>
                                                <a href="#" onclick="edita_ampliador(<?= $cadastro->id . ',' . $cadastro->usuario_ampliador . ',' . "'$cadastro->data_ampliado'" ?>)">
                                                    <?= $ampliadores[$cadastro->usuario_ampliador] ?>
                                                </a>
                                            <?php endif; ?>

                                            <?php if (empty($cadastro->usuario_ampliador) && $cadastro->confirmado > 0) : ?>
                                                <a href="#" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#mdlCriar" onclick="openMdlAmpliador(<?= $cadastro->id ?>)">
                                                    <i class="fa fa-plus"></i>
                                                </a>
                                            <?php endif; ?>

                                        </td>

                                        <td>
                                            <!--<?php if (!empty($cadastro->arq_ampliado)) : ?>
                                                                <a href="<?= $cadastro->arq_ampliado ?>" class="btn btn-success" target="_blank">
                                                                    <i class="fa fa-download"></i>
                                                                </a>
                                            <?php endif; ?>

                                            <?php if ($cadastro->confirmado > 0 && $cadastro->usuario_ampliador > 0 && empty($cadastro->arq_ampliado)) : ?>
                                                                <a href="#" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#mdlAmpliado" onclick="openMdlAmpliado(<?= $cadastro->id ?>)">
                                                                    <i class="fa fa-file"></i>
                                                                </a>
                                            <?php endif; ?>-->

                                            <?php if (!empty($cadastro->arq_ampliado)) : ?>
                                                <a href="#" class="btn btn-success" onclick="openMdlAmpliado(<?= $cadastro->id . ",'$cadastro->arq_ampliado'" ?>)">
                                                    <i class="fa fa-file"></i>
                                                </a>
                                            <?php endif; ?>

                                            <?php if ($cadastro->usuario_ampliador > 0 && empty($cadastro->arq_ampliado)) : ?>
                                                <a href="#" class="btn btn-success" onclick="openMdlAmpliado(<?= $cadastro->id ?>)">
                                                    <i class="fa fa-plus"></i>
                                                </a>
                                            <?php endif; ?>



                                        </td>
                                        <td>
                                            <?php if ($cadastro->usuario_ampliador > 0) : ?>
                                                <?= $cadastro->media_ampliado ?>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($cadastro->resp_corte > 0) : ?>
                                                <?= $this->tec->hrsd($cadastro->data_corte) ?>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if (!empty($cadastro->resp_corte)) : ?>
                                                <a href="#" onclick="edita_corte(<?= $cadastro->id . ',' . $cadastro->resp_corte . ',' . "'$cadastro->data_corte'" ?>)">
                                                    <?= $cortadores[$cadastro->resp_corte] ?>
                                                </a>
                                            <?php endif; ?>

                                            <?php if (empty($cadastro->resp_corte) && !empty($cadastro->arq_ampliado)) : ?>
                                                <a href="#" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#mdlCriar" onclick="openMdlCortador(<?= $cadastro->id ?>)">
                                                    <i class="fa fa-plus"></i>
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($cadastro->resp_corte > 0) : ?>
                                                <?= $cadastro->media_corte ?>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($cadastro->resp_corte > 0) : ?>
                                                <?= $cadastro->media_total ?>
                                            <?php endif; ?>
                                        </td>

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
    <input type="hidden" id="arq_mostruario">
    <input type="hidden" id="arq_cad">
    <input type="hidden" id="arq_cad2">
    <input type="hidden" id="arq_ampliado">
</section>

<script src="<?= $assets ?>plugins/daterangepicker/moment.min.js"></script>
<script src="<?= $assets ?>plugins/daterangepicker/daterangepicker.js"></script>

<script>
                                            $(document).ready(function () {
                                                $('#table_piloto').DataTable({
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
                                                    "aaSorting": [
                                                        [1, "desc"]
                                                    ],
                                                    'sScrollY': (window.innerHeight - 300) + 'px'
                                                });

                                                var search = sessionStorage.getItem('search');

                                                if (search) {
                                                    $('#table_piloto_filter input').val(search).trigger('keyup');
                                                }

                                                sessionStorage.removeItem('search');

                                                $('#data_range').daterangepicker({
                                                    locale: daterange_locale,
                                                    startDate: moment(<?= $date_start ?>),
                                                    endDate: moment(<?= $date_end ?>)
                                                }, function (start, end, label) {

                                                    location.href = 'piloto_e_corte?inicio=' + start.format('x') + '&fim=' + end.format('x');
                                                });

                                                //for dropZone imagemPadrão
                                                new Dropzone('#adicionarImagemPadrao', {
                                                    acceptedFiles: "image/*",
                                                    thumbnailWidth: 125,
                                                    thumbnailHeight: 125,
                                                    addRemoveLinks: true,
                                                    uploadMultiple: false, //for one image to upload
                                                    dictRemoveFile: "Remover",
                                                    init: function () {
                                                        this.on("success", function (file, response) {
                                                            if (response.indexOf('http') > -1) {
                                                                $('#arq_mostruario').val(response);
                                                            } else {
                                                                alert(response);
                                                            }
                                                        });
                                                    },
                                                    removedfile: function (file) {
                                                        file.previewElement.remove();
                                                    }
                                                });
                                                //end dropZone

                                                //for dropZone arquivoPadrão
                                                new Dropzone('#adicionarArquivoPadrao', {
                                                    acceptedFiles: ".adsx, .pdf",
                                                    thumbnailWidth: 125,
                                                    thumbnailHeight: 125,
                                                    addRemoveLinks: true,
                                                    uploadMultiple: false, //for one image to upload
                                                    dictRemoveFile: "Remover",
                                                    init: function () {
                                                        this.on("success", function (file, response) {
                                                            if (response.indexOf('http') > -1) {
                                                                $('#arq_cad').val(response);
                                                            } else {
                                                                alert(response);
                                                            }
                                                        });
                                                    },
                                                    removedfile: function (file) {
                                                        file.previewElement.remove();
                                                    }
                                                });
                                                //end dropZone


                                                //for dropZone arquivoPadrão2
                                                new Dropzone('#adicionarArquivoPadrao2', {
                                                    acceptedFiles: ".adsx, .pdf",
                                                    thumbnailWidth: 125,
                                                    thumbnailHeight: 125,
                                                    addRemoveLinks: true,
                                                    uploadMultiple: false, //for one image to upload
                                                    dictRemoveFile: "Remover",
                                                    init: function () {
                                                        this.on("success", function (file, response) {
                                                            if (response.indexOf('http') > -1) {
                                                                $('#arq_cad2').val(response);
                                                            } else {
                                                                alert(response);
                                                            }
                                                        });
                                                    },
                                                    removedfile: function (file) {
                                                        file.previewElement.remove();
                                                    }
                                                });
                                                //end dropZone


                                                //for dropZone adicionar mostruário
                                                new Dropzone('#adicionarMostruario', {
                                                    acceptedFiles: "image/*",
                                                    thumbnailWidth: 125,
                                                    thumbnailHeight: 125,
                                                    addRemoveLinks: true,
                                                    uploadMultiple: false, //for one image to upload
                                                    dictRemoveFile: "Remover",
                                                    init: function () {
                                                        this.on("success", function (file, response) {
                                                            if (response.indexOf('http') > -1) {
                                                                $('#arq_mostruario').val(response);
                                                            } else {
                                                                alert(response);
                                                            }
                                                        });
                                                    },
                                                    removedfile: function (file) {
                                                        file.previewElement.remove();
                                                    }
                                                });
                                                //end dropZone


                                                //for dropZone ampliado
                                                new Dropzone('#adicionarAmpliado', {
                                                    acceptedFiles: ".adsx, .pdf",
                                                    thumbnailWidth: 125,
                                                    thumbnailHeight: 125,
                                                    addRemoveLinks: true,
                                                    uploadMultiple: false, //for one image to upload
                                                    dictRemoveFile: "Remover",
                                                    init: function () {
                                                        this.on("success", function (file, response) {
                                                            if (response.indexOf('http') > -1) {
                                                                $('#arq_ampliado').val(response);
                                                            } else {
                                                                alert(response);
                                                            }
                                                        });
                                                    },
                                                    removedfile: function (file) {
                                                        file.previewElement.remove();
                                                    }
                                                });
                                                //end dropZone
                                            });


                                            function openMdlImagemPadrao(id) {
                                                $('#cadastro_id').val(id);
                                                $("#mdlImagemPadrao").modal('show');
                                            }

                                            function openMdlArquivoPadrao(id, arq_cad, arq_cad2) {
                                                $('#cadastro_id').val(id);
                                                if (arq_cad) {
                                                    $('#baixarArquivo a').attr('href', arq_cad);
                                                    $('#baixarArquivo').show();
                                                } else {
                                                    $('#baixarArquivo').hide();
                                                }

                                                if (arq_cad2) {
                                                    $('#baixarArquivo2 a').attr('href', arq_cad2);
                                                    $('#baixarArquivo2').show();
                                                } else {
                                                    $('#baixarArquivo2').hide();
                                                }

                                                $("#mdlArquivoPadrao").modal('show');
                                            }

                                            function openMdlNovo() {
                                                $('#piloto_corte_id').val('');
                                                $('#cod_corte').val('');
                                                $('#data_pedido').val('');
                                                $('#categoria_id').val('').trigger('change');

                                                $('#img_mostruario').hide();

                                                $("#mdlCriar").modal('show');
                                            }

                                            function editar_piloto_corte(id, cod_corte, data_pedido, categoria_id) {
                                                $('#piloto_corte_id').val(id);
                                                $('#cod_corte').val(cod_corte);
                                                $('#data_pedido').val(data_pedido.split(' ')[0]);
                                                $('#categoria_id').val(categoria_id).trigger('change');

                                                var img = $('#img_mostruario_' + id);

                                                if (img.length === 1) {
                                                    $('#img_mostruario').attr('src', img.get(0).src);
                                                    $('#img_mostruario').show();
                                                } else {
                                                    $('#img_mostruario').hide();
                                                }

                                                $("#mdlCriar").modal('show');
                                            }

                                            function preview_mostruario() {
                                                window.open($('#img_mostruario').attr('src'), '_blank');
                                            }

                                            function openMdlModelista(id) {
                                                $('#cadastro_id').val(id);
                                                $('#cod_modelista').val('').trigger('change');
                                                $('#obs_cad').val('');
                                                $('#cod_modelista2').val('').trigger('change');
                                                $('#obs_cad2').val('');
                                                $('#data_cad').val('');
                                                $("#mdlModelista").modal('show');
                                            }

                                            function edita_modelista(cadastro_id, usuario_cad, data_cad, obs_cad, usuario_cad2, obs_cad2) {

                                                $('#cadastro_id').val(cadastro_id);
                                                $('#cod_modelista').val(usuario_cad).trigger('change');
                                                $('#data_cad').val(data_cad.split(' ')[0]);
                                                $('#obs_cad').val(obs_cad);
                                                $('#cod_modelista2').val(usuario_cad2).trigger('change');
                                                $('#obs_cad2').val(obs_cad2);

                                                $("#mdlModelista").modal('show');
                                            }

                                            function aprovado(id, data) {

                                                $('#cadastro_id').val(id);

                                                if (data) {
                                                    $('#data_provado').val(data);
                                                }

                                                $("#mdlProvado").modal('show');
                                            }

                                            function openMdlConfirma(id) {
                                                $('#cadastro_id').val(id);
                                                $("#mdlConfirma").modal('show');
                                            }

                                            function openMdlPiloteira(id) {
                                                $('#cadastro_id').val(id);
                                                $('#resp_piloto').val('').trigger('change');
                                                $('#obs_piloto').val('');
                                                $('#resp_piloto2').val('').trigger('change');
                                                $('#obs_piloto2').val('');
                                                $('#data_piloto').val('');
                                                $("#mdlPiloteira").modal('show');
                                            }

                                            function edita_piloteira(cadastro_id, resp_piloto, data_piloto, obs_piloto, resp_piloto2, obs_piloto2) {

                                                $('#cadastro_id').val(cadastro_id);
                                                $('#resp_piloto').val(resp_piloto).trigger('change');
                                                $('#data_piloto').val(data_piloto.split(' ')[0]);
                                                $('#obs_piloto').val(obs_piloto);
                                                $('#resp_piloto2').val(resp_piloto2).trigger('change');
                                                $('#obs_piloto2').val(obs_piloto2);

                                                $("#mdlPiloteira").modal('show');
                                            }

                                            function openMdlAmpliado(id, arq_ampliado) {

                                                $('#cadastro_id').val(id);
                                                if (arq_ampliado) {
                                                    $('#baixarArquivoAmpliado a').attr('href', arq_ampliado);
                                                    $('#baixarArquivoAmpliado').show();
                                                } else {
                                                    $('#baixarArquivoAmpliado').hide();
                                                }
                                                $("#mdlAmpliado").modal('show');
                                            }

                                            function openMdlAmpliador(id) {
                                                $('#cadastro_id').val(id);
                                                $('#usuario_ampliador').val('').trigger('change');
                                                $('#data_ampliado').val('');
                                                $("#mdlAmpliador").modal('show');
                                            }

                                            function edita_ampliador(cadastro_id, usuario_ampliador, data_ampliado) {

                                                $('#cadastro_id').val(cadastro_id);
                                                $('#usuario_ampliador').val(usuario_ampliador).trigger('change');
                                                $('#data_ampliado').val(data_ampliado.split(' ')[0]);

                                                $("#mdlAmpliador").modal('show');
                                            }

                                            function openMdlCortador(id) {
                                                $('#cadastro_id').val(id);
                                                $('#resp_corte').val('').trigger('change');
                                                $('#data_corte').val('');

                                                $("#mdlCortador").modal('show');
                                            }

                                            function edita_corte(cadastro_id, resp_corte, data_corte) {

                                                $('#cadastro_id').val(cadastro_id);
                                                $('#resp_corte').val(resp_corte).trigger('change');
                                                $('#data_corte').val(data_corte.split(' ')[0]);

                                                $("#mdlCortador").modal('show');
                                            }
</script>


<!--for dropZone update image-->
<script src="<?= $assets ?>plugins/dropzone/dropzone.min.js"></script>

<script>
                                            Dropzone.autoDiscover = false;

                                            function piloto_corte_atualiza(post) {
                                                post['id'] = $('#cadastro_id').val();
                                                post['<?= $this->security->get_csrf_token_name() ?>'] = '<?= $this->security->get_csrf_hash() ?>';

                                                $.post('piloto_corte_atualiza', post, function (resp) {

                                                    sessionStorage.setItem('search', $('#table_piloto_filter input').val());

                                                    location.reload();
                                                });
                                            }

                                            $('#piloto_corte_cadastro').click(function () {

                                                var cod_corte = $('#cod_corte').val();

                                                var data_pedido = $('#data_pedido').val();

                                                var categoria_id = $('#categoria_id').val();

                                                if (cod_corte && data_pedido && categoria_id > 0) {

                                                    var post = {
                                                        id: $('#piloto_corte_id').val(),
                                                        cod_corte: cod_corte,
                                                        data_pedido: data_pedido,
                                                        categoria_id: categoria_id,
                                                        '<?= $this->security->get_csrf_token_name() ?>': '<?= $this->security->get_csrf_hash() ?>'
                                                    };

                                                    var arq_mostruario = $('#arq_mostruario').val();

                                                    if (arq_mostruario.indexOf('http') > -1) {
                                                        post['arq_mostruario'] = arq_mostruario;
                                                    }

                                                    $.post('piloto_corte_cadastro', post, function (resp) {

                                                        sessionStorage.setItem('search', $('#table_piloto_filter input').val());

                                                        location.reload();
                                                    });
                                                }
                                            });

                                            $('#salva_mostruario').click(function () {

                                                var arq_mostruario = $('#arq_mostruario').val();

                                                if (arq_mostruario.indexOf('http') > -1) {
                                                    piloto_corte_atualiza({arq_mostruario: arq_mostruario});
                                                }
                                            });

                                            $('#salva_arq_cad').click(function () {

                                                var arq_cad = $('#arq_cad').val();
                                                var arq_cad2 = $('#arq_cad2').val();

                                                if (arq_cad.indexOf('http') > -1) {
                                                    piloto_corte_atualiza({arq_cad: arq_cad});
                                                }

                                                if (arq_cad2.indexOf('http') > -1) {
                                                    piloto_corte_atualiza({arq_cad2: arq_cad2});
                                                }
                                            });

                                            $('#salva_modelista').click(function () {

                                                var usuario_cad = $('#cod_modelista').val();

                                                var obs_cad = $('#obs_cad').val();

                                                var usuario_cad2 = $('#cod_modelista2').val();

                                                var obs_cad2 = $('#obs_cad2').val();

                                                var data_cad = $('#data_cad').val();

                                                if (usuario_cad > 0 && data_cad) {
                                                    piloto_corte_atualiza({
                                                        usuario_cad: usuario_cad,
                                                        obs_cad: obs_cad,
                                                        usuario_cad2: usuario_cad2,
                                                        obs_cad2: obs_cad2,
                                                        data_cad: data_cad
                                                    });
                                                }
                                            });

                                            $('#salva_piloto').click(function () {

                                                var resp_piloto = $('#resp_piloto').val();

                                                var obs_piloto = $('#obs_piloto').val();

                                                var resp_piloto2 = $('#resp_piloto2').val();

                                                var obs_piloto2 = $('#obs_piloto2').val();

                                                var data_piloto = $('#data_piloto').val();

                                                if (resp_piloto && data_piloto) {
                                                    piloto_corte_atualiza({
                                                        resp_piloto: resp_piloto,
                                                        obs_piloto: obs_piloto,
                                                        resp_piloto2: resp_piloto2,
                                                        obs_piloto2: obs_piloto2,
                                                        data_piloto: data_piloto
                                                    });
                                                }
                                            });

                                            $('#salva_provado').click(function () {

                                                var provado = $('#data_provado').val();

                                                if (provado) {
                                                    piloto_corte_atualiza({provado: provado});
                                                }
                                            });

                                            $('#salva_confirmado').click(function () {
                                                var confirmado = $('#confirmado').val();
                                                piloto_corte_atualiza({confirmado: confirmado});
                                            });

                                            $('#salva_ampliador').click(function () {

                                                var usuario_ampliador = $('#usuario_ampliador').val();

                                                var data_ampliado = $('#data_ampliado').val();

                                                if (usuario_ampliador > 0 && data_ampliado) {
                                                    piloto_corte_atualiza({
                                                        usuario_ampliador: usuario_ampliador,
                                                        data_ampliado: data_ampliado
                                                    });
                                                }
                                            });

                                            $('#salva_arq_ampliado').click(function () {

                                                var arq_ampliado = $('#arq_ampliado').val();

                                                if (arq_ampliado.indexOf('http') > -1) {
                                                    piloto_corte_atualiza({arq_ampliado: arq_ampliado});
                                                }
                                            });

                                            $('#salva_corte').click(function () {

                                                var resp_corte = $('#resp_corte').val();

                                                var data_corte = $('#data_corte').val();

                                                if (resp_corte > 0 && data_corte) {
                                                    piloto_corte_atualiza({
                                                        resp_corte: resp_corte,
                                                        data_corte: data_corte
                                                    });
                                                }
                                            });
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

            $.post('piloto_corte_excluir', post, function (resp) {

                if (resp === 'Ok') {
                    location.reload();
                } else {
                    alert(resp);
                }
            });
        }
    </script>
<?php endif; ?>

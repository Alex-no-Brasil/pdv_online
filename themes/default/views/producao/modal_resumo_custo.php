
            <div class="modal-header bg-success">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Adicionar resumo de custo</h4>
            </div>

            <?php echo form_open("producao/resumo_custo_salvar"); ?>
            <div class="modal-body">

                <div class="form-group" id="selectPeca">
                    <label for="status">Peça</label>
                    <select name="piloto_corte_id" id="pilotocorte_id" data-placeholder="Selecione a Peça" class="form-control select2 input-tip selectLojaDestino" style="width:100%;" required>
                        <option value="">Selecione peça</option>
                        <?php foreach ($pilotocortes as $corte_id => $pilotocorte) : ?>
                            <option value="<?= $corte_id ?>"> <?= $pilotocorte ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="row">
                    <div class="col-xs-3">
                        <div class="form-group">
                            <?= lang("Valor tecido", "Valor tecido") ?>
                            <div class="input-group">
                                <span class="input-group-addon">R$</span>
                                <?= form_input('tecido_preco', set_value('precoTecido', $tecido_preco), 'class="dinheiro form-control" id="precoTecido" required placeholder="99,99"'); ?>
                            </div>
                        </div>  
                    </div>
                    <div class="col-xs-3">
                        <div class="form-group">
                            <?= lang("Metragem", "Metragem") ?>
                            <?= form_input('tecido_metro', set_value('valor_metro', $tecido_metro), 'class="dinheiro form-control" required placeholder="0,99"'); ?>
                        </div>  
                    </div>
                   <div class="col-xs-3">
                        <div class="form-group">
                            <?= lang("Corte", "Corte") ?>
                            <div class="input-group">
                                <span class="input-group-addon">R$</span>
                                <?= form_input('valor_corte', set_value('corte', $valor_corte), 'class="dinheiro form-control" id="corte" required placeholder="99,99"'); ?>
                            </div>
                        </div>  
                    </div>
                    <div class="col-xs-3">
                        <div class="form-group">
                            <?= lang("Modelagem", "Modelagem") ?>
                            <div class="input-group">
                                <span class="input-group-addon">R$</span>
                                <?= form_input('valor_modelagem', set_value('modelagem', $valor_modelagem), 'class="dinheiro form-control" id="modelagem" required placeholder="99,99"'); ?>
                            </div>
                        </div>  
                    </div>
                </div>
                
                <hr>
                <h6>Acessórios</h6>

                <div class="row">
                    <div class="col-xs-3">
                        <div class="form-group">
                            <?= lang("Botão", "Botão") ?>
                            <div class="input-group">
                                <span class="input-group-addon">R$</span>
                                <?= form_input('acessorio_botao', set_value('botao', $acessorio_botao), 'class="dinheiro form-control" id="botao"  placeholder="99,99"'); ?>
                            </div>
                        </div>  
                    </div>
                    <div class="col-xs-3">
                        <div class="form-group">
                            <?= lang("Ziper", "Ziper") ?>
                            <div class="input-group">
                                <span class="input-group-addon">R$</span>
                                <?= form_input('acessorio_ziper', set_value('ziper', $acessorio_ziper), 'class="dinheiro form-control" id="ziper"  placeholder="99,99"'); ?>
                            </div>
                        </div>  
                    </div>
                    <div class="col-xs-3">
                        <div class="form-group">
                            <?= lang("Intertela", "Intertela") ?>
                            <div class="input-group">
                                <span class="input-group-addon">R$</span>
                                <?= form_input('acessorio_intertela', set_value('intertela', $acessorio_intertela), 'class="dinheiro form-control" id="intertela"  placeholder="99,99"'); ?>
                            </div>
                        </div>  
                    </div>
                    <div class="col-xs-3">
                        <div class="form-group">
                            <?= lang("Fivela", "fivela") ?>
                            <div class="input-group">
                                <span class="input-group-addon">R$</span>
                                <?= form_input('acessorio_fivela', set_value('fivela', $acessorio_fivela), 'class="dinheiro form-control" id="fivela"  placeholder="99,99"'); ?>
                            </div>
                        </div>  
                    </div>
                    <div class="col-xs-3">
                        <div class="form-group">
                            <?= lang("Cinto", "Cinto") ?>
                            <div class="input-group">
                                <span class="input-group-addon">R$</span>
                                <?= form_input('acessorio_cinto', set_value('cinto', $acessorio_cinto), 'class="dinheiro form-control" id="cinto"  placeholder="99,99"'); ?>
                            </div>
                        </div>  
                    </div>
                    <div class="col-xs-3">
                        <div class="form-group">
                            <?= lang("Ombrera", "Ombrera") ?>
                            <div class="input-group">
                                <span class="input-group-addon">R$</span>
                                <?= form_input('acessorio_ombrera', set_value('ombrera', $acessorio_ombrera), 'class="dinheiro form-control" id="ombrera"  placeholder="99,99"'); ?>
                            </div>
                        </div>  
                    </div>
                    <div class="col-xs-3">
                        <div class="form-group">
                            <?= lang("Elástico", "Elástico") ?>
                            <div class="input-group">
                                <span class="input-group-addon">R$</span>
                                <?= form_input('acessorio_elastico', set_value('elastico', $acessorio_elastico), 'class="dinheiro form-control" id="elastico" placeholder="99,99"'); ?>
                            </div>
                        </div>  
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <input type="hidden" name="id" value="<?= $id ?>">
                <button id="btnConfirmarTransferencia" type="submit" class="btn btn-success"><i class="fa fa-check"></i>&nbsp; CONFIRMAR</button>
            </div>
            <?= form_close(); ?>

<script>
    //money mask
    $('.dinheiro').mask('#.##0,00', {reverse: true});
    
     $('#pilotocorte_id').val('<?= $piloto_corte_id ?>');
</script>
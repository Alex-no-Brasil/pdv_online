<link href="<?= $assets ?>plugins/daterangepicker/daterangepicker.css" rel="stylesheet" type="text/css" />
<link href="<?= $assets ?>plugins/iCheck/square/grey.css" rel="stylesheet" type="text/css" />
<style>
    table, th, td {
        border:1px solid black;
    }
    th{
        text-align: center;
    }
    .tdEsquerda{
        border-left-color: #fff;
        border-top-color: #fff;
        border-bottom-color: #fff;
    }
    .tdDireita{
        border-right-color: #fff;
        border-top-color: #fff;
        border-bottom-color: #fff;
    }
    .tdCentro{
        border-top-color: #fff;
        border-bottom-color: #fff;
    }
</style>
<script src="<?= $assets ?>plugins/daterangepicker/moment.min.js"></script>
<script src="<?= $assets ?>plugins/daterangepicker/daterangepicker.js"></script>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header" style="padding: 10px 10px 0 10px">  
                </div>


                <br><br>
                <div class="row" style="padding:0 5% 0 5%">

                    <div class="col-xs-2">
                        <div class="form-group" id="selectCategoria">
                            <label>Loja</label>
                            <select name="cod_loja" id="cod_loja" data-placeholder="Selecione a Loja" class="form-control select2 select2-hidden-accessible input-tip selectLojaDestino" style="width:100%;" required>
                                <?php foreach ($lojas as $id => $loja) : ?>
                                    <option value="<?= $id ?>"> <?= $loja ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div> 
                    </div>

                    <div class="col-xs-2">
                        <div class="form-group" id="selectCategoria">
                            <label>Categoria</label>
                            <select name="categoria_id" id="categoria_id" data-placeholder="Selecione a Categoria" class="form-control select2 select2-hidden-accessible input-tip selectLojaDestino" style="width:100%;" required>
                                <?php foreach ($categorias as $id => $categoria) : ?>
                                    <option value="<?= $id ?>"> <?= $categoria ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div> 
                    </div>
                    
                    <div class="col-xs-2" id="box-manga" style="display: none">
                        <div class="form-group">
                            <label>Manga</label>
                            <select id="manga" class="form-control select2 select2-hidden-accessible input-tip selectLojaDestino" style="width:100%;">
                                <option value="">Todas</option>
                                <option value="longa">1 - Inverno</option>
                                <option value="curta">2 - Verão</option>
                            </select>
                        </div> 
                    </div>

                    <div class="col-xs-2">
                        <div class="form-group" id="selectCategoria">
                            <div class="form-group">
                                <?= lang("Valor", "Valor") ?>
                                <?= form_input('valor', set_value('valor'), 'class="form-control dinheiro" id="valor" required placeholder="99,99"'); ?>
                            </div>
                        </div> 
                    </div>

                    <div class="col-xs-2" style="padding: 0">
                        <label style="margin-top: 30px; cursor: pointer; font-weight: 500">
                            <input type="checkbox" id="todos-valor">&nbsp;&nbsp;Todos preços
                        </label>
                    </div>
                    
                    <div class="col-xs-1" style="text-align:right">
                        <a href="#" class="btn btn-success" onclick="filtro()" style="margin-top:25px;">
                            <i class="fa fa-filter"></i>
                            Filtrar
                        </a>
                    </div>
                    <div class="col-xs-1" style="text-align:left">
                        <a href="#" class="btn btn-success" onclick="exporta()" style="margin-top:25px;">
                            <i class="fa fa-file"></i>
                            Exportar
                        </a>
                    </div>

                </div>

                <br>

                <div style="margin-left: 5%; width:10%; padding: 2px; border: solid 1px black; border-bottom: none">Loja <span id="span-loja"></span></div>
                <center>                    
                    <div style="width:90%; background-color: yellow;color: red;font-size: 25px; padding: 3px;border: solid 1px black;border-bottom: none">
                        <b>
                            PEÇA DE R$ <span id="span-valor"></span>
                        </b>
                    </div>
                    <table style="width:90%;" id="myTable">
                        <thead>
                            <tr>
                                <th width="5%">CÓDIGO</th>
                                <th width="20%">TIPO</th>
                                <th width="5%">ESTOQUE</th>
                                <th width="10%">VALOR</th>
                                <th width="20%">CONFIRMAÇÃO</th>
                                <th width="40%">OBS</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </center>
                <br><br>

            </div>
        </div>
    </div>
</section>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js">
</script>
<script>

    var categorias_manga = {
        "2": "VESTIDOS",
        "3": "BLUSA",
        "7": "MACACOES",
        "8": "MACAQUINHO",
        "10": "CONJUNTO ",
        "14": "CROPPED",
        "17": "VESTIDOS DE FESTA"
    };
    
    $('#categoria_id').change(function () {
        
        $('#manga').val('').trigger('change');
        
        if (categorias_manga[this.value]) {
            $('#box-manga').show();
        } else {
            $('#box-manga').hide();
        }
            
    });

    function filtro() {
        var url = 'conferencia_filtro?';
        url += 'cod_loja=' + $('#cod_loja').val();
        url += '&categoria_id=' + $('#categoria_id').val();
        url += '&valor=' + $('#valor').val();
        url += '&manga=' + $('#manga').val();
        url += '&todos_valor=' + $('#todos-valor').is(':checked');

        if ($('#todos-valor').is(':checked')) {
            $('#valor').val('');
        }
        
        $('#span-valor').html($('#valor').val());

        $('#span-loja').html($('#cod_loja').val());

        $.get(url, function (html) {
            $('#myTable tbody').html(html);
        });
    }

    function exporta() {

        var url = 'exporta_conferencia?';
        url += 'cod_loja=' + $('#cod_loja').val();
        url += '&categoria_id=' + $('#categoria_id').val();
        url += '&valor=' + $('#valor').val();
        url += '&manga=' + $('#manga').val();
        url += '&todos_valor=' + $('#todos-valor').is(':checked');

        location.href = url;
    }

    $('.dinheiro').mask('#.##0,00', {reverse: true});
</script>
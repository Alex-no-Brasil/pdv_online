<?php (defined('BASEPATH')) OR exit('No direct script access allowed'); ?>
<style>
    .input-group .input-group-addon {
        background-color: #eee;
    }
</style>
<section class="content">
    <?php
    $attrib = array('class' => 'validation', 'role' => 'form');
    echo form_open("cards/save_tax", $attrib);
    ?>
    <div class="row">
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">Débito</h3>
                </div>
                <div class="box-body">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Taxa cliente</label>
                            <div class="input-group">
                                <input type="number" name="debit[tax_client]" id="debit_tax_client" class="form-control text-right" step="0.1" required>
                                <div class="input-group-addon">
                                    %
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Taxa bandeira</label>
                            <div class="input-group">
                                <input type="number" name="debit[tax_real]" id="debit_tax_real" class="form-control text-right" step="0.1" required>
                                <div class="input-group-addon">
                                    %
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">Crédito</h3>
                </div>
                <div class="box-body">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Taxa cliente</label>
                            <div class="input-group">
                                <span class="input-group-addon">1x</span>
                                <input type="number" name="credit[tax_client]" id="credit_tax_client" class="form-control text-right" step="0.1" required>
                                <div class="input-group-addon">
                                    %
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Taxa bandeira</label>
                            <div class="input-group">
                                <input type="number" name="credit[tax_real]" id="credit_tax_real" class="form-control text-right" step="0.1" required>
                                <div class="input-group-addon">
                                    %
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    for ($i = 2; $i <= 6; $i++) {
                        $type = "credit_$i" . "x";
                        echo '
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon">' . $i . 'x</span>
                                <input type="number" name="' . $type . '[tax_client]" id="' . $type . '_tax_client" class="form-control text-right" step="0.1" required>
                                <div class="input-group-addon">
                                    %
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="input-group">
                                <input type="number" name="' . $type . '[tax_real]" id="' . $type . '_tax_real" class="form-control text-right" step="0.1" required>
                                <div class="input-group-addon">
                                    %
                                </div>
                            </div>
                        </div>
                    </div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <?= form_submit('save_tax', 'Salvar', 'class="btn btn-primary"'); ?>
            </div>
        </div>
    </div>
    <?php echo form_close(); ?>
    <div class="clearfix"></div>
</section>
<?php
if ($tax['debit']) {
    echo "<script>
    $('#debit_tax_client').val({$tax['debit']->tax_client});
    $('#debit_tax_real').val({$tax['debit']->tax_real});
</script>";
}

if ($tax['credit']) {
    echo "<script>
    $('#credit_tax_client').val({$tax['credit']->tax_client});
    $('#credit_tax_real').val({$tax['credit']->tax_real});
</script>";
}

echo "<script>";
for ($i = 2; $i <= 6; $i++) {
    $type = "credit_$i" . "x";

    if (isset($tax[$type])) {
        echo "
        $('#$type" . "_tax_client').val({$tax[$type]->tax_client});
        $('#$type" . "_tax_real').val({$tax[$type]->tax_real});";
    }
}
echo "</script>";
?>
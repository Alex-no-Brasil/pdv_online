<section class="content">
	<div class="row">
		<div class="col-xs-12">
			<div class="box box-primary">
				<div class="box-header">
					<h3 class="box-title"><?= lang('enter_info'); ?></h3>
				</div>
				<div class="box-body">
					<?php echo form_open("lojas/adicionar");?>

					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label" for="code">Código</label>
							<?= form_input('cod', set_value('cod'), 'class="form-control input-sm" id="cod"'); ?>
						</div>

						<div class="form-group">
							<label class="control-label" for="nome">Nome</label>
							<?= form_input('nome', set_value('nome'), 'class="form-control input-sm" id="email_address"'); ?>
						</div>

						<div class="form-group">
							<label class="control-label" for="obs">Observações</label>
							<?= form_textarea('obs', set_value('obs'), 'class="form-control input-sm" id="obs"');?>
						</div>
					
						<div class="form-group">
							<?php echo form_submit('adicionar_loja', "Adicionar Loja", 'class="btn btn-primary"');?>
						</div>
					</div>
					<?php echo form_close();?>
				</div>
			</div>
		</div>
	</div>
</section>

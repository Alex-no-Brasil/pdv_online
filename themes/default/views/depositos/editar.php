<section class="content">
	<div class="row">
		<div class="col-xs-12">
			<div class="box box-primary">
				<div class="box-header">
					<h3 class="box-title"><?= lang('update_info'); ?></h3>
				</div>
				<div class="box-body">
					<?php echo form_open("depositos/editar/" . $deposito->id); ?>

					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label" for="code">Código</label>
							<?= form_input('cod', set_value('cod', $deposito->cod), 'class="form-control input-sm" id="cod"'); ?>
						</div>

						<div class="form-group">
							<label class="control-label" for="nome">Nome</label>
							<?= form_input('nome', set_value('nome', $deposito->nome), 'class="form-control input-sm" id="email_address"'); ?>
						</div>

						<div class="form-group">
							<label class="control-label" for="obs">Observações</label>
							<?= form_textarea('obs', set_value('obs', $deposito->obs), 'class="form-control input-sm" id="obs"'); ?>
						</div>

						<div class="form-group">
							<?php echo form_submit('editar_deposito', "Editar Depósito", 'class="btn btn-primary"'); ?>
						</div>
					</div>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
	</div>
</section>
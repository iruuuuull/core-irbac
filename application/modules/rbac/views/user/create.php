<h1 class="page-title">Buat User</h1>

<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
            	<?= form_open('', ['class' => 'form-horizontal']); ?>

            		<div class="form-group row">
            			<?= form_label('Username', 'id_username', ['class' => 'control-label col-md-2']); ?>
            			<div class="col-md-4">
            				<?= form_input('User[username]', $user->username ?? '', [
            					'class' => 'form-control',
            					'id' => 'id_username',
            				]); ?>
            			</div>
            		</div>

            		<div class="form-group row">
            			<?= form_label('E-mail', 'id_email', ['class' => 'control-label col-md-2']); ?>
            			<div class="col-md-4">
            				<?= form_input('User[email]', $user->email ?? '', [
            					'class' => 'form-control',
            					'id' => 'id_email',
            				]); ?>
            			</div>
            		</div>

            		<div class="form-group row">
            			<?= form_label('Password', 'id_password', ['class' => 'control-label col-md-2']); ?>
            			<div class="col-md-4">
            				<?= form_password('User[password]', '', [
            					'class' => 'form-control',
            					'id' => 'id_password',
            				]); ?>
            			</div>
            		</div>

            		<div class="form-group row">
            			<?= form_label('Re-type Password', 'id_password_conf', ['class' => 'control-label col-md-2']); ?>
            			<div class="col-md-4">
            				<?= form_password('password_conf', '', [
            					'class' => 'form-control',
            					'id' => 'id_password_conf',
            				]); ?>
            			</div>
            		</div>

            		<div class="form-group row">
            			<?= form_label('Groups', 'id_group', ['class' => 'control-label col-md-2']); ?>
            			<div class="col-md-4">
            				<?php 
                                foreach ($groups as $key => $group):
                                    $checked = false;
                                    if (isset($user) && in_array($group->id, $user_group)) {
                                        $checked = true;
                                    }
                            ?>
            					<div class="checkbox" style="margin-left: 20px">
            						<label>
            							<?= form_checkbox('UserGroup[group_id]', $group->id, $checked); ?>
            							<?= $group->label ?>
            						</label>
            					</div>
            				<?php endforeach ?>
            			</div>
            		</div>

                    <div class="form-group row">
                        <?= form_label('Status User', 'id_status', ['class' => 'control-label col-md-2']); ?>
                        <div class="col-md-4" style="padding-top: 8px;">
                            <?= form_hidden('User[status]', 0); ?>
                            <?= form_checkbox('User[status]', 1, $user->status ?? 0, [
                                'class' => 'make-switch',
                                'id' => 'id_status',
                                'data-size' => 'mini',
                                'data-on-text' => 'Aktif',
                                'data-off-text' => 'NonAktif'
                            ]); ?>
                        </div>
                    </div>

            		<div class="form-group row">
            			<div class="col-md-6 text-right">
            				<a href="<?= site_url('/rbac/user') ?>" class="btn btn-danger">Kembali</a>
            				<button type="submit" class="btn btn-primary"><i class="fa fa-paper-plane"></i> Submit</button>
            			</div>
            		</div>

            	<?= form_close() ?>
            </div>
        </div>
    </div>
</div>

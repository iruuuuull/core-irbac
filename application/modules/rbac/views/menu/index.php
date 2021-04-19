<h1 class="page-title"><?= $title ?></h1>

<div class="row">
    <div class="col-md-12">
        <div class="card light bordered">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <?= form_open(); ?>

                            <div class="form-group">
                                <?= form_label('Tipe Menu', 'id_title', ['class' => 'control-label label-required']); ?>
                                <?= form_input('MenuType[title]', def($model, 'title'), ['class' => 'form-control', 'id' => 'id_title']); ?>
                            </div>

                            <div class="form-group">
                                <?= form_label('Deskripsi', 'id_description', ['class' => 'control-label label-required']); ?>
                                <?= form_input('MenuType[description]', def($model, 'description'), ['class' => 'form-control', 'id' => 'id_description']); ?>
                            </div>

                            <div class="form-group">
                                <?= $this->html->submitButton(
                                    !empty($model->title) ? 'Update' : 'Save', [
                                        'class' => 'btn btn-primary'
                                    ]
                                ) ?>
                            </div>

                        <?= form_close() ?>
                    </div>

                    <div class="col-md-8">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-stripped" id="table-menu">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Judul Menu</th>
                                        <th>Deskripsi</th>
                                        <th>&nbsp;</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($list_type as $key => $value): ?>  
                                    <tr>
                                        <td><?= ($key + 1) ?></td>
                                        <td><?= $value->title ?></td>
                                        <td><?= $value->description ?></td>
                                        <td>
                                            <?= $this->html->a('List Tautan', "/rbac/menu/list-menu/{$value->id}") ?> | 
                                            <?= $this->html->a('Update', "/rbac/menu/{$value->id}") ?> | 
                                            <?= $this->html->a('Delete', "/rbac/menu/hapus/{$value->id}", [
                                                'onclick' => 'return confirm("Yakin ingin hapus?")'
                                            ]) ?>
                                        </td>
                                    </tr>
                                    <?php endforeach ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
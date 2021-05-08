<div class="form grid-1">
    <div class="card">
        <div class="card-header d-flex flex-space-between">
            <h6><?= $title ?></h6>

            <div class="select-export">
                <?= anchor('/master/unit-campus/tambah', '<i class="fa fa-plus"></i> Tambah', [
                    'class' => 'btn-normal btn-primary'
                ]); ?>
            </div>
        </div>

        <div class="card-body">
            <table id="table-unit" class="table table-hover table-stripped table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Parent</th>
                        <th>Level</th>
                        <th>Status</th>
                        <th>Kerja Sama</th>
                        <th>Tipe</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

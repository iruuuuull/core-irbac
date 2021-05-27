        <div class="py-1">
            <div class="card">
                <div class="d-flex flex-start">
                    <?= anchor('/pengelolaan-mahasiswa/tambah', '<i class="fa fa-plus"></i> ADD NEW STUDENT', [
                        'class' => 'btn btn-link btn-primary'
                    ]); ?>
                </div>
            </div>
        </div>
        <div class="py-1">
            <div class="card">
                <div class="card-header d-flex flex-space-between">
                    <h6><?= $title ?></h6>
                    <div class="select-export">
                        <div type="button" class="btn btn-link btn-secondary dropdown-button"><i class="fas fa-file-export"></i> Export</div>

                        <div class="type-export">
                            <a href="#" id="pdf"><i class="far fa-file-pdf"></i> PDF</a>
                            <a href="#" id="excel"><i class="far fa-file-excel"></i> EXCEL</a>
                            <a href="#" id="print"><i class="fas fa-print"></i> PRINT</a>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="table-list-siswa" class="table-list">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Mahasiswa</th>
                                <th>Kampus</th>
                                <th>Angkatan</th>
                                <th>Jurusan</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
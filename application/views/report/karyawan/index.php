<h1 class="page-title"><?= $title ?> </h1>
<input type="hidden" name="id_group" id="group_id" value="<?= $admin ?>" readonly>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
            	<div class="table-toolbar">
                    <div class="row">

                        <div class="form_add">  

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-2">
                                        <?= form_label('Group Unit', 'label_group', ['class' => 'control-label']); ?>
                                    </div>
                                    <div class="col-md-3">
                                    <select name="group_unit" class="form-control" required='' id="id_group_unit">
                                        <option value="">- Pilih Group Unit -</option>
                                        <?php
                                        foreach ($data_group->result() as $dataGroup) {
                                            echo "<option value='" . $dataGroup->kode_unit."@".$dataGroup->sbu_id."@".$dataGroup->id ."'>" . $dataGroup->nama_unit . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-2">
                                            <?= form_label('Unit', 'label_unit', ['class' => 'control-label']); ?>
                                    </div>
                                    <div class="col-md-3">
                                        <?= form_dropdown('unit', [
                                            '' => '- Pilih Unit -',
                                        ],'', [
                                            'class' => 'form-control',
                                            'id' => 'id_unit',
                                        ]); ?>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-2">
                                        <?= form_label('Bagian', 'label_bagian', ['class' => 'control-label']); ?>
                                    </div>
                                    <div class="col-md-3">
                                        <?= form_dropdown('bagian', [
                                            'all' => '- Semua Bagian -',
                                        ],'', [
                                            'class' => 'form-control',
                                            'id' => 'id_bagian',
                                        ]); ?>
                                    </div>
                                    <div class="col-md-1"> 
                                        <button type="button" id="btn-cari-data" style="margin-top:5px;" class="btn btn-sm green"><i class="fa fa-check"></i> Proses</button>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
                <?php
                $i = 1 ;
                if($Karyawans==''){$display = 'none';}else{$display = 'block';} ?>
                <div id="table-admin" style="display:<?= $display?>">
                 <div class="table-responsive"><table class="table table-bordered table-hover table-responsive" id="admin_1">
                    <div style="margin-top:70px;">
                </div>
                   <thead>
                    <tr style="background-color:#031727;color:white;">
                        <th>No</th>
                        <th>NIK</th>
                        <th>Nama</th>
                        <th>Bagian</th>
                        <th>Jabatan</th>
                        <th>Email</th>
                        <th>Unit</th>
                        <th>Status</th>
                        <th>Tanggal Selesai</th>
                    </tr>
                   </thead>
                   <tbody>
                <?php
                $i = 1 ;
                foreach ($Karyawans as $key => $value){
                $nama_lengkap = $value['nama_depan'].' '.$value['nama_tengah'].' '.$value['nama_belakang'];
                $tanggal_selesai = (int)strtotime($value['tanggal_selesai']) > 0 ? date('d-F-Y', strtotime($value['tanggal_selesai'])) : '-'; ?>

                    <tr>
                        <td><?= $i++?></td>
                        <td><?= $value['nik']?></td>
                        <td width="300"><?= anchor('/profil?id='. $value['user_id'], $nama_lengkap, ['target' => '_blank'])?></td>
                        <td><?= $value['department']?></td>
                        <td><?= $value['job_title']?></td>
                        <td><?= $value['email']?></td>
                        <td><?= $value['nama_unit']?></td>
                        <td><?= $value['status']?></td>
                        <td><?= $tanggal_selesai?></td>
                    <?php } ?>
                   </tbody>
                 </table>
                </div>
                </div>
                <div class="table-container">

                </div>

            </div>
        </div>
    </div>
</div>

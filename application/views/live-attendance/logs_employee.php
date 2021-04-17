<h1 class="page-title"><?= $title ?></h1>
<div class="portlet light bordered">
    <div class="portlet-body">
    	<div class="table-toolbar">
            <div class="row">
                <div class="col-md-3">
                    <?= form_open('', ['method' => 'get']); ?>
                        <h3  style="margin-left: 14px;"><?= def($unit, 'nama_unit') ?></h3>
                        <div class="input-group" style="text-align:left">
                            <input class="form-control form-control-inline datepicker" name="date" size="16" type="text" value="<?= date('d/m/Y', strtotime($date)) ?>">
                            <span class="input-group-btn">
                                <button type="submit" class="btn green">Filter</button>   
                            </span>
                        </div>
                    <?= form_close(); ?>
                </div>
            </div>
        </div>

        <table class="table table-hover table-striped" width="100%" id="table-attendance2">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>Check In</th>
                    <th>Location In</th>
                    <th>Check Out</th>
                    <th>Location Out</th>
                    <th>Daily Hours</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $daily = 0;
                    foreach ($model as $key => $value):
                    $nama = $value->nama_depan .' '. $value->nama_tengah .' '. $value->nama_belakang;
                    $attendance = $value->attendanceByDate($date);

                    $getKet = Cek_Keterangan(def($attendance, 'keterangan'));
                ?>
                    <tr>
                        <td width="30" align="center"><?= ($key + 1) ?></td>
                        <td width="200"><?= $nama ?></td>
                        <td width="90" align="center">
                            <a href="#" class="btn-detail-in" data-id="<?=  def($attendance, 'id') ?>">
                                <?= def($attendance, 'check_in', '-') ?></a></td>
                        <td><?= def($attendance, 'location_in', '-') ?></td>
                        <td width="90" align="center">
                            <a href="#" class="btn-detail-out" data-id="<?=  def($attendance, 'id') ?>">
                                <?= def($attendance, 'check_out', '-') ?></a></td>
                        <td><?= def($attendance, 'location_out', '-') ?></td>
                        <td width="100" align="center"><?= def($attendance, 'daily_hours', 0) ?></td>
                        <td><span <?= $getKet['class'] ?> ><b><?= $getKet['keterangan'] ?? '-' ?></b></span></td>
                    </tr>
                    <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

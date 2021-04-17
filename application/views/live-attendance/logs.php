<link href="<?= base_url('web/assets/pages/css/profile.min.css') ?>" rel="stylesheet" type="text/css" />
<h1 class="page-title"><?= $title ?></h1>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <div class="row" style="margin-bottom: 30px; ">
                    <div class="col-sm-2">
                        <?php if (!empty($user_detail->profile_pic)): ?>    
                        <img src="<?= base_url($user_detail->profile_pic) ?>" id="img-profile" class="img-responsive img-circle" style="border: 5px solid; width: 200px;" />
                        <?php else: ?>
                        <img src="<?= base_url('/web/assets/pages/img/no_avatar.jpg') ?>" id="img-profile" class="img-responsive img-circle" style="border: 5px solid; width: 200px;" />
                        <?php endif ?> 
                    </div>
                    <div class="col-sm-2" style="margin-top: 40px;">
                        <h3><b>
                            <?php if (!empty($user_detail->nama_depan)): ?>
                                <?= ucwords("{$user_detail->nama_depan} {$user_detail->nama_tengah} {$user_detail->nama_belakang}") ?>
                            <?php else: ?>
                                <?= ucwords($this->session->userdata('identity')->username) ?>
                            <?php endif ?>
                        </b></h3>
                        <h4>
                            <?php if (!empty($user_detail->job_title)): ?>
                                <?= ucwords($user_detail->job_title) ?>
                            <?php endif ?>
                        </h4>
                    </div>   
                </div>
                <hr>                             
            	<div class="table-toolbar">
                    <div class="row">           
                        <div class="col-md-12">
                            <div class="row">
                                <?= form_open('', ''); ?>
                                    <label  style="margin-left: 14px;">Employee's Hours This Month :</label>
                                    <div class="form-group">  
                                        <div class="col-sm-2">
                                        <?= form_dropdown('bulan', [
                                            '' => '- Select Month -',
                                            '01' => 'Januari',
                                            '02' => 'Februari',
                                            '03' => 'Maret',
                                            '04' => 'April',
                                            '05' => 'Mei',
                                            '06' => 'Juni',
                                            '07' => 'Juli',
                                            '08' => 'Agustus',
                                            '09' => 'Sepetember',
                                            '10' => 'Oktober',
                                            '11' => 'November',
                                            '12' => 'Desember',
                                        ], $bulan ?? '', [
                                            'class' => 'form-control',
                                            'id' => 'id_bulan',
                                        ]); ?>   
                                        </div>
                                        <div class="col-sm-2" style="margin-top: 5px; ">
                                            <button type="submit" name="submit" class="btn sbold btn-secondary btn-sm border border-dark" > Filter</button>   
                                        </div>                                     
                                    </div>
                                <?= form_close(); ?>
                            </div>
                        </div>
                    </div>
                </div>
                

            	<div class="table-responsive"><table class="table table-bordered table-hover table-responsive" width="100%" id="table-attendance">
                    <thead>
                        <tr style="background-color:#031727;color:white;">
                            <th>No</th>
                            <th>Date</th>
                            <th>Check In</th>
                            <th>Location In</th>
                            <th>Check Out</th>
                            <th>Location Out</th>
                            <th>Daily Hours</th>
                            <th>Desc</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $daily = 0;
                            foreach ($calendars as $key => $model):
                                $attendee = $model->attendanceByUser($user_id)->get()->row();
                                $daily += def($attendee, 'daily_hours', 0);
                                $getKet = Cek_Keterangan(def($attendee, 'keterangan'));
                        ?>
                            <tr <?= Cek_Weekend(def($model, 'is_weekend'), def($model, 'is_holiday')) ?>>
                                <td width="30" align="center"><?= ($key + 1) ?></td>
                                <td width="200"><?= date('d F Y', strtotime($model->date)) ?></td>
                                <td width="90" align="center"><a href="#" class="btn-detail-in" data-id="<?= def($attendee, 'id') ?>"><?= def($attendee, 'check_in') ?></a></td>
                                <td><?= def($attendee, 'location_in') ?></td>
                                <td width="90" align="center"><a href="#" class="btn-detail-out" data-id="<?= def($attendee, 'id') ?>"><?= def($attendee, 'check_out') ?></a></td>
                                <td><?= def($attendee, 'location_out') ?></td>
                                <td width="100" align="center"><?= def($attendee, 'daily_hours') ?></td>
                                <td><span <?= $getKet['class'] ?> ><b><?= $getKet['keterangan'] ?></b></span></td>
                            </tr>
                            <?php endforeach; ?>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td align="center"><b>Total</b></td>
                            <td align="center"><b><?= $daily ?></b></td>
                            <td align="center"><b><?= $count ?></b></td>
                        </tr>
                    </tbody>
                </table></div>
            </div>
        </div>
    </div>
</div>



<h1 class="page-title"><?= $title ?></h1>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">

                <div class="table-responsive"><table class="table table-striped table-bordered table-hover" width="100%" id="table-employee">
                    <div style="margin-top:10px;">
                        <p><font style="font-family: arial;" size="5">Employee Update Head Count For Period <?= date('F d, Y', strtotime(date('Y-m-d')))?></font></p>
                    </div>
                    <thead>
                        <tr>
                            <th style="text-align: center;" width="5%">No</th>
                            <th width="50%">Unit</th>
                            <th width="15%"style="text-align: center;" >Permanent</th>
                            <th width="15%"style="text-align: center;" >Contract</th>
                            <th width="15%"style="text-align: center;" > Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $total_permanent = 0;
                        $total_kontrak = 0;
                        $total_all = 0;
                        foreach ($data_sum as $key => $value): 
                            $total_permanent += $value['Permanent'];
                            $total_kontrak += $value['Kontrak'];
                            $total_all += $value['Total'];
                            ?>
                            <tr>
                                <td style="text-align: center;"><?= ($key + 1) ?></td>
                                <td><?= $value['Unit'] ?></td>
                                <td style="text-align: center;"><?= $value['Permanent'] ?></td>
                                <td style="text-align: center;"><?= $value['Kontrak'] ?></td>
                                <td style="text-align: center;"><?= $value['Total'] ?></td>
                            </tr>
                        <?php endforeach; ?>             
                    </tbody>
                    <tr>              
                        <td colspan="2" style="text-align: center;"><strong>TOTAL</strong></td>
                        <td style="text-align: center;"><strong><?= $total_permanent ?></strong></td>
                        <td style="text-align: center;"><strong><?= $total_kontrak ?></strong></td>
                        <td style="text-align: center;"><strong><?= $total_all ?></strong></td>
                    </tr>
                </table>
            </div>




        </div>
    </div>
</div>
</div>

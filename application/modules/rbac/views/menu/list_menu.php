<h1 class="page-title"><?= $title ?></h1>

<div class="row">
    <div class="col-lg-4">
        <?php echo $this->layout->renderPartial('_side_menu') ?>
    </div>

    <div class="col-lg-8">
        <div class="portlet light bordered">
            <div class="portlet-title">
                Group name : <strong><?php echo $menuType ?></strong>
            </div>
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-8">
                        <strong><?= 'Menu Structure' ?></strong>
                        <p>Seret tiap daftar ke posisi yg anda sukai. Klik simbol 'tambah' dibagian samping untuk melihat pengaturan tambahan.</p>
                    </div>
                    <div class="col-md-4">
                        <?= $this->html->submitButton('Save Menu <span class=\'fa fa-save\'></span>', ['name'=> 'save', 'class' => 'btn btn-primary pull-right btn-save-menu']);?>
                    </div>
                </div>

                <div class="note note-success">
                    <h4 class="block">Tips !</h4>
                    <small>Icon harus berisi berdasarkan Font Awesome<br/>Ex. (fa fa-)dashboard <i class="fa fa-dashboard"></i><br/> Klik link untuk referensi <a href="http://fontawesome.io/">http://fontawesome.io/</a></small>
                </div>

                <?= form_open('', ['id' => 'formMenu']); ?>
                    <div class="dd">
                        <ol class="dd-list">
                        <?php
                        if (!empty($menus)) {
                            $i = 0;
                            foreach ($menus as $key => $menu) {
                                if ($menu->menu_parent == 0) {
                                    echo $this->layout->renderPartial('_view', [
                                        'menu' => $menu,
                                        'listGroup' => $listGroup,
                                        'childMenus' => $menus,
                                        'menuType' => $menuType,
                                        'i' => $i,
                                    ]);
                                }
                                $i++;
                            }
                        }
                        ?>
                        </ol>
                    </div>
                <?= form_close(); ?>
            </div>
        </div>
    </div>
</div>

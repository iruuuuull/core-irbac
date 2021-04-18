<li class="dd-item" data-id="<?php echo $menu->id ?>">
    <button type="button" class="pull-right btn btn-success btn-xs pull-right" aria-hidden="true" data-toggle="collapse" href="#item-menu-<?php echo $menu->id?>" aria-expanded="true" aria-controls="item-menu-<?php echo $menu->id?>"></button>

    <div class="dd-handle">
        <div class="panel panel-success">
            <div class="handle-head panel-heading"><?php echo $menu->label?> <span class="pull-right"><i>(<?php echo $menu->menu_url?>)</i></span> </div>
            <div class="panel-collapse collapse dd-nodrag" id="item-menu-<?php echo $menu->id?>" role="tabpanel" aria-labelledby="item-menu-<?php echo $menu->id?>">
            <div class="panel panel-default">
            <div class="panel-body">
                <div class="form-group">
                    <label><i><?= 'Menu Label' ?></i></label>
                    <input type="text" class="form-control input-sm" name="Menu[item][<?php echo $i ?>][label]" value="<?php echo $menu->label?>" placeholder="<?= 'Menu Label' ?>">
                </div>
                <div class="form-group">
                    <label><i><?= 'Description' ?></i></label>
                    <input type="text" class="form-control input-sm" name="Menu[item][<?php echo $i ?>][description]" value="<?php echo $menu->description?>" placeholder="<?= 'Menu Description' ?>">
                </div>
                <div class="form-group">
                    <label><i><?= 'URL' ?></i></label>
                    <input type="text" class="form-control input-sm" name="Menu[item][<?php echo $i ?>][menu_url]" value="<?php echo $menu->menu_url?>" placeholder="<?= 'URL' ?>">
                </div>
                <div class="form-group">
                    <label><i><?= 'Icon Class' ?></i></label>
                    <input type="text" class="form-control input-sm" name="Menu[item][<?php echo $i ?>][class]" value="<?php echo $menu->class?>" placeholder="<?= 'Icon Class' ?>">
                </div>
                <div class="form-group">
                    <div style="position:relative; margin-left: 25px;">
                        <label><i><?= 'Group' ?></i></label>
                        <?php
                        if (!empty($listGroup)) {
                            echo $this->html->beginTag('div', ['class' => 'group']);
                            $checked = false;
                            foreach ($listGroup as $key => $value) {
                                if (!empty($menu->menuGroup)) {
                                    foreach ($menu->menuGroup as $k => $v) {
                                        if ($value->id == $v->group_id) {
                                            $checked = true;
                                            break;
                                        } else {
                                            $checked = false;
                                        }
                                    }
                                }
                                echo $this->html->beginTag('div', ['class' => 'checkbox']);
                                echo $this->html->beginTag('label');
                                echo $this->html->checkbox('Menu[item][' . $i . '][group][]', $checked, ['value' => $value->id]);
                                echo $value->label;
                                echo $this->html->endTag('label');
                                echo $this->html->endTag('div');
                            }
                            echo $this->html->endTag('div');
                        }
                        ?>
                    </div>
                </div>
                <div class="col-md-12">
                    <a href="javascript:void(0)" aria-hidden="true" data-toggle="collapse" href="#item-menu-<?php echo $menu->id?>" aria-expanded="true" aria-controls="item-menu-<?php echo $menu->id?>" class="text-danger remove-menu" data-id="<?php echo $menu->id ?>" menu-type="<?php echo $menuType ?>">Remove</a>
                    |
                    <a aria-hidden="true" data-toggle="collapse" href="#item-menu-<?php echo $menu->id ?>" aria-expanded="true" aria-controls="item-menu-<?php echo $menu->id ?>" class="text-muted">Cancel</a>
                </div>
            </div>
            </div>
            </div>
        </div>
        <input type="hidden" name="Menu[item][<?php echo $i ?>][menu_parent]" class="hasparent" value="<?php echo $menu->menu_parent?>">
        <input type="hidden" name="Menu[item][<?php echo $i ?>][dummy_id]" class="dummy-id" value="<?php echo $menu->menu_parent?>">
        <input type="hidden" name="Menu[item][<?php echo $i ?>][menu_custom]" value="<?php echo $menu->menu_custom?>">
        <input type="hidden" name="Menu[item][<?php echo $i ?>][menu_id]" value="<?php echo $menu->id?>">
        <input type="hidden" name="Menu[item][<?php echo $i ?>][menu_order]" class="menu-order" value="<?php echo $menu->menu_order?>">
        <input type="hidden" name="Menu[item][<?php echo $i ?>][level]" class="level" value="<?php echo $menu->level?>">
    </div>
    <?php
        echo "<ol class='dd-list'>";
        $i = 0;
        foreach ($childMenus as $key => $childMenu) {
            if ($childMenu->menu_parent == $menu->id) {
                echo $this->layout->renderPartial('_view', [
                    'menu' => $childMenu,
                    'listGroup' => $listGroup,
                    'menuType' => $menuType,
                    'childMenus' => $childMenus,
                    'i' => $i
                ]);
            }
            $i++;
        }
        echo "</ol>";
    ?>
</li>
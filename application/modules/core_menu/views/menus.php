<h4>Menu administration</h4>

<div class="panel">
    <h5>Select menu</h5>
    <ul class="breadcrumbs">
        <?php foreach ($menus as $breadcrumb_menu) :?>
            <li>
                <a title="<?php echo $breadcrumb_menu->description ;?>"
                   href="<?php echo base_url() . $this->core_menu_library->menus_uri  . $breadcrumb_menu->id ;?>"><?php echo $breadcrumb_menu->menu_name ;?></a>
            </li>
        <?php endforeach ;?>
    </ul>
</div>

<div class="panel">
<h5>Menu</h5>
<table width="100%">
    <thead>
        <tr>
            <th width="5%">Id</th>
            <th width="10%">Menu name</th>
            <th width="15%">Description</th>
            <th width="10%"><a href="<?php echo base_url() . $this->core_menu_library->menu_add_uri ;?>">Add menu +</a></th>
        </tr>
    </thead>
    <tr>
        <td><?php echo $menu->id ;?></td>
        <td><?php echo $menu->menu_name ;?></td>
        <td><?php echo $menu->description ;?></td>
        <td>

            <a class="label round small secondary" href="<?php echo base_url() . $this->core_menu_library->menu_edit_uri . $menu->id ;?>">Edit</a>&nbsp
            <a class="label alert small round" href="<?php echo base_url() . $this->core_menu_library->menu_delete_uri . $menu->id ;?>"
               onClick="return confirm(<?php echo '\'' . lang('menu_confirm_delete') . '\'' ;?>)">Delete</a>
        </td>
    </tr>
</table>
</div>

<h5>Links</h5>
<table width="100%">
    <thead>
        <tr>
            <th>Weight</th>
            <th>Menu</th>
            <th>External</th>
            <th>Title</th>
            <th>Text</th>
            <th>Link</th>
            <th>Permissions</th>
            <th><a href="<?php echo base_url() . $this->core_menu_library->menu_link_add_uri . $menu->id ;?>">Add+</a></th>
        </tr>
    </thead>
    <tbody id="table1">
        <?php if (!empty($links)) :?>
            <?php foreach ($links as $link) :?>
                <tr id="tr-<?php echo $link['id'] ;?>">

                    <td id="td-<?php echo $link['id'] ;?>">

                        <?php echo form_open($this->core_menu_library->menus_uri . $menu->id) ;?>

                        <?php $id = $link['id'] ;?>

                        <input class="csrf_test_name" type="hidden" name="csrf_test_name"
                               value="<?php echo (isset($csrf_test_name)) ? $csrf_test_name : set_value('csrf_test_name') ;?>" />

                        <input type="hidden" name="id" value="<?php echo (isset($link['id'])) ? $link['id'] : set_value('id') ;?>" />

                        <div style="display:none;" id="weight-dragger-<?php echo (isset($link['id'])) ? $link['id'] : set_value('id') ;?>"
                             class="weight-dragger"><?php echo (isset($link['weight'])) ? $link['weight'] : set_value('weight') ;?></div>

                        <noscript>
                            <select name="weight" class="weight-selector">
                                <?php $weight = $link['weight'] ;?>
                                <?php foreach ($max_link_count as $max) :?>
                                    <?php if (!set_value('weight') && $value == $weight) :?>
                                        <?php $selected = 'selected' ;?>
                                    <?php elseif (set_value('weight') == $max && set_value('id') == $id) :?>
                                        <?php $selected = 'selected' ;?>
                                    <?php elseif ((set_value('weight') != $max && set_value('id') != $id) && $max == $weight) :?>
                                        <?php $selected = 'selected' ;?>
                                    <?php elseif ((set_value('weight') == $max && set_value('id') != $id) && $max == $weight) :?>
                                        <?php $selected = 'selected' ;?>
                                    <?php else :?>
                                        <?php $selected = NULL ;?>
                                    <?php endif ;?>
                                    <option value="<?php echo $max ;?>" <?php echo $selected ?>><?php echo $max ;?></option>
                                <?php endforeach ;?>
                            </select>
                        </noscript>
                    </td>

                    <td>
                        <select name="parent_menu_id">
                            <?php $parent_menu_id = $link['parent_menu_id'] ;?>
                            <?php foreach ($menus as $menu) :?>
                                <?php if (!set_value('parent_menu_id') && $menu->id == $parent_menu_id) :?>
                                    <?php $selected = 'selected' ;?>
                                <?php elseif (set_value('parent_menu_id') == $menu->id && set_value('id') == $id) :?>
                                    <?php $selected = 'selected' ;?>
                                <?php elseif (((set_value('parent_menu_id') != $menu->id) && set_value('id') != $id) && $menu->id == $parent_menu_id) :?>
                                    <?php $selected = 'selected' ;?>
                                <?php elseif (((set_value('parent_menu_id') == $menu->id) && set_value('id') != $id) && $menu->id == $parent_menu_id) :?>
                                    <?php $selected = 'selected' ;?>
                                <?php else :?>
                                    <?php $selected = NULL ;?>
                                <?php endif ;?>
                                <option value="<?php echo $menu->id ;?>" <?php echo $selected ;?>><?php echo $menu->menu_name ;?></option>
                            <?php endforeach ;?>
                        </select>
                        <label for="parent_menu_id">Parent menu</label>

                        <select name="child_menu_id">
                            <?php $child_menu_id = $link['child_menu_id'] ;?>
                            <option value=0 <?php echo set_select('child_menu_id', NULL) ;?>>None</option>
                            <?php foreach ($menus as $menu) :?>
                                <?php if ((!set_value('child_menu_id') && $menu->id == $child_menu_id)) :?>
                                    <?php $selected = 'selected' ;?>
                                <?php elseif ((set_value('child_menu_id') == $menu->id) && set_value('id') == $id) :?>
                                    <?php $selected = 'selected' ;?>
                                <?php elseif (((set_value('child_menu_id') != $menu->id) && set_value('id') != $id) && $menu->id == $child_menu_id) :?>
                                    <?php $selected = 'selected' ;?>
                                <?php elseif (((set_value('child_menu_id') == $menu->id) && set_value('id') != $id) && $menu->id == $child_menu_id) :?>
                                    <?php $selected = 'selected' ;?>
                                <?php else :?>
                                    <?php $selected = NULL ;?>
                                <?php endif ;?>
                                <option value="<?php echo $menu->id ;?>" <?php echo $selected ;?>><?php echo $menu->menu_name ;?></option>
                            <?php endforeach ;?>
                        </select>
                        <label for="child_menu_id">Child menu</label>
                    </td>

                    <td>
                        <?php if((isset($link['external']) && $link['external'] == 1) || set_value('external') == 1 && set_value('id') == $link['id']) :?>
                            <?php $checked = 'checked' ;?>
                            <?php else :?>
                            <?php $checked = NULL ;?>
                        <?php endif ;?>
                        <input type="checkbox" name="external" value="1" <?php echo $checked ;?> />
                    </td>

                    <td>
                        <textarea name="title" class="<?php echo (form_error('title') && set_value('id') == $link['id']) ? form_error_class('title') : '' ;?>"><?php echo (isset($link['title'])) ? $link['title'] : set_value('title') ;?></textarea>
                    </td>

                    <td>
                        <input type="text" name="text" class="<?php echo (form_error('text') && set_value('id') == $link['id']) ? form_error_class('text') : '' ;?>"
                            value="<?php echo (isset($link['text'])) ? $link['text'] : set_value('text') ;?>" />
                    </td>

                    <td>
                        <input type="text" name="link" class="<?php echo (form_error('link') && set_value('id') == $link['id']) ? form_error_class('link') : '' ;?>"
                            value="<?php echo (isset($link['link'])) ? $link['link'] : set_value('link') ;?>" />
                    </td>

                    <td>
                        <select name="permissions[]" multiple="multiple">
                            <?php if (isset($all_permissions)) :?>
                                <?php foreach ($all_permissions as $permission) :?>
                                    <?php
                                        if (isset($link['permissions']) && strstr($link['permissions'], $permission['permission']))
                                        {
                                            $selected = 'selected="selected"';
                                        }
                                        elseif (set_select('permissions', $permission['permission']) && set_value('id') == $id)
                                        {
                                            $selected = set_select('permissions', $permission['permission']);
                                        }
                                        else
                                        {
                                            $selected = '';
                                        }
                                    ?>
                                    <option value="<?php echo $permission['permission'] ;?>" <?php echo $selected ;?>>
                                        <?php echo $permission['permission'] ;?>
                                    </option>
                                <?php endforeach ;?>
                            <?php endif ;?>
                        </select>
                    </td>
                    <td>
                        <input type="submit" value="Save" name="submit" />
                        <br/><br/>
                        <a class="label alert small round" href="<?php echo base_url() . $this->core_menu_library->menu_link_delete_uri . $link['id'] ;?>"
                                onClick="return confirm(<?php echo '\'' . lang('menu_link_confirm_delete') . '\'' ;?>)">Delete</a>
                    </td>
                    <?php echo form_close() ;?>
                </tr>
            <?php endforeach ;?>
        <?php endif ;?>
    </tbody>
</table>
<input type="hidden" id="menu_link_edit_weight_url" name="menu_link_edit_weight_url" value="<?php echo $menu_link_edit_weight_url ;?>" />

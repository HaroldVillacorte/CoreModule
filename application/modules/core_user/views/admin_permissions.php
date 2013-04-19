<div id="ajax-content">

    <h4>
        permissions <img id="loading-img" src="<?php echo $asset ;?>images/load.gif" style="display:none;" />
    </h4>

    <table width="100%">
        <thead>
            <tr>
                <th>Id</th>
                <th>permission</th>
                <th>Description</th>
                <th>Protected</th>
                <th><a href="<?php echo base_url($this->core_user_library->user_admin_permission_add_uri) ;?>">Add permission +</a></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($permissions as $permission) :?>
                <tr>
                    <td><?php echo $permission->id ;?></td>
                    <td><?php echo $permission->permission ;?></td>
                    <td><?php echo $permission->description ;?></td>
                    <td><?php echo $permission->protected ;?></td>
                    <td>
                        <a href="<?php echo base_url($this->core_user_library->user_admin_permission_edit_uri . $permission->id) ;?>"
                           class="label secondary round">Edit</a>
                        <a href="<?php echo base_url($this->core_user_library->user_admin_permission_delete_uri. $permission->id) ;?>"
                           class="label alert round" style="margin-left:10px;"
                           onClick="return confirm(<?php echo '\'' . lang('confirm_admin_permission_delete') . '\'' ;?>)">Del</a>
                    </td>
                </tr>
            <?php endforeach ;?>
        </tbody>
    </table>
    <?php echo $pagination ;?>

</div>

<div id="ajax-content">

    <h4>
        Roles <img id="loading-img" src="<?php echo $asset ;?>images/load.gif" style="display:none;" />
    </h4>

    <table width="100%">
        <thead>
            <tr>
                <th>Id</th>
                <th>Role</th>
                <th>Description</th>
                <th>Protected</th>
                <th><a href="<?php echo base_url() . $this->core_user_library->user_admin_role_add_uri ;?>">Add role +</a></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($roles as $role) :?>
                <tr>
                    <td><?php echo $role->id ;?></td>
                    <td><?php echo $role->role ;?></td>
                    <td><?php echo $role->description ;?></td>
                    <td><?php echo $role->protected ;?></td>
                    <td>
                        <a href="<?php echo base_url(). $this->core_user_library->user_admin_role_edit_uri . $role->id ;?>"
                           class="label secondary round">Edit</a>
                        <a href="<?php echo base_url() . $this->core_user_library->user_admin_role_delete_uri. $role->id ;?>"
                           class="label alert round" style="margin-left:10px;"
                           onClick="return confirm(<?php echo '\'' . lang('confirm_admin_role_delete') . '\'' ;?>)">Del</a>
                    </td>
                </tr>
            <?php endforeach ;?>
        </tbody>
    </table>
    <?php echo $pagination ;?>

</div>

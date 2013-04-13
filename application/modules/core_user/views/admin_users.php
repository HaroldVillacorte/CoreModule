<div id="ajax-content">
    <h4>Users</h4>
    <p>Displaying <?php echo $first ;?> to <?php echo $last ;?> of <?php echo $count ;?> records.  <img id="loading-img"
        src="<?php echo $asset ;?>images/load.gif" style="floeat:left;display:none;" />
    </p>

    <table width="100%">
        <thead>
            <tr>
                <th>Id</th>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Member since</th>
                <th>Protected</th>
                <th><a href="<?php echo base_url() . $this->core_user_library->user_admin_user_add_uri ;?>">Add user +</a></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user) :?>
            <tr>
                <td><?php echo $user['id'] ;?></td>
                <td><?php echo $user['username'] ;?></td>
                <td><?php echo $user['email'] ;?></td>
                <td><?php echo $user['role'] ;?></td>
                <td><?php echo $user['created'] ;?></td>
                <td><?php echo $user['protected'] ;?></td>
                <td>
                    <a href="<?php echo base_url() . $this->core_user_library->user_admin_user_edit_uri . $user['id'] ;?>"
                       class="label secondary round">Edit</a>
                    <a href="<?php echo base_url() . $this->core_user_library->user_admin_user_delete_uri . $user['id'] ;?>"
                       class="label alert round" style="margin-left:10px;"
                       onClick="return confirm(<?php echo '\'' . lang('confirm_admin_user_delete') . '\'';?>)">Del</a>
                </td>
            </tr>
            <?php endforeach ;?>
        </tbody>
    </table>


    <p><?php echo $pagination_links ;?></p>
</div>
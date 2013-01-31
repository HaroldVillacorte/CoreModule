<h4>Roles</h4>

<?php foreach ($roles as $role):?>
  <p class="panel">
    Id: <?php echo $role->id ;?><br/>
    Role: <?php echo $role->role ;?><br/>
    <a class="button secondary" href="<?php echo base_url() . 'user_admin/edit_role/'
            . $role->id ;?>">Edit</a>
    <a class="button secondary" href="<?php echo base_url() . 'user_admin/delete_role/'
            . $role->id ;?>" onClick="return confirm('Are you sure?')">Delete</a>
  </p>
<?php endforeach; ?>
  <p><a href="<?php echo base_url() . 'user_admin/edit_role/';?>">Add role</a></p>


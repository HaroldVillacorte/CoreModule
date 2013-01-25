<h4>Roles</h4>

<?php foreach ($roles as $role):?>
  <p class="panel">
    Id: <?php echo $role->getId();?><br/>
    Role: <?php echo $role->getRole();?><br/>
    <button><a>Edit</a></button>
  </p>
<?php endforeach; ?>
  <p><a href="<?php echo base_url() . 'user_admin/edit_role/';?>">Add role</a></p>


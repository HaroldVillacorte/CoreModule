<h4>Add category</h4>

<?php echo form_open(current_url()) ;?>

<fieldset>
    <legend>Category level</legend>

    <?php foreach ($levels as $level) :?>

    <label class="<?php echo form_error_class('level')?>" for="level"><?php echo $level->role ;?></label>
    <input type="radio" name="level" value="<?php echo $level->id ;?>" <?php echo set_radio('level', $level->id) ;?>/>

    <?php endforeach ;?>

</fieldset>

<fieldset>
    <legend>Category name</legend>

    <label for="level">Name:</label>
    <input type="text" name="name" class="<?php echo form_error_class('name')?>"
           value="<?php echo set_value('name') ;?>" />

</fieldset>

<input type="submit" value="Save" name="submit" />

<?php echo form_close() ;?>

<h4>System settings</h4>

<?php echo form_open(current_url()) ;?>

<label for="core_module_time_format" class="<?php echo form_error_class('core_module_time_format') ;?>">Time format:</label>
<select name="core_module_time_format">
    <?php foreach ($time_formats as $format) :?>
        <?php $selected = (variable_get('core_module_time_format') && variable_get('core_module_time_format') == $format) ?
        'selected="selected"' : set_select('core_module_time_format', $format);?>
        <option <?php echo $selected ;?>>
            <?php echo $format ;?>
        </option>
    <?php endforeach ;?>
</select>

<fieldset>
    <legend>Site variables</legend>

    <label for="site_name" >Site name:</label>
    <input class="<?php echo form_error_class('site_name') ;?>"
           type="text" name="site_name"
           value="<?php echo (variable_get('site_name')) ?
           variable_get('site_name') : set_value('site_name') ;?>" />

    <label for="site_description" >Site description:</label>
    <input class="<?php echo form_error_class('site_description') ;?>"
           type="text" name="site_description"
           value="<?php echo (variable_get('site_description')) ?
           variable_get('site_description') : set_value('site_description') ;?>" />

</fieldset>

<fieldset>
    <legend>Content</legend>

    <label for="core_module_allowed_tags" >Allowed html tags (currently not in use by core):</label>
    <input class="<?php echo form_error_class('core_module_allowed_tags') ;?>"
           type="text" name="core_module_allowed_tags"
           value="<?php echo (variable_get('core_module_allowed_tags')) ?
           variable_get('core_module_allowed_tags') : set_value('core_module_allowed_tags') ;?>" />

    <label for="core_module_pagination_per_page" >Number of records per paginated page:</label>
    <input class="<?php echo form_error_class('core_module_pagination_per_page') ;?>"
           type="text" name="core_module_pagination_per_page"
           value="<?php echo (variable_get('core_module_pagination_per_page')) ?
           variable_get('core_module_pagination_per_page') : set_value('core_module_pagination_per_page') ;?>" />

    <label for="core_module_design_mode" class="<?php echo form_error_class('core_module_design_mode') ;?>">Design mode:</label>
    <?php $checked = (variable_get('core_module_design_mode') && variable_get('core_module_design_mode') == 1) ?
           'checked="checked"' : set_checkbox('core_module_design_mode', '1') ;?>
    <input type="checkbox" name="core_module_design_mode" value=1 <?php echo $checked ;?> />

</fieldset>

<input type="submit" value="Save" name="submit" />

<?php echo form_close() ;?>

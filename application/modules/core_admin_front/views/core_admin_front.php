<h4>Regex</h4>

<?php echo form_open(current_url()) ;?>

<fieldset>
<label for="regex">Regex:</label>
<input type="text" name="regex" value="<?php echo (isset($regex)) ? $regex : '' ;?>" />

<label for="string">String:</label>
<textarea name="string">
<?php echo (isset($string)) ? $string : '' ;?>
</textarea>

<input type="submit" value="Run" name="submit" />
</fieldset>

<?php echo form_close() ;?>

<?php if (isset($output_array)) :?>
    <p>
        <label for="output">Output:</label><br/>
        <?php foreach ($output_array as $output) :?>
            <?php echo $output ;?> <br/>
        <?php endforeach ;?>
    </p>
<?php endif ;?>
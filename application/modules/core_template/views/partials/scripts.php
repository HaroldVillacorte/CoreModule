<script src="http://code.jquery.com/jquery-1.8.3.min.js"></script>
<!-- Groecery CRUD -->
<?php if (isset($output->js_files)) :?>
    <?php foreach ($output->js_files as $file) :?>
        <?php if (strstr($file, 'jquery-1.8.2.min.js') == NULL) :?>
            <script src="<?php echo $file ;?>"></script>
        <?php endif ;?>
    <?php endforeach ;?>
<?php endif ;?>
<!-- Load js files from Asset Loader module -->
<?php echo Modules::run('core_asset_loader/javascript') ;?>
<!-- Initialize JS Plugins -->
<script src="<?php echo $js_url ;?>jquery.foundation.navigation.js"></script>
<script src="<?php echo $js_url ;?>app.js"></script>

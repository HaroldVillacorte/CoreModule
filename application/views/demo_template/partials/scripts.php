<script src="http://code.jquery.com/jquery-1.8.3.min.js"></script>

<!-- Load js files from controllers -->
<?php if (isset($scripts)):?>
    <?php foreach ($scripts as $script) :?>
        <script src="<?php echo $asset ;?>javascripts/<?php echo $script ;?>"></script>
    <?php endforeach ;?>
<?php endif ;?>

<!-- Initialize JS Plugins -->
<script src="<?php echo $asset ;?>javascripts/jquery.foundation.navigation.js"></script>
<script src="<?php echo $asset ;?>javascripts/app.js"></script>

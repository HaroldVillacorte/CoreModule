<script src="http://code.jquery.com/jquery-1.8.3.min.js"></script>
<!-- Initialize JS Plugins -->
<script src="<?php echo $asset ;?>javascripts/jquery.foundation.topbar.js"></script>
<script src="<?php echo $asset ;?>javascripts/jquery.foundation.forms.js"></script>
<script src="<?php echo $asset ;?>javascripts/app.js"></script>
<script src="<?php echo $asset ;?>jquery_ui/js/jquery.ui.core.min.js"></script>
<script src="<?php echo $asset ;?>jquery_ui/js/jquery.ui.widget.min.js"></script>
<script src="<?php echo $asset ;?>jquery_ui/js/jquery.ui.mouse.min.js"></script>
<script src="<?php echo $asset ;?>jquery_ui/js/jquery.ui.button.min.js"></script>
<script src="<?php echo $asset ;?>jquery_ui/js/jquery.ui.sortable.min.js"></script>
<script src="<?php echo $asset ;?>javascripts/core_module.js"></script>

<?php if (isset($scripts)):?>
    <?php foreach ($scripts as $script) :?>
        <script src="<?php echo $asset ;?>javascripts/<?php echo $script ;?>"></script>
    <?php endforeach ;?>
<?php endif; ?>


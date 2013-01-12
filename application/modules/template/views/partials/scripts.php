<!-- Included JS Files (Uncompressed) -->
<!--
<script src="javascripts/jquery.js"></script>
<script src="javascripts/jquery.foundation.mediaQueryToggle.js"></script>
<script src="javascripts/jquery.foundation.forms.js"></script>
<script src="javascripts/jquery.foundation.reveal.js"></script>
<script src="javascripts/jquery.foundation.orbit.js"></script>
<script src="javascripts/jquery.foundation.navigation.js"></script>
<script src="javascripts/jquery.foundation.buttons.js"></script>
<script src="javascripts/jquery.foundation.tabs.js"></script>
<script src="javascripts/jquery.foundation.tooltips.js"></script>
<script src="javascripts/jquery.foundation.accordion.js"></script>
<script src="javascripts/jquery.placeholder.js"></script>
<script src="javascripts/jquery.foundation.alerts.js"></script>
<script src="javascripts/jquery.foundation.topbar.js"></script>
<script src="javascripts/jquery.foundation.joyride.js"></script>
<script src="javascripts/jquery.foundation.clearing.js"></script>
<script src="javascripts/jquery.foundation.magellan.js"></script>
-->
<!-- Included JS Files (Compressed) -->
<script src="http://code.jquery.com/jquery-1.8.3.min.js"></script>
<!-- Groecery CRUD -->
<?php if (isset ($output->js_files)) : ?>
  <?php foreach ($output->js_files as $file) : ?>
    <?php if (strstr ($file, 'jquery-1.8.2.min.js') == NULL) : ?>
    <script src="<?php echo $file; ?>"></script>
    <?php endif; ?>
  <?php endforeach; ?>
<?php endif; ?>
<!--<script src="<?php //echo $js_url; ?>foundation.min.js"></script>-->
<script src="<?php echo $js_url; ?>jquery.foundation.orbit.js"></script>
<!-- Initialize JS Plugins -->
<script src="<?php echo $js_url; ?>app.js"></script>
<script type="text/javascript">
  $(window).load(function() {
    $('#slider').orbit();
  });
</script>

<?php $data['data_two'] = $data_two; ?>
<!DOCTYPE html>
<?php $this->load->view ('partials/head', $data); ?>
<body>
  <?php $this->load->view ('header_nav', $data); ?>
  <?php $this->load->view ('highlighted', $data); ?>
  <!-- Content -->
  <div class="row">
    <?php echo Modules::run ('core_messages/load'); ?>
    <?php $this->load->view ($module . '/' . $view_file); ?>
  </div>
  <?php $this->load->view ('call_to_action_panel', $data); ?>
  <?php $this->load->view ('footer', $data); ?>
  <?php $this->load->view ('scripts', $data); ?>
</body>
</html>
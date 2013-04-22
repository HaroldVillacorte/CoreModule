<?php include 'partials/head.php';?>
<?php include 'partials/top_nav.php';?>

<div class="row"> <!-- row container-->
  <div class="twelve columns"> <!-- twelve columns container-->

    <?php
    isset ($_GET['page']) ? load($_GET['page']) : load('introduction.php') ;
    function load($page) {
      file_exists('pages/' . $page) ? include 'pages/' . $page : include 'pages/404.php' ;
    }
    ?>

  <div> <!-- twelve columns container-->
</div> <!-- row container -->

<?php include 'partials/footer.php';?>
<?php include 'partials/scripts.php';?>

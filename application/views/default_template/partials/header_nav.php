<!-- Header and Nav -->
<div class="row">
    <div class="three columns">
        <h1>
            <img src="http://placehold.it/400x100&text=<?php echo $site_name ;?>" />
        </h1>
    </div>
    <div class="nine columns">
            <?php echo Modules::run('core_menu', 2, 'demo_menu') ;?>
    </div>
</div>
<!-- End Header and Nav -->

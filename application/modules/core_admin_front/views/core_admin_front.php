<section class="panel">

        <h5><u>Security checks</u></h5>

        <p><strong>Global xss filtering:</strong> <?php echo $config['global_xss_filtering'] ;?></p>
        <p><strong>CSRF protection:</strong> <?php echo $config['csrf_protection'] ;?></p>
        <p><strong>Session encrypt cookie:</strong> <?php echo $config['sess_encrypt_cookie'] ;?></p>

</section>

<h4>Admin</h4>

<?php if (!empty($categories)) :?>

    <?php foreach ($categories as $key => $category) :?>

        <?php $br = ($key == count($categories) - 1) ? '<br/>' : '' ;?>

        <?php echo $br ;?>

        <div class="six columns">

            <h5><?php echo $category->name ;?></h5>

            <p>
                <?php foreach ($category->pages as $page) :?>

                    <?php if (stristr($page->title, 'edit') || stristr($page->title, 'delete')) :?>
                        <?php echo $page->title ;?></br>
                    <?php else :?>
                        <a href="<?php echo base_url($page->slug) ;?>"><?php echo $page->title ;?></a></br>
                    <?php endif ;?>

                <?php endforeach ;?>
            </p>

        </div>

    <?php endforeach ;?>

<?php endif ;?>

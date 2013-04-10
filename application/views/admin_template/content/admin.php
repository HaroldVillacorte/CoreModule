<h4>Admin index</h4>

<?php foreach ($links as $link) :?>
    <div class="panel four columns">
        <h6><?php echo $link->text ;?></h6>
        <p><?php echo $link->title ;?></p>
        <a class="button secondary" href="<?php echo base_url() . $link->link ;?>" title="<?php echo $link->title ;?>">Go</a>
    </div>
<?php endforeach; ?>

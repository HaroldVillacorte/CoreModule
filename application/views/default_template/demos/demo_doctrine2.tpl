<div class="twelve columns">
    <h5>This is Doctrine 2</h5>
    <div id="ajax-content">
        <p>Render time: {$elapsed_time} | Displaying {$first} to {$last} of {$count} records.
            <img id="loading-img" src="{$img_url}load.gif" style="display:none;" />
        </p>
        {$output}
        <p>{$pagination_links}</p>
    </div>
</div>
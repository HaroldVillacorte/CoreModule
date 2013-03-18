<article>
    {if="isset($page->title)"}
        <h4>{$page->title}</h4>
    {else}
        <h4>Welcome</h4>
    {/if}

    {if="isset($page->body)"}
        <section>{$page->body}</section>
    {else}
        <section>Page content has not been set.</section>
    {/if}
</article>
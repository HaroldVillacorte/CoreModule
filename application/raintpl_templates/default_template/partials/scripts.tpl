<script src="http://code.jquery.com/jquery-1.8.3.min.js"></script>

<!-- Load js files from Asset Loader module -->
{if="$scripts"}
    {loop="$scripts"}
        <script src="{$js_url}{$value}"></script>
    {/loop}
{/if}

<!-- Initialize JS Plugins -->
<script src="{$js_url}jquery.foundation.navigation.js"></script>
<script src="{$js_url}app.js"></script>

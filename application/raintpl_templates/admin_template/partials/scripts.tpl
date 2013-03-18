<script src="http://code.jquery.com/jquery-1.8.3.min.js"></script>

<!-- Load js files from controllers -->
{if="$scripts"}
    {loop="$scripts"}
        <script src="javascripts/{$value}"></script>
    {/loop}
{/if}

<!-- Initialize JS Plugins -->
<script src="javascripts/jquery.foundation.navigation.js"></script>
<script src="javascripts/app.js"></script>

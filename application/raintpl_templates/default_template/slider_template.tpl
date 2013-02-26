<!DOCTYPE html>
{include="partials/head"}
<body>
    {include="partials/header_nav"}
    {include="partials/first_band_slider"}

    <!-- Content -->
    <div class="row">
        {function="Modules::run('_core_messages/load')"}
        {include="demos/$content_file"}
    </div>
    {include="partials/call_to_action_panel"}
    {include="partials/footer"}
    {include="partials/scripts"}
    <script type="text/javascript">
        $(window).load(function() {
            $('#slider').orbit();
        });
    </script>
</body>
</html>

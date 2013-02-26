<!DOCTYPE html>
{include="partials/head"}
<body>
    {include="partials/header_nav"}
    {include="partials/highlighted"}

    <!-- Content -->
    <div class="row">
        {function="Modules::run('_core_messages/load')"}
        {include="content/$content_file"}
    </div>
    {include="partials/call_to_action_panel"}
    {include="partials/footer"}
    {include="partials/scripts"}
</body>
</html>

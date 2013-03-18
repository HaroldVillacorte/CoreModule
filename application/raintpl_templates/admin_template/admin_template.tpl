<!DOCTYPE html>
{include="partials/head"}
<body>
    {include="partials/header_nav"}

    <div class="row">
        <section class="twelve columns">
            {include="partials/highlighted"}
        </section>
    </div>

    <!-- Content -->
    <div class="row">
        <section class="twelve columns">
            {function="Modules::run('_core_messages/load')"}
            {include="content/$content_file"}
            {include="partials/call_to_action_panel"}
        </section>
    </div>

    {include="partials/footer"}
    {include="partials/scripts"}
</body>
</html>

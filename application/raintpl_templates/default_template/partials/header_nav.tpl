<!-- Header and Nav -->
<div class="row">
    <div class="three columns">
        <h1>
            <img src="http://placehold.it/400x100&text={$site_name}" />
        </h1>
    </div>
    <div class="nine columns">
        <ul class="nav-bar right">
            <li><a href="{function="base_url()"}">Home</a></li>
            <!-- Template demos -->
            <li class="has-flyout">
                <a href="#">Template demos</a>
                <a href="#" class="flyout-toggle"><span> </span></a>
                <ul class="flyout">
                    <li><a href="{function="base_url()"}default_controller/columns/one_column/">One column</a></li>
                    <li><a href="{function="base_url()"}default_controller/columns/two_column/">Two column</a></li>
                    <li><a href="{function="base_url()"}default_controller/columns/three_column/">Three column</a></li>
                    <li><a href="{function="base_url()"}default_controller/columns/branded/">Branded</a></li>
                    <li><a href="{function="base_url()"}default_controller/columns/blog/">Blog</a></li>
                    <li><a href="{function="base_url()"}default_controller/columns/feed/">Feed</a></li>
                    <li><a href="{function="base_url()"}default_controller/columns/grid/">Grid</a></li>
                    <li><a href="{function="base_url()"}default_controller/columns/workspace/">Workspace</a></li>
                    <li><a href="{function="base_url()"}default_controller/columns/so_boxy/">So Boxy</a></li>
                </ul>

            </li>

            <!-- Crud demos -->
            <li class="has-flyout">
                <a href="#">Crud demos</a>
                <a href="#" class="flyout-toggle"><span> </span></a>
                <ul class="flyout">
                    <li><a href="{function="base_url()"}demo_grocery_crud/">Grocery Crud demo</a></li>
                    <li><a href="{function="base_url()"}demo_doctrine2/">Doctrine 2 demo</a></li>
                </ul>
            </li>

            <!-- Admin menu -->
            <li class="has-flyout">
                <a href="#">Admin menu</a>
                <a href="#" class="flyout-toggle"><span> </span></a>
                <ul class="flyout">
                    <li><a href="{function="base_url()"}user/admin_users/">Admin</a></li>
                    <li><a href="{function="base_url()"}user/admin_roles/">User roles</a></li>
                </ul>
            </li>

            <!-- Login links -->
            {$user_action="login"}
            {if="$CI->session->userdata('user_id')"}
                {$user_action="logout"}
                <li><a href="{function="base_url()"}user/">Profile</a></li>
            {/if}
            <li><a href="{function="base_url()"}user/{$user_action}">{$user_action}</a></li>

        </ul>
    </div>
</div>
<!-- End Header and Nav -->

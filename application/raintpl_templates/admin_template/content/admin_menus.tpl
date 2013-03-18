<h4>Menus</h4>

<table width="100%">
    <thead>
        <tr>
            <th width="5%">Id</th>
            <th width="10%">Menu name</th>
            <th width="15%">Description</th>
            <th width="10%"><a href="{function="base_url()"}{$CI->core_menu_library->menu_add_uri}">Add menu +</a></th>
            <th width="60%">Links</th>
        </tr>
    </thead>
{loop="$menus"}
    <tr>
        <td>{$value->id}</td>
        <td>{$value->menu_name}</td>
        <td>{$value->description}</td>
        <td>

            <a class="label round" href="{function="base_url()"}{$CI->core_menu_library->menu_edit_uri}{$value->id}">Edit</a>&nbsp
            <a class="label alert round" href="{function="base_url()"}{$CI->core_menu_library->menu_delete_uri}{$value->id}"
               onClick="return confirm('{function="lang('menu_confirm_delete')"}')">Delete</a>
        </td>
        <td>
            {if="$value->links"}
                <ul class="button-group">
                {loop="$value->links"}
                    <li><a class="secondary button left small" href="{function="base_url()"}{$CI->core_menu_library->menu_link_edit_uri}{$value->id}">{$value->text}</a></li>
                {/loop}
                </ul>
            {/if}
            <a class="button small" href="{function="base_url()"}{$CI->core_menu_library->menu_link_add_uri}{$value->id}">Add link +</a>
        </td>
    </tr>
{/loop}
<table>
{$pagination}

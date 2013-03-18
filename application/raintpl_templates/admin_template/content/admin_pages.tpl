<h4>Pages</h4>

<table width="100%">
    <thead>
        <tr>
            <th>Id</th>
            <th>Published</th>
            <th>Author</th>
            <th>Created</th>
            <th>Last edit</th>
            <th>Last edited by</th>
            <th>Slug</th>
            <th>Title</th>
            <th><a href="{function="base_url()"}{$CI->core_pages_library->page_add_uri}">Add page +</a></th>
        </tr>
    </thead>
{loop="$pages"}
    <tr>
        <td>{$value->id}</td>
        <td>{$value->published}</td>
        <td>{$value->username}</td>
        <td>{$value->created}</td>
        <td>{$value->last_edit}</td>
        <td>{$value->last_editor}</td>
        <td>{$value->slug}</td>
        <td>{$value->title}</td>
        <td>
            <a href="{function="base_url()"}{$CI->core_pages_library->page_edit_uri}{$value->id}">Edit</a>
            <a href="{function="base_url()"}{$CI->core_pages_library->page_delete_uri}{$value->id}"
               onClick="return confirm('{function="lang('page_confirm_delete')"}')">Delete</a>
        </td>
    </tr>
{/loop}
<table>
{$pagination}

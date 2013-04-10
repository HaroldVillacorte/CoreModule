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
            <th><a href="<?php echo base_url() . $this->core_module_library->page_add_uri ;?>">Add page +</a></th>
        </tr>
    </thead>
<?php foreach ($pages as $page) :?>
    <tr>
        <td><?php echo $page->id ;?></td>
        <td><?php echo $page->published ;?></td>
        <td><?php echo $page->username ;?></td>
        <td><?php echo $page->created ;?></td>
        <td><?php echo $page->last_edit ;?></td>
        <td><?php echo $page->last_editor ;?></td>
        <td><?php echo $page->slug ;?></td>
        <td><?php echo $page->title ;?></td>
        <td>
            <a href="<?php echo base_url() . $this->core_module_library->page_edit_uri . $page->id ;?>">Edit</a>
            <a href="<?php echo base_url() . $this->core_module_library->page_delete_uri . $page->id ;?>"
               onClick="return confirm(<?php echo '\'' . lang('page_confirm_delete') . '\'' ;?>)">Delete</a>
        </td>
    </tr>
<?php endforeach ;?>
<table>
<?php echo $pagination ;?>

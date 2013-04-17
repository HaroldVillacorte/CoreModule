
<div id="ajax-content">

    <h4>
        Categories <img id="loading-img" src="<?php echo $asset ;?>images/load.gif" style="display:none;" />
    </h4>

    <table width="100%">
        <thead>
            <tr>
                <th>Id</th>
                <th>Level</th>
                <th>Name</th>
                <th>
                    <a href="<?php echo base_url($this->core_module_library->category_add_uri) ;?>">Add +</a>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($categories)) :?>
                <?php foreach ($categories as $category) :?>
                    <tr>
                        <td><?php echo $category->id ;?></td>
                        <td><?php echo $category->level ;?></td>
                        <td><?php echo $category->name ;?></td>
                        <td>
                            <a class="label small round secondary"
                               href="<?php echo base_url($this->core_module_library->category_edit_uri . $category->id) ;?>">Edit</a>
                            <a class="label small round alert"
                               href="<?php echo base_url($this->core_module_library->category_delete_uri . $category->id) ;?>"
                               onClick="return confirm(<?php echo '\'' . lang('category_delete_confirm') . '\'' ;?>)">Delete</a>
                        </td>
                    </tr>
                <?php endforeach ;?>
            <?php endif ;?>
        </tbody>
    </table>

    <?php echo $pagination ;?>

</div>


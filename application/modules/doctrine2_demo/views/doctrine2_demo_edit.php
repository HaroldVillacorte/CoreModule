<div class="twelve columns">
  <h5>Doctrine 2 Demo Edit</h5>
  <p>Render time: <?php echo $elapsed_time;?></p>

  <?php echo form_open('doctrine2_demo/edit');?>

  <input type="hidden" name="id" value="<?php echo set_value('id', $record->getId());?>" />

  <label for="order_number">Order number:</label>
  <input type="text" name="order_number" value="<?php echo set_value('order_number', $record->getOrdernumber());?>" />

  <label for="order_number">Product code:</label>
  <input type="text" name="product_code" value="<?php echo set_value('product_code', $record->getProductcode());?>" />

  <label for="order_number">Quantity ordered:</label>
  <input type="text" name="quantitiy_ordered" value="<?php echo set_value('quantitiy_ordered', $record->getQuantityordered());?>" />

  <label for="order_number">Price:</label>
  <input type="text" name="price_each" value="<?php echo set_value('price_each', $record->getPriceeach());?>" />

  <label for="order_number">Line number:</label>
  <input type="text" name="line_number" value="<?php echo set_value('line_number', $record->getOrderlinenumber());?>" />

  <label for="order_number">Comments:</label>
  <?php $text_value = set_value('text', $record->getText());?>
  <textarea name="text" value="<?php echo $text_value;?>"><?php echo $text_value;?></textarea>

  <p style="margin-top:1em;"><?php echo form_submit('save', 'Save')?>

    <noscript>
    <a href="<?php echo base_url() . 'doctrine2_demo/data/' . $user_page;?>">Back to list</a>
    </noscript>

    <a id="ajax-back-button" href="back" ONCLICK="history.go(-1)" style="display:none;">Back to list</a>

  </p>
  <?php echo form_close();?>
</div>
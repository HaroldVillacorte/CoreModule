<div class="twelve columns">
    <h5>Doctrine 2 Demo Edit</h5>
    <p>Render time: {$elapsed_time}</p>

    {function="form_open('demo_doctrine2/edit')"}

    <input type="hidden" name="id" value="{function="set_value('id', $record->getId())"}" />

    <label for="order_number">Order number:</label>
    <input type="text" name="order_number" value="{function="set_value('order_number', $record->getOrdernumber())"}" />

    <label for="order_number">Product code:</label>
    <input type="text" name="product_code" value="{function="set_value('product_code', $record->getProductcode())"}" />

    <label for="order_number">Quantity ordered:</label>
    <input type="text" name="quantitiy_ordered" value="{function="set_value('quantitiy_ordered', $record->getQuantityordered())"}" />

    <label for="order_number">Price:</label>
    <input type="text" name="price_each" value="{function="set_value('price_each', $record->getPriceeach())"}" />

    <label for="order_number">Line number:</label>
    <input type="text" name="line_number" value="{function="set_value('line_number', $record->getOrderlinenumber())"}" />

    <label for="order_number">Comments:</label>
    <textarea name="text">{function="set_value('text', $record->getText())"}</textarea>

    <p style="margin-top:1em;">{function="form_submit('save', 'Save')"}

        <noscript>
        <a href="{function="base_url()"}demo_doctrine2/data/{$user_page}">Back to list</a>
        </noscript>

        <script>
            document.write(
            '<a id="ajax-back-button" href="back" ONCLICK="history.go(-1)">Back to list</a>'
        );
        </script>

    </p>
    {function="form_close()"}
</div>
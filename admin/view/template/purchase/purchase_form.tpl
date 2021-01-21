<?php echo $header; ?>
<?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-purchase" data-toggle="tooltip" title="<?php echo $button_save; ?>"
          class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>"
          class="btn btn-default"><i class="fa fa-reply"></i></a>
      </div>
      <h1>
        <?php echo $heading_title; ?>
      </h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>">
            <?php echo $breadcrumb['text']; ?>
          </a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i>
      <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i>
          <?php echo $text_form; ?>
        </h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-purchase"
          class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-invoice">
              <?php echo $entry_invoice; ?>
            </label>
            <div class="col-sm-10">
              <div class="input-group date">
                <p id="date" class="form-control-static">
                  <?php echo $invoice; ?>
                </p>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-date">
              <?php echo $entry_date; ?>
            </label>
            <div class="col-sm-10">
              <div class="input-group date">
                <p id="date" class="form-control-static">
                  <?php echo $date; ?>
                </p>
                <input type="hidden" name="order_id" value="<?php echo $order_id; ?>" />
              </div>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-supplier">
              <?php echo $entry_supplier_name; ?>
            </label>
            <div class="col-sm-10">
              <input type="text" name="supplier_name" value="<?php echo $supplier_name; ?>"
                placeholder="<?php echo $entry_supplier_name; ?>" id="input-supplier" class="form-control" <?php echo
                $order_id ? 'readonly="readonly"' : '' ; ?> />
              <?php if ($error_supplier_name) { ?>
              <div class="text-danger">
                <?php echo $error_supplier_name; ?>
              </div>
              <?php } ?>
              <input type="hidden" name="supplier_id" value="<?php echo $supplier_id; ?>" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-telephone">
              <?php echo $entry_telephone; ?>
            </label>
            <div class="col-sm-10">
              <input type="text" name="telephone" value="<?php echo $telephone; ?>"
                placeholder="<?php echo $entry_telephone; ?>" id="input-telephone" class="form-control" <?php echo
                $order_id ? 'readonly="readonly"' : '' ; ?> />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-contact-person">
              <?php echo $entry_contact_person; ?>
            </label>
            <div class="col-sm-10">
              <input type="text" name="contact_person" value="<?php echo $contact_person; ?>"
                placeholder="<?php echo $entry_contact_person; ?>" id="input-contact-person" class="form-control" />
            </div>
          </div>
          <fieldset>
            <legend>
              <?php echo $text_product_list; ?>
            </legend>
            <div class="table-responsive">
              <table class="table table-bordered table-wide" id="products">
                <thead>
                  <tr>
                    <td class="text-left col-xs-4">
                      <?php echo $column_product; ?>
                    </td>
                    <td class="text-right">
                      <?php echo $column_quantity; ?>
                    </td>
                    <td class="text-left">
                      <?php echo $column_unit_class; ?>
                    </td>
                    <td class="text-right">
                      <?php echo $column_price; ?>
                    </td>
                    <td class="text-right">
                      <?php echo $column_total; ?>
                    </td>
                    <td class="text-right">
                      <?php echo $column_action; ?>
                    </td>
                  </tr>
                </thead>
                <tbody>
                  <?php if ($purchase_products) { ?>
                  <?php foreach ($purchase_products as $key => $purchase_product) { ?>
                  <tr id="product-row<?php echo $key; ?>" value="<?php echo $key; ?>">
                    <td class="text-left">
                      <input type="text" name="product[<?php echo $key; ?>][name]"
                        value="<?php echo $purchase_product['name']; ?>" placeholder="<?php echo $entry_product; ?>"
                        class="form-control" <?php echo $purchase_product['fixed'] ? 'readonly="readonly"' : '' ; ?> />
                      <input type="hidden" name="product[<?php echo $key; ?>][product_id]"
                        value="<?php echo $purchase_product['product_id']; ?>" />
                      <?php foreach ($purchase_product['option'] as $option) { ?>
                      - <small>
                        <?php echo $option['name']; ?>:
                        <?php echo $option['value']; ?>
                      </small>
                      </small><br />
                      <?php } ?>
                      <?php foreach ($purchase_product['attribute'] as $attribute) { ?>
                      - <small>
                        <?php echo $attribute['name']; ?>:
                        <?php echo $attribute['value']; ?>
                      </small>
                      </small><br />
                      <?php } ?>
                    </td>
                    <td class="text-right">
                      <input type="text" name="product[<?php echo $key; ?>][quantity]"
                        value="<?php echo $purchase_product['quantity']; ?>"
                        placeholder="<?php echo $entry_quantity; ?>" class="form-control currency" />
                    </td>
                    <td>
                      <select name="product[<?php echo $key; ?>][unit_class]" class="form-control">
                        <?php if ($purchase_product['fixed']) { ?>
                        <option value="<?php echo $purchase_product['unit_class']; ?>">
                          <?php echo $purchase_product['unit_class']; ?>
                        </option>
                        <?php } else { ?>
                        <option value="">
                          <?php echo $text_select; ?>
                        </option>
                        <?php foreach ($unit_classes as $unit_class) { ?>
                        <?php if ($unit_class['title'] == $purchase_product['unit_class']) { ?>
                        <option value="<?php echo $unit_class['title']; ?>" selected="selected">
                          <?php echo $unit_class['title']; ?>
                        </option>
                        <?php } else { ?>
                        <option value="<?php echo $unit_class['title']; ?>">
                          <?php echo $unit_class['title']; ?>
                        </option>
                        <?php } ?>
                        <?php } ?>
                      </select>
                      <?php } ?>
                    </td>
                    <td class="text-right">
                      <input type="text" name="product[<?php echo $key; ?>][price]"
                        value="<?php echo $purchase_product['price']; ?>" placeholder="<?php echo $entry_price; ?>"
                        class="form-control currency" />
                    </td>
                    <td class="text-right">
                      <input type="text" name="product[<?php echo $key; ?>][total]"
                        value="<?php echo $purchase_product['total']; ?>" class="form-control" readonly="readonly" />
                    </td>
                    <td class="text-right"><button type="button" data-toggle="tooltip"
                        title="<?php echo $button_remove; ?>" class="btn btn-danger btn-remove" <?php echo
                        $purchase_product['fixed'] ? 'disabled="disabled"' : '' ; ?> ><i
                          class="fa fa-minus-circle"></i></button></td>
                  </tr>
                  <?php } ?>
                  <?php } ?>
                </tbody>
                <tfoot>
                  <tr class="text-right">
                    <td colspan="4">
                      <?php echo $text_subtotal; ?>
                    </td>
                    <td><input type="text" name="subtotal" value="0" class="form-control" readonly="readonly" /></td>
                    <td><button type="button" onclick="addProduct();" data-toggle="tooltip"
                        title="<?php echo $button_product_add; ?>" class="btn btn-primary"><i
                          class="fa fa-plus-circle"></i></button></td>
                  </tr>
                  <tr>
                    <td class="text-right" colspan="4">
                      <?php echo $text_adjustment; ?>
                    </td>
                    <td><input type="text" name="adjustment" value="<?php echo $adjustment; ?>"
                        class="form-control currency" /></td>
                  </tr>
                  <tr>
                    <td class="text-right" colspan="4">
                      <?php echo $text_total; ?>
                    </td>
                    <td><input type="text" name="total" value="0" class="form-control" readonly="readonly" /></td>
                  </tr>
                </tfoot>
              </table>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-comment">
                <?php echo $entry_comment; ?>
              </label>
              <div class="col-sm-10">
                <textarea name="comment" rows="2" id="input-comment"
                  class="form-control"><?php echo $comment; ?></textarea>
              </div>
            </div>
          </fieldset>
        </form>
      </div>
    </div>
  </div>
  <script type="text/javascript">
    $('input[name=\'supplier_name\']').autocomplete({
      'source': function (request, response) {
        $.ajax({
          url: 'index.php?route=purchase/purchase/supplierAutocomplete&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request),
          dataType: 'json',
          success: function (json) {
            response($.map(json, function (item) {
              return {
                label: item['supplier_name'],
                value: item['supplier_id']
              }
            }));
          }
        });
      },
      'select': function (item) {
        $('input[name=\'supplier_name\']').val(item['label']);
        $('input[name=\'supplier_id\']').val(item['value']);
      }
    });
  </script>
  <script type="text/javascript">
    let inline_total_str = JSON.parse('<?php echo $purchase_products_total ?>');
    let inline_total = [],
      subtotal = 0,
      adjustment = 0;

    for (var i = 0; i < inline_total_str.length; i++) {
      inline_total[i] = getNumber(inline_total_str[i]);
    };

    calculateTotal(inline_total);

    let product_row = '<?php echo $purchase_product_idx ?>';

    function addProduct() {
      html = '<tr id="product-row' + product_row + '" value="' + product_row + '">';
      html += '  <td><input type="text" name="product[' + product_row + '][name]" value="" placeholder="<?php echo $entry_product; ?>" class="form-control" /></td>';
      html += '  <input type="hidden" name="product[' + product_row + '][product_id]" value="0" /></td>';
      html += '  <td><input type="text" name="product[' + product_row + '][quantity]" value="0" class="form-control currency" /></td>';
      html += `  <td><select name="product[` + product_row + `][unit_class]" class="form-control">;
               <option value=""><?php echo $text_select; ?></option>;
               <?php foreach ($unit_classes as $unit_class) { ?>;
               <option value="<?php echo $unit_class['title']; ?>"><?php echo $unit_class['title']; ?></option>;
               <?php } ?>';
               </select></td>`;
      html += '  <td><input type="text" name="product[' + product_row + '][price]" value="0" class="form-control currency" /></td>';
      html += '  <td><input type="text" name="product[' + product_row + '][total]" value="0" class="form-control" readonly="readonly" /></td>';
      html += '  <td class="text-right"><button type="button" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger btn-remove"><i class="fa fa-minus-circle"></i></button></td>';
      html += '</tr>';

      $('#products tbody').append(html);

      product_row++;
    };

    $('#products').on('click', '.btn-remove', function () {
      let idx = $(this).closest('tr').attr('value');

      $('#product-row' + idx).remove();
      inline_total[idx] = 0;
      calculateTotal(inline_total);
    });


    $('#products').on('keyup', 'input[name^=\'product\'].currency', function () {
      let idx = $(this).closest('tr').attr('value');
      quantity = getNumber($('#products input[name=\'product[' + idx + '][quantity]\']').val());
      price = getNumber($('#products input[name=\'product[' + idx + '][price]\']').val());

      inline_total[idx] = quantity * price;

      $('#products input[name=\'product[' + idx + '][total]\']').val((inline_total[idx]).toLocaleString());

      calculateTotal(inline_total);
    });

    $('input[name=\'adjustment\']').on('keyup', function () {
      calculateTotal(inline_total);
    });

    function calculateTotal(data_total) {
      subtotal = data_total.reduce(function (sum, element) {
        return sum + element;
      }, 0);

      adjustment = getNumber($('input[name=\'adjustment\']').val());

      $('input[name=\'subtotal\']').val((subtotal).toLocaleString());
      $('input[name=\'total\']').val((subtotal + adjustment).toLocaleString());
    };

    // Currency Format
    function getNumber(str) {
      return Number(str.replace(/(?!-)[^0-9.]/g, ""));
    };

    $('#content').on('keyup', 'input.currency', function () {
      let node = this;
      $(node).val(getNumber($(node).val()).toLocaleString());
    });

    $('input.currency').trigger('keyup');
  </script>
  <!-- <script type="text/javascript">
    // Customer
    $('input[name=\'customer\']').autocomplete({
      'source': function (request, response) {
        $.ajax({
          url: 'index.php?route=customer/customer/autocomplete&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request),
          dataType: 'json',
          success: function (json) {
            json.unshift({
              customer_id: '0',
              customer_group_id: '<?php echo $customer_group_id; ?>',
              name: '<?php echo $text_none; ?>',
              customer_group: '',
              firstname: '',
              lastname: '',
              id_no: '',
              email: '',
              telephone: '',
              fax: '',
              custom_field: [],
              address: []
            });

            response($.map(json, function (item) {
              return {
                category: item['customer_group'],
                label: item['name'],
                value: item['customer_id'],
                customer_group_id: item['customer_group_id'],
                firstname: item['firstname'],
                lastname: item['lastname'],
                id_no: item['id_no'],
                email: item['email'],
                telephone: item['telephone'],
                fax: item['fax'],
                custom_field: item['custom_field'],
                address: item['address']
              }
            }));
          }
        });
      },
      'select': function (item) {
        // Reset all custom fields
        $('#tab-customer input[type=\'text\'], #tab-customer textarea').not('#tab-customer input[name=\'customer\'], #tab-customer input[name=\'customer_id\']').val('');
        $('#tab-customer select option').removeAttr('selected');
        $('#tab-customer input[type=\'checkbox\'], #tab-customer input[type=\'radio\']').removeAttr('checked');

        $('#tab-customer input[name=\'customer\']').val(item['label']);
        $('#tab-customer input[name=\'customer_id\']').val(item['value']);
        $('#tab-customer select[name=\'customer_group_id\']').val(item['customer_group_id']);
        $('#tab-customer input[name=\'firstname\']').val(item['firstname']);
        $('#tab-customer input[name=\'lastname\']').val(item['lastname']);
        $('#tab-customer input[name=\'id_no\']').val(item['id_no']);
        $('#tab-customer input[name=\'email\']').val(item['email']);
        $('#tab-customer input[name=\'telephone\']').val(item['telephone']);
        $('#tab-customer input[name=\'fax\']').val(item['fax']);

        for (i in item.custom_field) {
          $('#tab-customer select[name=\'custom_field[' + i + ']\']').val(item.custom_field[i]);
          $('#tab-customer textarea[name=\'custom_field[' + i + ']\']').val(item.custom_field[i]);
          $('#tab-customer input[name^=\'custom_field[' + i + ']\'][type=\'text\']').val(item.custom_field[i]);
          $('#tab-customer input[name^=\'custom_field[' + i + ']\'][type=\'hidden\']').val(item.custom_field[i]);
          $('#tab-customer input[name^=\'custom_field[' + i + ']\'][type=\'radio\'][value=\'' + item.custom_field[i] + '\']').prop('checked', true);

          if (item.custom_field[i] instanceof Array) {
            for (j = 0; j < item.custom_field[i].length; j++) {
              $('#tab-customer input[name^=\'custom_field[' + i + ']\'][type=\'checkbox\'][value=\'' + item.custom_field[i][j] + '\']').prop('checked', true);
            }
          }
        }

        $('select[name=\'customer_group_id\']').trigger('change');

        html = '<option value="0"><?php echo $text_none; ?></option>';

        for (i in item['address']) {
          html += '<option value="' + item['address'][i]['address_id'] + '">' + item['address'][i]['firstname'] + ' ' + item['address'][i]['lastname'] + ', ' + item['address'][i]['address_1'] + ', ' + item['address'][i]['city'] + ', ' + item['address'][i]['country'] + '</option>';
        }

        $('select[name=\'payment_address\']').html(html);

        $('select[name=\'payment_address\']').trigger('change');
      }
    });

    // Checkout
    // $('#button-save').on('click', function () {
    //   if ($('input[name=\'purchase_id\']').val() == 0) {
    //     var url = '<?php echo $store_url; ?>index.php?route=api/purchase/add&token=' + token + '&store_id=' + $('select[name=\'store_id\'] option:selected').val();
    //   } else {
    //     var url = '<?php echo $store_url; ?>index.php?route=api/purchase/edit&token=' + token + '&store_id=' + $('select[name=\'store_id\'] option:selected').val() + '&purchase_id=' + $('input[name=\'purchase_id\']').val();
    //   }

    //   $.ajax({
    //     url: url,
    //     type: 'post',
    //     data: 'payment_method=' + encodeURIComponent($('select[name=\'payment_method\']').val()) + '&purchase_status_id=' + encodeURIComponent($('#tab-total select[name=\'purchase_status_id\']').val()) + '&comment=' + encodeURIComponent($('#tab-total textarea[name=\'comment\']').val()) + '&affiliate_id=' + Number($('#tab-total input[name=\'affiliate_id\']').val()) + '&user_id=<?php echo $user_id; ?>',
    //     dataType: 'json',
    //     crossDomain: true,
    //     beforeSend: function () {
    //       $('#button-save').button('loading');
    //     },
    //     complete: function () {
    //       $('#button-save').button('reset');
    //     },
    //     success: function (json) {
    //       $('.alert, .text-danger').remove();

    //       if (json['error']) {
    //         $('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
    //       }

    //       if (json['success']) {
    //         $('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '  <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

    //         if (json['purchase_id']) {
    //           purchase_id = json['purchase_id'];
    //         } else {
    //           purchase_id = <? php echo $purchase_id; ?>;
    //         }

    //         location = 'index.php?route=purchase/purchase/info&token=<?php echo $token; ?>&purchase_id=' + purchase_id + '<?php echo $url; ?>';
    //       }
    //     },
    //     error: function (xhr, ajaxOptions, thrownError) {
    //       alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
    //     }
    //   });
    // });
  </script> -->
  <!-- <script>
    $('.date').datetimepicker({
      pickTime: false
    });
  </script> -->
</div>
<?php echo $footer; ?>
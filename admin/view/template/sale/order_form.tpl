<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right"><a href="<?php echo $cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i> <?php echo $button_cancel; ?></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
      </div>
      <div class="panel-body">
        <form class="form-horizontal">
          <ul id="order" class="nav nav-tabs nav-justified">
            <li class="disabled active"><a href="#tab-customer" data-toggle="tab">1. <?php echo $tab_customer; ?></a></li>
            <li class="disabled"><a href="#tab-cart" data-toggle="tab">2. <?php echo $tab_product; ?></a></li>
            <li class="disabled"><a href="#tab-payment" data-toggle="tab">3. <?php echo $tab_payment; ?></a></li>
            <li class="disabled"><a href="#tab-total" data-toggle="tab">4. <?php echo $tab_total; ?></a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab-customer">
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-store"><?php echo $entry_store; ?></label>
                <div class="col-sm-10">
                  <select name="store_id" id="input-store" class="form-control">
                    <?php foreach ($stores as $store) { ?>
                    <?php if ($store['store_id'] == $store_id) { ?>
                    <option value="<?php echo $store['store_id']; ?>" selected="selected"><?php echo $store['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-currency"><?php echo $entry_currency; ?></label>
                <div class="col-sm-10">
                  <select name="currency" id="input-currency" class="form-control">
                    <?php foreach ($currencies as $currency) { ?>
                    <?php if ($currency['code'] == $currency_code) { ?>
                    <option value="<?php echo $currency['code']; ?>" selected="selected"><?php echo $currency['title']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $currency['code']; ?>"><?php echo $currency['title']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-customer"><?php echo $entry_customer; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="customer" value="<?php echo $customer; ?>" placeholder="<?php echo $entry_customer; ?>" id="input-customer" class="form-control" />
                  <input type="hidden" name="customer_id" value="<?php echo $customer_id; ?>" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-customer-group"><?php echo $entry_customer_group; ?></label>
                <div class="col-sm-10">
                  <select name="customer_group_id" id="input-customer-group" class="form-control">
                    <?php foreach ($customer_groups as $customer_group) { ?>
                    <?php if ($customer_group['customer_group_id'] == $customer_group_id) { ?>
                    <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-firstname"><?php echo $entry_firstname; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="firstname" value="<?php echo $firstname; ?>" id="input-firstname" class="form-control" />
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-lastname"><?php echo $entry_lastname; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="lastname" value="<?php echo $lastname; ?>" id="input-lastname" class="form-control" />
                </div>
              </div>
			  <div class="form-group required">
				<label class="col-sm-2 control-label" for="input-id-no"><?php echo $entry_id_no; ?></label>
				<div class="col-sm-10">
				  <input type="text" name="id_no" value="<?php echo $id_no; ?>" placeholder="<?php echo $entry_id_no; ?>" id="input-id-no" class="form-control" />
				</div>
			  </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-email"><?php echo $entry_email; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="email" value="<?php echo $email; ?>" id="input-email" class="form-control" />
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-telephone"><?php echo $entry_telephone; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="telephone" value="<?php echo $telephone; ?>" id="input-telephone" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-fax"><?php echo $entry_fax; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="fax" value="<?php echo $fax; ?>" id="input-fax" class="form-control" />
                </div>
              </div>
              <?php foreach ($custom_fields as $custom_field) { ?>
              <?php if ($custom_field['location'] == 'account') { ?>
              <?php if ($custom_field['type'] == 'select') { ?>
              <div class="form-group custom-field custom-field<?php echo $custom_field['custom_field_id']; ?>" data-sort="<?php echo $custom_field['sort_order'] + 3; ?>">
                <label class="col-sm-2 control-label" for="input-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
                <div class="col-sm-10">
                  <select name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" id="input-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control">
                    <option value=""><?php echo $text_select; ?></option>
                    <?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
                    <?php if (isset($account_custom_field[$custom_field['custom_field_id']]) && $custom_field_value['custom_field_value_id'] == $account_custom_field[$custom_field['custom_field_id']]) { ?>
                    <option value="<?php echo $custom_field_value['custom_field_value_id']; ?>" selected="selected"><?php echo $custom_field_value['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $custom_field_value['custom_field_value_id']; ?>"><?php echo $custom_field_value['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <?php } ?>
              <?php if ($custom_field['type'] == 'radio') { ?>
              <div class="form-group custom-field custom-field<?php echo $custom_field['custom_field_id']; ?>" data-sort="<?php echo $custom_field['sort_order'] + 3; ?>">
                <label class="col-sm-2 control-label"><?php echo $custom_field['name']; ?></label>
                <div class="col-sm-10">
                  <div id="input-custom-field<?php echo $custom_field['custom_field_id']; ?>">
                    <?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
                    <div class="radio">
                      <?php if (isset($account_custom_field[$custom_field['custom_field_id']]) && $custom_field_value['custom_field_value_id'] == $account_custom_field[$custom_field['custom_field_id']]) { ?>
                      <label>
                        <input type="radio" name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo $custom_field_value['custom_field_value_id']; ?>" checked="checked" />
                        <?php echo $custom_field_value['name']; ?></label>
                      <?php } else { ?>
                      <label>
                        <input type="radio" name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo $custom_field_value['custom_field_value_id']; ?>" />
                        <?php echo $custom_field_value['name']; ?></label>
                      <?php } ?>
                    </div>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <?php } ?>
              <?php if ($custom_field['type'] == 'checkbox') { ?>
              <div class="form-group custom-field custom-field<?php echo $custom_field['custom_field_id']; ?>" data-sort="<?php echo $custom_field['sort_order'] + 3; ?>">
                <label class="col-sm-2 control-label"><?php echo $custom_field['name']; ?></label>
                <div class="col-sm-10">
                  <div id="input-custom-field<?php echo $custom_field['custom_field_id']; ?>">
                    <?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
                    <div class="checkbox">
                      <?php if (isset($account_custom_field[$custom_field['custom_field_id']]) && in_array($custom_field_value['custom_field_value_id'], $account_custom_field[$custom_field['custom_field_id']])) { ?>
                      <label>
                        <input type="checkbox" name="custom_field[<?php echo $custom_field['custom_field_id']; ?>][]" value="<?php echo $custom_field_value['custom_field_value_id']; ?>" checked="checked" />
                        <?php echo $custom_field_value['name']; ?></label>
                      <?php } else { ?>
                      <label>
                        <input type="checkbox" name="custom_field[<?php echo $custom_field['custom_field_id']; ?>][]" value="<?php echo $custom_field_value['custom_field_value_id']; ?>" />
                        <?php echo $custom_field_value['name']; ?></label>
                      <?php } ?>
                    </div>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <?php } ?>
              <?php if ($custom_field['type'] == 'text') { ?>
              <div class="form-group custom-field custom-field<?php echo $custom_field['custom_field_id']; ?>" data-sort="<?php echo $custom_field['sort_order'] + 3; ?>">
                <label class="col-sm-2 control-label" for="input-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo (isset($account_custom_field[$custom_field['custom_field_id']]) ? $account_custom_field[$custom_field['custom_field_id']] : $custom_field['value']); ?>" placeholder="<?php echo $custom_field['name']; ?>" id="input-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control" />
                </div>
              </div>
              <?php } ?>
              <?php if ($custom_field['type'] == 'textarea') { ?>
              <div class="form-group custom-field custom-field<?php echo $custom_field['custom_field_id']; ?>" data-sort="<?php echo $custom_field['sort_order'] + 3; ?>">
                <label class="col-sm-2 control-label" for="input-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
                <div class="col-sm-10">
                  <textarea name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" rows="5" placeholder="<?php echo $custom_field['name']; ?>" id="input-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control"><?php echo $custom_field['value']; ?></textarea>
                </div>
              </div>
              <?php } ?>
              <?php if ($custom_field['type'] == 'file') { ?>
              <div class="form-group custom-field custom-field<?php echo $custom_field['custom_field_id']; ?>" data-sort="<?php echo $custom_field['sort_order'] + 3; ?>">
                <label class="col-sm-2 control-label"><?php echo $custom_field['name']; ?></label>
                <div class="col-sm-10">
                  <button type="button" id="button-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="btn btn-default"><i class="fa fa-upload"></i> <?php echo $button_upload; ?></button>
                  <input type="hidden" name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo (isset($account_custom_field[$custom_field['custom_field_id']]) ? $account_custom_field[$custom_field['custom_field_id']] : ''); ?>" id="input-custom-field<?php echo $custom_field['custom_field_id']; ?>" />
                </div>
              </div>
              <?php } ?>
              <?php if ($custom_field['type'] == 'date') { ?>
              <div class="form-group custom-field custom-field<?php echo $custom_field['custom_field_id']; ?>" data-sort="<?php echo $custom_field['sort_order'] + 3; ?>">
                <label class="col-sm-2 control-label" for="input-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
                <div class="col-sm-10">
                  <div class="input-group date">
                    <input type="text" name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo (isset($account_custom_field[$custom_field['custom_field_id']]) ? $account_custom_field[$custom_field['custom_field_id']] : $custom_field['value']); ?>" placeholder="<?php echo $custom_field['name']; ?>" data-date-format="YYYY-MM-DD" id="input-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control" />
                    <span class="input-group-btn">
                    <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                    </span></div>
                </div>
              </div>
              <?php } ?>
              <?php if ($custom_field['type'] == 'time') { ?>
              <div class="form-group custom-field custom-field<?php echo $custom_field['custom_field_id']; ?>" data-sort="<?php echo $custom_field['sort_order'] + 3; ?>">
                <label class="col-sm-2 control-label" for="input-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
                <div class="col-sm-10">
                  <div class="input-group time">
                    <input type="text" name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo (isset($account_custom_field[$custom_field['custom_field_id']]) ? $account_custom_field[$custom_field['custom_field_id']] : $custom_field['value']); ?>" placeholder="<?php echo $custom_field['name']; ?>" data-date-format="HH:mm" id="input-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control" />
                    <span class="input-group-btn">
                    <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                    </span></div>
                </div>
              </div>
              <?php } ?>
              <?php if ($custom_field['type'] == 'datetime') { ?>
              <div class="form-group custom-field custom-field<?php echo $custom_field['custom_field_id']; ?>" data-sort="<?php echo $custom_field['sort_order'] + 3; ?>">
                <label class="col-sm-2 control-label" for="input-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
                <div class="col-sm-10">
                  <div class="input-group datetime">
                    <input type="text" name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo (isset($account_custom_field[$custom_field['custom_field_id']]) ? $account_custom_field[$custom_field['custom_field_id']] : $custom_field['value']); ?>" placeholder="<?php echo $custom_field['name']; ?>" data-date-format="YYYY-MM-DD HH:mm" id="input-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control" />
                    <span class="input-group-btn">
                    <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                    </span></div>
                </div>
              </div>
              <?php } ?>
              <?php } ?>
              <?php } ?>
              <div class="text-right">
                <button type="button" id="button-customer" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary"><i class="fa fa-arrow-right"></i> <?php echo $button_continue; ?></button>
              </div>
            </div>
            <div class="tab-pane" id="tab-cart">
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-event-date"><?php echo $entry_event_date; ?></label>
                <div class="col-sm-10">
                  <div class="input-group date">
                    <input type="text" name="event_date" value="<?php echo $event_date; ?>" placeholder="<?php echo $entry_event_date; ?>" data-date-format="YYYY-MM-DD" id="input-event-date" class="form-control" />
                    <span class="input-group-btn">
                      <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                    </span></div>
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-slot"><?php echo $entry_slot; ?></label>
                <div class="col-sm-10">
                  <select name="slot_id" id="input-slot" class="form-control">
				    <option value=""><?php echo $text_select; ?></option>
                    <?php foreach ($slots as $slot) { ?>
                    <?php if ($slot['slot_id'] == $slot_id) { ?>
                    <option value="<?php echo $slot['slot_id']; ?>" selected="selected"><?php echo $slot['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $slot['slot_id']; ?>"><?php echo $slot['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-ceremony"><?php echo $entry_ceremony; ?></label>
                <div class="col-sm-10">
                  <select name="ceremony_id" id="input-ceremony" class="form-control">
				    <option value=""><?php echo $text_select; ?></option>
                    <?php foreach ($ceremonies as $ceremony) { ?>
                    <?php if ($ceremony['ceremony_id'] == $ceremony_id) { ?>
                    <option value="<?php echo $ceremony['ceremony_id']; ?>" selected="selected"><?php echo $ceremony['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $ceremony['ceremony_id']; ?>"><?php echo $ceremony['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <fieldset>
                <legend><?php echo $text_product_list; ?></legend>
              <div class="table-responsive">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <td class="text-left"><?php echo $column_product_type; ?></td>
                      <td class="text-left"><?php echo $column_product; ?></td>
                      <td class="text-left"><?php echo $column_model; ?></td>
                      <td class="text-right"><?php echo $column_quantity; ?></td>
                      <td class="text-left"><?php echo $column_unit_class; ?></td>
                      <td class="text-right"><?php echo $column_price; ?></td>
                      <td class="text-right"><?php echo $column_total; ?></td>
                      <td class="text-right"><?php echo $column_action; ?></td>
                    </tr>
                  </thead>
                  <tbody id="cart">
                    <?php if ($order_products) { ?>
                    <?php $product_row = 0; ?>
                    <?php foreach ($order_products as $order_product) { ?>
                    <tr>
					  <td class="text-left"><?php echo $order_product['category']; ?>
                        <input type="hidden" name="product[<?php echo $product_row; ?>][category]" value="<?php echo $order_product['category']; ?>" /></td>
                      <td class="text-left"><?php echo $order_product['name']; ?><br />
                        <input type="hidden" name="product[<?php echo $product_row; ?>][product_id]" value="<?php echo $order_product['product_id']; ?>" />
                        <?php foreach ($order_product['option'] as $option) { ?>
                        - <small><?php echo $option['name']; ?>: <?php echo $option['value']; ?></small><br />
                        <?php if ($option['type'] == 'select' || $option['type'] == 'radio' || $option['type'] == 'image') { ?>
                        <input type="hidden" name="product[<?php echo $product_row; ?>][option][<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['product_option_value_id']; ?>" />
                        <?php } ?>
                        <?php if ($option['type'] == 'checkbox') { ?>
                        <input type="hidden" name="product[<?php echo $product_row; ?>][option][<?php echo $option['product_option_id']; ?>][]" value="<?php echo $option['product_option_value_id']; ?>" />
                        <?php } ?>
                        <?php if ($option['type'] == 'text' || $option['type'] == 'textarea' || $option['type'] == 'file' || $option['type'] == 'date' || $option['type'] == 'datetime' || $option['type'] == 'time') { ?>
                        <input type="hidden" name="product[<?php echo $product_row; ?>][option][<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['value']; ?>" />
                        <?php } ?>
                        <?php } ?></td>
                      <td class="text-left"><?php echo $order_product['model']; ?></td>
                      <td class="text-right"><?php echo $order_product['quantity']; ?>
                        <input type="hidden" name="product[<?php echo $product_row; ?>][quantity]" value="<?php echo $order_product['quantity']; ?>" /></td>
                      <td class="text-right">
                        <input type="hidden" name="product[<?php echo $product_row; ?>][price]" value="<?php echo $order_product['price']; ?>" /></td>
                      <td class="text-left"></td>
                      <td class="text-right"></td>
                      <td class="text-center"></td>
                    </tr>
                    <?php $product_row++; ?>
                    <?php } ?>
                    <?php } else { ?>
                    <tr>
                      <td class="text-center" colspan="8"><?php echo $text_no_results; ?></td>
                    </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
              </fieldset>
              <fieldset id="field-product">
                <legend><?php echo $text_product; ?></legend>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-primary-type"><?php echo $entry_primary_type; ?></label>
                  <div class="col-sm-10">
                    <select name="primary_type" id="input-primary-type" class="form-control">
                      <option value="1"><?php echo $text_primary_type; ?></option>
                      <option value="0"><?php echo $text_secondary_type; ?></option>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-category"><?php echo $entry_category; ?></label>
                  <div class="col-sm-10">
                    <select name="category_id" id="input-category" class="form-control">
                   </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-product"><?php echo $entry_product; ?></label>
                  <div class="col-sm-10">
                    <select name="product_id" id="input-product" class="form-control">
                   </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-price"><?php echo $entry_price; ?></label>
                  <div class="col-sm-10">
					<p id="price" class="form-control-static">-</p>
                  </div>
                </div>
                <div id="option"></div>
              </fieldset>
              <div class="text-right">
                <button type="button" id="button-product-add" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i> <?php echo $button_product_add; ?></button>
              </div>
              <br />
              <div class="row">
                <div class="col-sm-6 text-left">
                  <button type="button" onclick="$('a[href=\'#tab-customer\']').tab('show');" class="btn btn-default"><i class="fa fa-arrow-left"></i> <?php echo $button_back; ?></button>
                </div>
                <div class="col-sm-6 text-right">
                  <button type="button" id="button-cart" class="btn btn-primary"><i class="fa fa-arrow-right"></i> <?php echo $button_continue; ?></button>
                </div>
              </div>
            </div>
            <div class="tab-pane" id="tab-payment">
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-payment-address"><?php echo $entry_address; ?></label>
                <div class="col-sm-10">
                  <select name="payment_address" id="input-payment-address" class="form-control">
                    <option value="0" selected="selected"><?php echo $text_none; ?></option>
                    <?php foreach ($addresses as $address) { ?>
                    <option value="<?php echo $address['address_id']; ?>"><?php echo $address['firstname'] . ' ' . $address['lastname'] . ', ' . $address['address_1'] . ', ' . $address['city'] . ', ' . $address['country']; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-payment-firstname"><?php echo $entry_firstname; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="firstname" value="<?php echo $payment_firstname; ?>" id="input-payment-firstname" class="form-control" />
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-payment-lastname"><?php echo $entry_lastname; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="lastname" value="<?php echo $payment_lastname; ?>" id="input-payment-lastname" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-payment-company"><?php echo $entry_company; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="company" value="<?php echo $payment_company; ?>" id="input-payment-company" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-payment-profession"><?php echo $entry_profession; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="profession" value="<?php echo $payment_profession; ?>" id="input-payment-profession" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-payment-position"><?php echo $entry_position; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="position" value="<?php echo $payment_position; ?>" id="input-payment-position" class="form-control" />
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-payment-address-1"><?php echo $entry_address_1; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="address_1" value="<?php echo $payment_address_1; ?>" id="input-payment-address-1" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-payment-address-2"><?php echo $entry_address_2; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="address_2" value="<?php echo $payment_address_2; ?>" id="input-payment-address-2" class="form-control" />
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-payment-city"><?php echo $entry_city; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="city" value="<?php echo $payment_city; ?>" id="input-payment-city" class="form-control" />
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-payment-postcode"><?php echo $entry_postcode; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="postcode" value="<?php echo $payment_postcode; ?>" id="input-payment-postcode" class="form-control" />
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-payment-country"><?php echo $entry_country; ?></label>
                <div class="col-sm-10">
                  <select name="country_id" id="input-payment-country" class="form-control">
                    <option value=""><?php echo $text_select; ?></option>
                    <?php foreach ($countries as $country) { ?>
                    <?php if ($country['country_id'] == $payment_country_id) { ?>
                    <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-payment-zone"><?php echo $entry_zone; ?></label>
                <div class="col-sm-10">
                  <select name="zone_id" id="input-payment-zone" class="form-control">
                  </select>
                </div>
              </div>
              <?php foreach ($custom_fields as $custom_field) { ?>
              <?php if ($custom_field['location'] == 'address') { ?>
              <?php if ($custom_field['type'] == 'select') { ?>
              <div class="form-group custom-field custom-field<?php echo $custom_field['custom_field_id']; ?>" data-sort="<?php echo $custom_field['sort_order'] + 3; ?>">
                <label class="col-sm-2 control-label" for="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
                <div class="col-sm-10">
                  <select name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" id="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control">
                    <option value=""><?php echo $text_select; ?></option>
                    <?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
                    <?php if (isset($payment_custom_field[$custom_field['custom_field_id']]) && $custom_field_value['custom_field_value_id'] == $payment_custom_field[$custom_field['custom_field_id']]) { ?>
                    <option value="<?php echo $custom_field_value['custom_field_value_id']; ?>" selected="selected"><?php echo $custom_field_value['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $custom_field_value['custom_field_value_id']; ?>"><?php echo $custom_field_value['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <?php } ?>
              <?php if ($custom_field['type'] == 'radio') { ?>
              <div class="form-group custom-field custom-field<?php echo $custom_field['custom_field_id']; ?>" data-sort="<?php echo $custom_field['sort_order'] + 3; ?>">
                <label class="col-sm-2 control-label"><?php echo $custom_field['name']; ?></label>
                <div class="col-sm-10">
                  <div id="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>">
                    <?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
                    <div class="radio">
                      <?php if (isset($payment_custom_field[$custom_field['custom_field_id']]) && $custom_field_value['custom_field_value_id'] == $payment_custom_field[$custom_field['custom_field_id']]) { ?>
                      <label>
                        <input type="radio" name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo $custom_field_value['custom_field_value_id']; ?>" checked="checked" />
                        <?php echo $custom_field_value['name']; ?></label>
                      <?php } else { ?>
                      <label>
                        <input type="radio" name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo $custom_field_value['custom_field_value_id']; ?>" />
                        <?php echo $custom_field_value['name']; ?></label>
                      <?php } ?>
                    </div>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <?php } ?>
              <?php if ($custom_field['type'] == 'checkbox') { ?>
              <div class="form-group custom-field custom-field<?php echo $custom_field['custom_field_id']; ?>" data-sort="<?php echo $custom_field['sort_order'] + 3; ?>">
                <label class="col-sm-2 control-label"><?php echo $custom_field['name']; ?></label>
                <div class="col-sm-10">
                  <div id="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>">
                    <?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
                    <div class="checkbox">
                      <?php if (isset($payment_custom_field[$custom_field['custom_field_id']]) && in_array($custom_field_value['custom_field_value_id'], $payment_custom_field[$custom_field['custom_field_id']])) { ?>
                      <label>
                        <input type="checkbox" name="custom_field[<?php echo $custom_field['custom_field_id']; ?>][]" value="<?php echo $custom_field_value['custom_field_value_id']; ?>" checked="checked" />
                        <?php echo $custom_field_value['name']; ?></label>
                      <?php } else { ?>
                      <label>
                        <input type="checkbox" name="custom_field[<?php echo $custom_field['custom_field_id']; ?>][]" value="<?php echo $custom_field_value['custom_field_value_id']; ?>" />
                        <?php echo $custom_field_value['name']; ?></label>
                      <?php } ?>
                    </div>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <?php } ?>
              <?php if ($custom_field['type'] == 'text') { ?>
              <div class="form-group custom-field custom-field<?php echo $custom_field['custom_field_id']; ?>" data-sort="<?php echo $custom_field['sort_order'] + 3; ?>">
                <label class="col-sm-2 control-label" for="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo (isset($payment_custom_field[$custom_field['custom_field_id']]) ? $payment_custom_field[$custom_field['custom_field_id']] : $custom_field['value']); ?>" placeholder="<?php echo $custom_field['name']; ?>" id="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control" />
                </div>
              </div>
              <?php } ?>
              <?php if ($custom_field['type'] == 'textarea') { ?>
              <div class="form-group custom-field custom-field<?php echo $custom_field['custom_field_id']; ?>" data-sort="<?php echo $custom_field['sort_order'] + 3; ?>">
                <label class="col-sm-2 control-label" for="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
                <div class="col-sm-10">
                  <textarea name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" rows="5" placeholder="<?php echo $custom_field['name']; ?>" id="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control"><?php echo (isset($payment_custom_field[$custom_field['custom_field_id']]) ? $payment_custom_field[$custom_field['custom_field_id']] : $custom_field['value']); ?></textarea>
                </div>
              </div>
              <?php } ?>
              <?php if ($custom_field['type'] == 'file') { ?>
              <div class="form-group custom-field custom-field<?php echo $custom_field['custom_field_id']; ?>" data-sort="<?php echo $custom_field['sort_order'] + 3; ?>">
                <label class="col-sm-2 control-label"><?php echo $custom_field['name']; ?></label>
                <div class="col-sm-10">
                  <button type="button" id="button-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-default"><i class="fa fa-upload"></i> <?php echo $button_upload; ?></button>
                  <input type="hidden" name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo (isset($payment_custom_field[$custom_field['custom_field_id']]) ? $payment_custom_field[$custom_field['custom_field_id']] : ''); ?>" id="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" />
                </div>
              </div>
              <?php } ?>
              <?php if ($custom_field['type'] == 'date') { ?>
              <div class="form-group custom-field custom-field<?php echo $custom_field['custom_field_id']; ?>" data-sort="<?php echo $custom_field['sort_order'] + 3; ?>">
                <label class="col-sm-2 control-label" for="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
                <div class="col-sm-10">
                  <div class="input-group date">
                    <input type="text" name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo (isset($payment_custom_field[$custom_field['custom_field_id']]) ? $payment_custom_field[$custom_field['custom_field_id']] : $custom_field['value']); ?>" placeholder="<?php echo $custom_field['name']; ?>" data-date-format="YYYY-MM-DD" id="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control" />
                    <span class="input-group-btn">
                    <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                    </span></div>
                </div>
              </div>
              <?php } ?>
              <?php if ($custom_field['type'] == 'time') { ?>
              <div class="form-group custom-field custom-field<?php echo $custom_field['custom_field_id']; ?>" data-sort="<?php echo $custom_field['sort_order'] + 3; ?>">
                <label class="col-sm-2 control-label" for="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
                <div class="col-sm-10">
                  <div class="input-group time">
                    <input type="text" name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo (isset($payment_custom_field[$custom_field['custom_field_id']]) ? $payment_custom_field[$custom_field['custom_field_id']] : $custom_field['value']); ?>" placeholder="<?php echo $custom_field['name']; ?>" data-date-format="HH:mm" id="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control" />
                    <span class="input-group-btn">
                    <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                    </span></div>
                </div>
              </div>
              <?php } ?>
              <?php if ($custom_field['type'] == 'datetime') { ?>
              <div class="form-group custom-field custom-field<?php echo $custom_field['custom_field_id']; ?>" data-sort="<?php echo $custom_field['sort_order'] + 3; ?>">
                <label class="col-sm-2 control-label" for="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
                <div class="col-sm-10">
                  <div class="input-group datetime">
                    <input type="text" name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo (isset($payment_custom_field[$custom_field['custom_field_id']]) ? $payment_custom_field[$custom_field['custom_field_id']] : $custom_field['value']); ?>" placeholder="<?php echo $custom_field['name']; ?>" data-date-format="YYYY-MM-DD HH:mm" id="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control" />
                    <span class="input-group-btn">
                    <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                    </span></div>
                </div>
              </div>
              <?php } ?>
              <?php } ?>
              <?php } ?>
              <div class="row">
                <div class="col-sm-6 text-left">
                  <button type="button" onclick="$('a[href=\'#tab-cart\']').tab('show');" class="btn btn-default"><i class="fa fa-arrow-left"></i> <?php echo $button_back; ?></button>
                </div>
                <div class="col-sm-6 text-right">
                  <button type="button" id="button-payment-address" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary"><i class="fa fa-arrow-right"></i> <?php echo $button_continue; ?></button>
                </div>
              </div>
            </div>
            <div class="tab-pane" id="tab-total">
              <div class="form-group">
                <label class="col-sm-3 control-label"><?php echo $entry_event_date; ?></label>
                <div class="col-sm-9" id="static-event-date">
                  <p class="form-control-static">-</p>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label"><?php echo $entry_slot; ?></label>
                <div class="col-sm-9" id="static-slot">
                  <p class="form-control-static">-</p>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label" for="input-ceremony"><?php echo $entry_ceremony; ?></label>
                <div class="col-sm-9" id="static-ceremony">
                  <p class="form-control-static">-</p>
                </div>
              </div>
              <div class="table-responsive">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <td class="text-left"><?php echo $column_product_type; ?></td>
                      <td class="text-left"><?php echo $column_product; ?></td>
                      <td class="text-left"><?php echo $column_model; ?></td>
                      <td class="text-right"><?php echo $column_quantity; ?></td>
                      <td class="text-left"><?php echo $column_unit_class; ?></td>
                      <td class="text-right"><?php echo $column_price; ?></td>
                      <td class="text-right"><?php echo $column_total; ?></td>
                    </tr>
                  </thead>
                  <tbody id="total">
                    <tr>
                      <td class="text-center" colspan="7"><?php echo $text_no_results; ?></td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <fieldset>
                <legend><?php echo $text_order_detail; ?></legend>
                <div class="form-group required">
                  <label class="col-sm-2 control-label" for="input-payment-method"><?php echo $entry_payment_method; ?></label>
                  <div class="col-sm-10">
                    <div class="input-group">
                      <select name="payment_method" id="input-payment-method" class="form-control">
                        <option value=""><?php echo $text_select; ?></option>
                        <?php if ($payment_code) { ?>
                        <option value="<?php echo $payment_code; ?>" selected="selected"><?php echo $payment_method; ?></option>
                        <?php } ?>
                      </select>
                      <span class="input-group-btn">
                      <button type="button" id="button-payment-method" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary"><?php echo $button_apply; ?></button>
                      </span></div>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-marketing-budget"><?php echo $entry_marketing_budget; ?></label>
                  <div class="col-sm-10">
                    <div class="input-group">
                      <input type="text" name="marketing_budget" value="<?php echo $marketing_budget; ?>" id="input-marketing-budget" class="form-control" />
                      <span class="input-group-btn">
                      <button type="button" id="button-marketing-budget" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary"><?php echo $button_apply; ?></button>
                      </span></div>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-coupon"><?php echo $entry_coupon; ?></label>
                  <div class="col-sm-10">
                    <div class="input-group">
                      <input type="text" name="coupon" value="<?php echo $coupon; ?>" id="input-coupon" class="form-control" />
                      <span class="input-group-btn">
                      <button type="button" id="button-coupon" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary"><?php echo $button_apply; ?></button>
                      </span></div>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-reward"><?php echo $entry_reward; ?></label>
                  <div class="col-sm-10">
                    <div class="input-group">
                      <input type="text" name="reward" value="<?php echo $reward; ?>" id="input-reward" data-loading-text="<?php echo $text_loading; ?>" class="form-control" />
                      <span class="input-group-btn">
                      <button type="button" id="button-reward" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary"><?php echo $button_apply; ?></button>
                      </span></div>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-order-status"><?php echo $entry_order_status; ?></label>
                  <div class="col-sm-10">
                    <select name="order_status_id" id="input-order-status" class="form-control">
                      <?php foreach ($order_statuses as $order_status) { ?>
                      <?php if ($order_status['order_status_id'] == $order_status_id) { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                      <?php } else { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                      <?php } ?>
                      <?php } ?>
                    </select>
                    <input type="hidden" name="order_id" value="<?php echo $order_id; ?>" />
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-comment"><?php echo $entry_comment; ?></label>
                  <div class="col-sm-10">
                    <textarea name="comment" rows="5" id="input-comment" class="form-control"><?php echo $comment; ?></textarea>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-affiliate"><?php echo $entry_affiliate; ?></label>
                  <div class="col-sm-10">
                    <input type="text" name="affiliate" value="<?php echo $affiliate; ?>" id="input-affiliate" class="form-control" />
                    <input type="hidden" name="affiliate_id" value="<?php echo $affiliate_id; ?>" />
                  </div>
                </div>
              </fieldset>
              <div class="row">
                <div class="col-sm-6 text-left">
                  <button type="button" onclick="$('a[href=\'#tab-payment\']').tab('show');" class="btn btn-default"><i class="fa fa-arrow-left"></i> <?php echo $button_back; ?></button>
                </div>
                <div class="col-sm-6 text-right">
                  <button type="button" id="button-refresh" data-toggle="tooltip" title="<?php echo $button_refresh; ?>" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-warning"><i class="fa fa-refresh"></i></button>
                  <button type="button" id="button-save" class="btn btn-primary"><i class="fa fa-check-circle"></i> <?php echo $button_save; ?></button>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
// Disable the tabs
$('#order a[data-toggle=\'tab\']').on('click', function(e) {
	return false;
});

var products = [];

$('select[name=\'primary_type\']').on('change', function() {
	var primary_type = $('select[name=\'primary_type\']').val();
	
	$.ajax({
		url: 'index.php?route=sale/order/productSelect&token=<?php echo $token; ?>&primary_type=' + primary_type,
		dataType: 'json',
		beforeSend: function() {
			$('select[name=\'primary_type\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
		},
		complete: function() {
			$('.fa-spin').remove();
		},
		success: function(json) {
			html = '<option value=""><?php echo $text_select; ?></option>';

			if (json['categories'] && json['categories'] != '') {
				for (i = 0; i < json['categories'].length; i++) {
					html += '<option value="' + json['categories'][i]['category_id'] + '">' + json['categories'][i]['name'] + '</option>';
				}
			} else {
				html += '<option value="0"><?php echo $text_none; ?></option>';
			}

			$('select[name=\'category_id\']').html(html);
			$('select[name=\'category_id\']').trigger('change');
			$('#price').html('-');
			$('#option').html('');
			
			products = json['products'];
		},
		
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('select[name=\'primary_type\']').trigger('change');

$('select[name=\'category_id\']').on('change', function() {
	var category_id = $('select[name=\'category_id\']').val();
	
	html = '<option value=""><?php echo $text_select; ?></option>';
	
	if (products[category_id] && products[category_id] != '') {
		for (i = 0; i < products[category_id].length; i++) {
			html += '<option value="' + products[category_id][i]['product_id'] + '">' + products[category_id][i]['name'] + '</option>';
		}
	} else {
		html += '<option value="0"><?php echo $text_none; ?></option>';
	}

	$('select[name=\'product_id\']').html(html);
	$('#price').html('-');
	$('#option').html('');
});

$('select[name=\'category_id\']').trigger('change');

$('select[name=\'product_id\']').on('change', function() {
	var product_id = $('select[name=\'product_id\']').val();
	
	$.ajax({
		url: 'index.php?route=sale/order/product&token=<?php echo $token; ?>&product_id=' + product_id,
		dataType: 'json',
		beforeSend: function() {
			$('select[name=\'product_id\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
		},
		complete: function() {
			$('.fa-spin').remove();
		},
		success: function(json) {
			if (json && json != '') {
				$('#price').html(json['price']);
				
				if (json['option'] != '') {
					html  = '<fieldset>';
					html += '  <legend><?php echo $entry_option; ?></legend>';

					for (i = 0; i < json['option'].length; i++) {
						option = json['option'][i];
						
						option['required'] = Number(option['required']);

						if (option['type'] == 'select') {
							html += '<div class="form-group' + (option['required'] ? ' required' : '') + '">';
							html += '  <label class="col-sm-2 control-label" for="input-option' + option['product_option_id'] + '">' + option['name'] + '</label>';
							html += '  <div class="col-sm-10">';
							html += '    <select name="option[' + option['product_option_id'] + ']" id="input-option' + option['product_option_id'] + '" class="form-control">';
							html += '      <option value=""><?php echo $text_select; ?></option>';

							for (j = 0; j < option['product_option_value'].length; j++) {
								option_value = option['product_option_value'][j];

								html += '<option value="' + option_value['product_option_value_id'] + '">' + option_value['name'];

								if (option_value['price']) {
									html += ' (' + option_value['price_prefix'] + option_value['price'] + ')';
								}

								html += '</option>';
							}

							html += '    </select>';
							html += '  </div>';
							html += '</div>';
						}

						if (option['type'] == 'radio') {
							html += '<div class="form-group' + (option['required'] ? ' required' : '') + '">';
							html += '  <label class="col-sm-2 control-label" for="input-option' + option['product_option_id'] + '">' + option['name'] + '</label>';
							html += '  <div class="col-sm-10">';
							html += '    <select name="option[' + option['product_option_id'] + ']" id="input-option' + option['product_option_id'] + '" class="form-control">';
							html += '      <option value=""><?php echo $text_select; ?></option>';

							for (j = 0; j < option['product_option_value'].length; j++) {
								option_value = option['product_option_value'][j];

								html += '<option value="' + option_value['product_option_value_id'] + '">' + option_value['name'];

								if (option_value['price']) {
									html += ' (' + option_value['price_prefix'] + option_value['price'] + ')';
								}

								html += '</option>';
							}

							html += '    </select>';
							html += '  </div>';
							html += '</div>';
						}

						if (option['type'] == 'checkbox') {
							html += '<div class="form-group' + (option['required'] ? ' required' : '') + '">';
							html += '  <label class="col-sm-2 control-label">' + option['name'] + '</label>';
							html += '  <div class="col-sm-10">';
							html += '    <div id="input-option' + option['product_option_id'] + '">';

							for (j = 0; j < option['product_option_value'].length; j++) {
								option_value = option['product_option_value'][j];

								html += '<div class="checkbox">';

								html += '  <label><input type="checkbox" name="option[' + option['product_option_id'] + '][]" value="' + option_value['product_option_value_id'] + '" /> ' + option_value['name'];

								if (option_value['price']) {
									html += ' (' + option_value['price_prefix'] + option_value['price'] + ')';
								}

								html += '  </label>';
								html += '</div>';
							}

							html += '    </div>';
							html += '  </div>';
							html += '</div>';
						}

						if (option['type'] == 'image') {
							html += '<div class="form-group' + (option['required'] ? ' required' : '') + '">';
							html += '  <label class="col-sm-2 control-label" for="input-option' + option['product_option_id'] + '">' + option['name'] + '</label>';
							html += '  <div class="col-sm-10">';
							html += '    <select name="option[' + option['product_option_id'] + ']" id="input-option' + option['product_option_id'] + '" class="form-control">';
							html += '      <option value=""><?php echo $text_select; ?></option>';

							for (j = 0; j < option['product_option_value'].length; j++) {
								option_value = option['product_option_value'][j];

								html += '<option value="' + option_value['product_option_value_id'] + '">' + option_value['name'];

								if (option_value['price']) {
									html += ' (' + option_value['price_prefix'] + option_value['price'] + ')';
								}

								html += '</option>';
							}

							html += '    </select>';
							html += '  </div>';
							html += '</div>';
						}

						if (option['type'] == 'text') {
							html += '<div class="form-group' + (option['required'] ? ' required' : '') + '">';
							html += '  <label class="col-sm-2 control-label" for="input-option' + option['product_option_id'] + '">' + option['name'] + '</label>';
							html += '  <div class="col-sm-10"><input type="text" name="option[' + option['product_option_id'] + ']" value="' + option['value'] + '" id="input-option' + option['product_option_id'] + '" class="form-control" /></div>';
							html += '</div>';
						}

						if (option['type'] == 'textarea') {
							html += '<div class="form-group' + (option['required'] ? ' required' : '') + '">';
							html += '  <label class="col-sm-2 control-label" for="input-option' + option['product_option_id'] + '">' + option['name'] + '</label>';
							html += '  <div class="col-sm-10"><textarea name="option[' + option['product_option_id'] + ']" rows="5" id="input-option' + option['product_option_id'] + '" class="form-control">' + option['value'] + '</textarea></div>';
							html += '</div>';
						}

						if (option['type'] == 'file') {
							html += '<div class="form-group' + (option['required'] ? ' required' : '') + '">';
							html += '  <label class="col-sm-2 control-label">' + option['name'] + '</label>';
							html += '  <div class="col-sm-10">';
							html += '    <button type="button" id="button-upload' + option['product_option_id'] + '" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-default"><i class="fa fa-upload"></i> <?php echo $button_upload; ?></button>';
							html += '    <input type="hidden" name="option[' + option['product_option_id'] + ']" value="' + option['value'] + '" id="input-option' + option['product_option_id'] + '" />';
							html += '  </div>';
							html += '</div>';
						}

						if (option['type'] == 'date') {
							html += '<div class="form-group' + (option['required'] ? ' required' : '') + '">';
							html += '  <label class="col-sm-2 control-label" for="input-option' + option['product_option_id'] + '">' + option['name'] + '</label>';
							html += '  <div class="col-sm-3"><div class="input-group date"><input type="text" name="option[' + option['product_option_id'] + ']" value="' + option['value'] + '" placeholder="' + option['name'] + '" data-date-format="YYYY-MM-DD" id="input-option' + option['product_option_id'] + '" class="form-control" /><span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span></div></div>';
							html += '</div>';
						}

						if (option['type'] == 'datetime') {
							html += '<div class="form-group' + (option['required'] ? ' required' : '') + '">';
							html += '  <label class="col-sm-2 control-label" for="input-option' + option['product_option_id'] + '">' + option['name'] + '</label>';
							html += '  <div class="col-sm-3"><div class="input-group datetime"><input type="text" name="option[' + option['product_option_id'] + ']" value="' + option['value'] + '" placeholder="' + option['name'] + '" data-date-format="YYYY-MM-DD HH:mm" id="input-option' + option['product_option_id'] + '" class="form-control" /><span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span></div></div>';
							html += '</div>';
						}

						if (option['type'] == 'time') {
							html += '<div class="form-group' + (option['required'] ? ' required' : '') + '">';
							html += '  <label class="col-sm-2 control-label" for="input-option' + option['product_option_id'] + '">' + option['name'] + '</label>';
							html += '  <div class="col-sm-3"><div class="input-group time"><input type="text" name="option[' + option['product_option_id'] + ']" value="' + option['value'] + '" placeholder="' + option['name'] + '" data-date-format="HH:mm" id="input-option' + option['product_option_id'] + '" class="form-control" /><span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span></div></div>';
							html += '</div>';
						}
					}

					html += '</fieldset>';

					$('#option').html(html);

					$('.date').datetimepicker({
						pickTime: false
					});

					$('.datetime').datetimepicker({
						pickDate: true,
						pickTime: true
					});

					$('.time').datetimepicker({
						pickDate: false
					});
				} else {
					$('#option').html('');
				}
			}
		},
		
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('select[name=\'product_id\']').trigger('change');
//--></script>
  <script type="text/javascript">
$(document).delegate('#button-ip-add', 'click', function() {
	$.ajax({
		url: 'index.php?route=user/api/addip&token=<?php echo $token; ?>&api_id=<?php echo $api_id; ?>',
		type: 'post',
		data: 'ip=<?php echo $api_ip; ?>',
		dataType: 'json',
		beforeSend: function() {
			$('#button-ip-add').button('loading');
		},
		complete: function() {
			$('#button-ip-add').button('reset');
		},
		success: function(json) {
			$('.alert').remove();

			if (json['error']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
			}

			if (json['success']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

var token = '';

// Login to the API
$.ajax({
	url: '<?php echo $store_url; ?>index.php?route=api/login',
	type: 'post',
	data: 'key=<?php echo $api_key; ?>',
	dataType: 'json',
	crossDomain: true,
	success: function(json) {
        $('.alert').remove();

        if (json['error']) {
    		if (json['error']['key']) {
    			$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['key'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
    		}

            if (json['error']['ip']) {
    			$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['ip'] + ' <button type="button" id="button-ip-add" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-danger btn-xs pull-right"><i class="fa fa-plus"></i> <?php echo $button_ip_add; ?></button></div>');
    		}
        }

		if (json['token']) {
			token = json['token'];

            $('select[name=\'currency\']').trigger('change');
		}
	},
	error: function(xhr, ajaxOptions, thrownError) {
		alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
	}
});

// Currency
$('select[name=\'currency\']').on('change', function() {
	$.ajax({
		url: '<?php echo $store_url; ?>index.php?route=api/currency&token=' + token + '&store_id=' + $('select[name=\'store_id\'] option:selected').val(),
		type: 'post',
		data: 'currency=' + $('select[name=\'currency\'] option:selected').val(),
		dataType: 'json',
		crossDomain: true,
		beforeSend: function() {
			$('select[name=\'currency\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
		},
		complete: function() {
			$('.fa-spin').remove();
		},
		success: function(json) {
			$('.alert, .text-danger').remove();
			$('.form-group').removeClass('has-error');

			if (json['error']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

				// Highlight any found errors
				$('select[name=\'currency\']').parent().parent().addClass('has-error');
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

// Add all products to the cart using the api
$('#button-refresh').on('click', function() {
	$.ajax({
		url: '<?php echo $store_url; ?>index.php?route=api/cart/products&token=' + token + '&store_id=' + $('select[name=\'store_id\'] option:selected').val(),
		dataType: 'json',
		crossDomain: true,
		success: function(json) {
			$('.alert-danger, .text-danger').remove();

			// Check for errors
			if (json['error']) {
				if (json['error']['warning']) {
					$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				}

				if (json['error']['stock']) {
					$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['stock'] + '</div>');
				}

				if (json['error']['minimum']) {
					for (i in json['error']['minimum']) {
						$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['minimum'][i] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
					}
				}
			}

			// var shipping = false;

			html = '';

			if (json['products'].length) {
				for (i = 0; i < json['products'].length; i++) {
					product = json['products'][i];

					html += '<tr>';
					
					if (product['primary_type'] == 1) {
						html += '  <td class="text-left"><?php echo $text_primary_type; ?><br />';
						html += '  <strong>' + product['category'] + '</strong><br />';
					} else {	
						html += '  <td class="text-left">';
						html += product['category'] + '<br />';
					}
					
					html += '  <input type="hidden" name="product[' + i + '][category]" value="' + product['category'] + '" /></td>';
					
					html += '  <td class="text-left">' + product['name'] + ' ' + (!product['stock'] ? '<span class="text-danger">***</span>' : '') + '<br />';
					html += '  <input type="hidden" name="product[' + i + '][product_id]" value="' + product['product_id'] + '" />';

					if (product['option']) {
						html += '<em>';
						for (j = 0; j < product['option'].length; j++) {
							option = product['option'][j];

							html += '  &nbsp;&nbsp;- <small>' + option['name'] + ': ' + option['value'] + '</small><br />';

							if (option['type'] == 'select' || option['type'] == 'radio' || option['type'] == 'image') {
								html += '<input type="hidden" name="product[' + i + '][option][' + option['product_option_id'] + ']" value="' + option['product_option_value_id'] + '" />';
							}

							if (option['type'] == 'checkbox') {
								html += '<input type="hidden" name="product[' + i + '][option][' + option['product_option_id'] + '][]" value="' + option['product_option_value_id'] + '" />';
							}

							if (option['type'] == 'text' || option['type'] == 'textarea' || option['type'] == 'file' || option['type'] == 'date' || option['type'] == 'datetime' || option['type'] == 'time') {
								html += '<input type="hidden" name="product[' + i + '][option][' + option['product_option_id'] + ']" value="' + option['value'] + '" />';
							}
						}
						html += '</em>';
					}

					if (product['attribute']) {
						html += '<em>';
						for (j = 0; j < product['attribute'].length; j++) {
							attribute = product['attribute'][j];

							html += '  &nbsp;&nbsp;- <small>' + attribute + '</small><br />';
						}
						html += '<em>';
					}

					html += '</td>';
					html += '  <td class="text-left">' + product['model'] + '</td>';
					html += '  <td class="text-right"><div class="input-group btn-block" style="max-width: 200px;"><input type="text" name="product[' + i + '][quantity]" value="' + product['quantity'] + '" class="form-control" /><span class="input-group-btn"><button type="button" data-toggle="tooltip" title="<?php echo $button_refresh; ?>" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary"><i class="fa fa-refresh"></i></button></span></div></td>';
					html += '  <td class="text-left">' + product['unit_class'] + '</td>';
                    html += '  <td class="text-right">' + product['price'];
					html += '  <input type="hidden" name="product[' + i + '][price]" value="' + product['base_price'] + '" />';
					html += '  </td>';
					html += '  <td class="text-right">' + product['total'] + '</td>';
					html += '  <td class="text-center" style="width: 3px;"><button type="button" value="' + product['cart_id'] + '" data-toggle="tooltip" title="<?php echo $button_remove; ?>" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
					html += '</tr>';
				}
			}

			if (!json['products'].length) {
				html += '<tr>';
				html += '  <td colspan="8" class="text-center"><?php echo $text_no_results; ?></td>';
				html += '</tr>';
			}

			$('#cart').html(html);

			// Event
			if (json['event'] && json['event'] != '') {
				$('#static-event-date > p').html(json['event']['event_date']);
				$('#static-slot > p').html(json['event']['slot']);
				$('#static-ceremony > p').html(json['event']['ceremony']);
			}
			
			html = '';

			if (json['products'].length) {
				for (i = 0; i < json['products'].length; i++) {
					product = json['products'][i];

					html += '<tr>';
					
					if (product['primary_type'] == 1) {
						html += '  <td class="text-left"><?php echo $text_primary_type; ?><br />';
						html += '  <strong>' + product['category'] + '</strong><br />';
					} else {	
						html += '  <td class="text-left"><br />';
						html += product['category'] + '<br />';
					}
					
					html += '  <input type="hidden" name="product[' + i + '][category]" value="' + product['category'] + '" /></td>';
					
					html += '  <td class="text-left">' + product['name'] + ' ' + (!product['stock'] ? '<span class="text-danger">***</span>' : '') + '<br />';

					if (product['option']) {
						html += '<em>';
						for (j = 0; j < product['option'].length; j++) {
							option = product['option'][j];

							html += '  - <small>' + option['name'] + ': ' + option['value'] + '</small><br />';
						}
						html += '</em>';
					}

					if (product['attribute']) {
						html += '<em>';
						for (j = 0; j < product['attribute'].length; j++) {
							attribute = product['attribute'][j];

							html += '  &nbsp;&nbsp;- <small>' + attribute + '</small><br />';
						}
						html += '<em>';
					}

					html += '  </td>';
					html += '  <td class="text-left">' + product['model'] + '</td>';
					html += '  <td class="text-right">' + product['quantity'] + '</td>';
					html += '  <td class="text-left">' + product['unit_class'] + '</td>';
					html += '  <td class="text-right">' + product['price'] + '</td>';
					html += '  <td class="text-right">' + product['total'] + '</td>';
					html += '</tr>';
				}
			}

			if (json['totals'].length) {
				for (i in json['totals']) {
					total = json['totals'][i];

					html += '<tr>';
					html += '  <td class="text-right" colspan="6">' + total['title'] + ':</td>';
					html += '  <td class="text-right">' + total['text'] + '</td>';
					html += '</tr>';
				}
			}

			if (!json['totals'].length && !json['products'].length) {
				html += '<tr>';
				html += '  <td colspan="7" class="text-center"><?php echo $text_no_results; ?></td>';
				html += '</tr>';
			}

			$('#total').html(html);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

// Customer
$('input[name=\'customer\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=customer/customer/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
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

				response($.map(json, function(item) {
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
	'select': function(item) {
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

		for (i in  item['address']) {
			html += '<option value="' + item['address'][i]['address_id'] + '">' + item['address'][i]['firstname'] + ' ' + item['address'][i]['lastname'] + ', ' + item['address'][i]['address_1'] + ', ' + item['address'][i]['city'] + ', ' + item['address'][i]['country'] + '</option>';
		}

		$('select[name=\'payment_address\']').html(html);

		$('select[name=\'payment_address\']').trigger('change');
	}
});

// Custom Fields
$('select[name=\'customer_group_id\']').on('change', function() {
	$.ajax({
		url: 'index.php?route=customer/customer/customfield&token=<?php echo $token; ?>&customer_group_id=' + this.value,
		dataType: 'json',
		success: function(json) {
			$('.custom-field').hide();
			$('.custom-field').removeClass('required');

			for (i = 0; i < json.length; i++) {
				custom_field = json[i];

				$('.custom-field' + custom_field['custom_field_id']).show();

				if (custom_field['required']) {
					$('.custom-field' + custom_field['custom_field_id']).addClass('required');
				}
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('select[name=\'customer_group_id\']').trigger('change');

$('#button-customer').on('click', function() {
	$.ajax({
		url: '<?php echo $store_url; ?>index.php?route=api/customer&token=' + token + '&store_id=' + $('select[name=\'store_id\'] option:selected').val(),
		type: 'post',
		data: $('#tab-customer input[type=\'text\'], #tab-customer input[type=\'hidden\'], #tab-customer input[type=\'radio\']:checked, #tab-customer input[type=\'checkbox\']:checked, #tab-customer select, #tab-customer textarea'),
		dataType: 'json',
		crossDomain: true,
		beforeSend: function() {
			$('#button-customer').button('loading');
		},
		complete: function() {
			 $('#button-customer').button('reset');
		},
		success: function(json) {
			$('.alert, .text-danger').remove();
			$('.form-group').removeClass('has-error');

			if (json['error']) {
				if (json['error']['warning']) {
					$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				}

				for (i in json['error']) {
					var element = $('#input-' + i.replace('_', '-'));

					if (element.parent().hasClass('input-group')) {
                   		$(element).parent().after('<div class="text-danger">' + json['error'][i] + '</div>');
					} else {
						$(element).after('<div class="text-danger">' + json['error'][i] + '</div>');
					}
				}

				// Highlight any found errors
				$('.text-danger').parentsUntil('.form-group').parent().addClass('has-error');
			} else {
                // Refresh products, vouchers and totals
                var request_1 = $.ajax({
                    url: '<?php echo $store_url; ?>index.php?route=api/cart/add&token=' + token + '&store_id=' + $('select[name=\'store_id\'] option:selected').val(),
                    type: 'post',
                    data: $('#cart input[name^=\'product\'][type=\'text\'], #cart input[name^=\'product\'][type=\'hidden\'], #cart input[name^=\'product\'][type=\'radio\']:checked, #cart input[name^=\'product\'][type=\'checkbox\']:checked, #cart select[name^=\'product\'], #cart textarea[name^=\'product\']'),
                    dataType: 'json',
                    crossDomain: true,
                    beforeSend: function() {
                        $('#button-product-add').button('loading');
                    },
                    complete: function() {
                        $('#button-product-add').button('reset');
                    },
                    success: function(json) {
                        $('.alert, .text-danger').remove();
                        $('.form-group').removeClass('has-error');

                        if (json['error'] && json['error']['warning']) {
                            $('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                        }
            		},
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    }
                });

                request_1.done(function() {
                    $('#button-refresh').trigger('click');

                    $('a[href=\'#tab-cart\']').tab('show');
                });
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('#button-product-add').on('click', function() {
	$.ajax({
		url: '<?php echo $store_url; ?>index.php?route=api/cart/add&token=' + token + '&store_id=' + $('select[name=\'store_id\'] option:selected').val(),
		type: 'post',
		data: $('#field-product select[name=\'primary_type\'], #field-product select[name=\'category_id\'], #field-product select[name=\'product_id\'], #field-product input[name^=\'option\'][type=\'text\'], #field-product input[name^=\'option\'][type=\'hidden\'], #field-product input[name^=\'option\'][type=\'radio\']:checked, #field-product input[name^=\'option\'][type=\'checkbox\']:checked, #field-product select[name^=\'option\'], #field-product textarea[name^=\'option\']'),
		dataType: 'json',
		crossDomain: true,
		beforeSend: function() {
			$('#button-product-add').button('loading');
		},
		complete: function() {
			$('#button-product-add').button('reset');
		},
		success: function(json) {
			$('.alert, .text-danger').remove();
			$('.form-group').removeClass('has-error');

			if (json['error']) {
				if (json['error']['warning']) {
					$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				}

				if (json['error']['option']) {
					for (i in json['error']['option']) {
						var element = $('#input-option' + i.replace('_', '-'));

						if (element.parent().hasClass('input-group')) {
							$(element).parent().after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
						} else {
							$(element).after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
						}
					}
				}

				if (json['error']['store']) {
					$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['store'] + '</div>');
				}

				// Highlight any found errors
				$('.text-danger').parentsUntil('.form-group').parent().addClass('has-error');
			} else {
				if (json['success']) {
					$('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div></div>');
				}
				
				$('select[name=\'category_id\']').trigger('change');	
				
				// Refresh products, vouchers and totals
				$('#button-refresh').trigger('click');
			}
			
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('#cart').on('click', '.btn-danger', function() {
	var node = this;

	$.ajax({
		url: '<?php echo $store_url; ?>index.php?route=api/cart/remove&token=' + token + '&store_id=' + $('select[name=\'store_id\'] option:selected').val(),
		type: 'post',
		data: 'key=' + encodeURIComponent(this.value),
		dataType: 'json',
		crossDomain: true,
		beforeSend: function() {
			$(node).button('loading');
		},
		complete: function() {
			$(node).button('reset');
		},
		success: function(json) {
			$('.alert, .text-danger').remove();

			// Check for errors
			if (json['error']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
			} else {
				// Refresh products, vouchers and totals
				$('#button-refresh').trigger('click');
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('#cart').delegate('.btn-primary', 'click', function() {
    var node = this;

    // Refresh products, vouchers and totals
    $.ajax({
        url: '<?php echo $store_url; ?>index.php?route=api/cart/add&token=' + token + '&store_id=' + $('select[name=\'store_id\'] option:selected').val(),
        type: 'post',
        data: $('#cart input[name^=\'product\'][type=\'text\'], #cart input[name^=\'product\'][type=\'hidden\'], #cart input[name^=\'product\'][type=\'radio\']:checked, #cart input[name^=\'product\'][type=\'checkbox\']:checked, #cart select[name^=\'product\'], #cart textarea[name^=\'product\']'),
        dataType: 'json',
        crossDomain: true,
        beforeSend: function() {
            $(node).button('loading');
        },
        complete: function() {
            $(node).button('reset');
        },
        success: function(json) {
            $('.alert, .text-danger').remove();
            $('.form-group').removeClass('has-error');

            if (json['error'] && json['error']['warning']) {
                $('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
            }

            if (json['success']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
			}
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    }).done(function() {
        $('#button-refresh').trigger('click');
    });
});

$('#button-cart').on('click', function() {
	$.ajax({
		url: '<?php echo $store_url; ?>index.php?route=api/cart/event&token=' + token + '&store_id=' + $('select[name=\'store_id\'] option:selected').val(),
		type: 'post',
		data: $('#tab-cart input[name=\'event_date\'], #tab-cart select[name=\'slot_id\'], #tab-cart select[name=\'ceremony_id\'], input[name=\'order_id\']'),
		dataType: 'json',
		crossDomain: true,
		beforeSend: function() {
			$('#button-cart').button('loading');
		},
		complete: function() {
			$('#button-cart').button('reset');
		},
		success: function(json) {
			$('.alert, .text-danger').remove();
			$('.form-group').removeClass('has-error');

			if (json['error']) {
				if (json['error']['warning']) {
					$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				}

				for (i in json['error']) {
					var element = $('#input-' + i.replace('_', '-'));

					if (element.parent().hasClass('input-group')) {
                   		$(element).parent().after('<div class="text-danger">' + json['error'][i] + '</div>');
					} else {
						$(element).after('<div class="text-danger">' + json['error'][i] + '</div>');
					}
				}

				// Highlight any found errors
				$('.text-danger').parentsUntil('.form-group').parent().addClass('has-error');
			} else {
                // Refresh products, vouchers and totals
                var request_1 = $.ajax({
                    url: '<?php echo $store_url; ?>index.php?route=api/cart/add&token=' + token + '&store_id=' + $('select[name=\'store_id\'] option:selected').val(),
                    type: 'post',
                    data: $('#cart input[name^=\'product\'][type=\'text\'], #cart input[name^=\'product\'][type=\'hidden\'], #cart input[name^=\'product\'][type=\'radio\']:checked, #cart input[name^=\'product\'][type=\'checkbox\']:checked, #cart select[name^=\'product\'], #cart textarea[name^=\'product\']'),
                    dataType: 'json',
                    crossDomain: true,
                    beforeSend: function() {
                        $('#button-product-add').button('loading');
                    },
                    complete: function() {
                        $('#button-product-add').button('reset');
                    },
                    success: function(json) {
                        $('.alert, .text-danger').remove();
                        $('.form-group').removeClass('has-error');

                        if (json['error'] && json['error']['warning']) {
                            $('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                        }
						
						$('#button-refresh').trigger('click');
            		},
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    }
                });

                request_1.done(function() {
					$('a[href=\'#tab-payment\']').tab('show');
                });
			}
		},
		
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

// Payment Address
$('select[name=\'payment_address\']').on('change', function() {
	$.ajax({
		url: 'index.php?route=customer/customer/address&token=<?php echo $token; ?>&address_id=' + this.value,
		dataType: 'json',
		beforeSend: function() {
			$('select[name=\'payment_address\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
		},
		complete: function() {
			$('#tab-payment .fa-spin').remove();
		},
		success: function(json) {
			// Reset all fields
			$('#tab-payment input[type=\'text\'], #tab-payment input[type=\'text\'], #tab-payment textarea').val('');
			$('#tab-payment select option').not('#tab-payment select[name=\'payment_address\']').removeAttr('selected');
			$('#tab-payment input[type=\'checkbox\'], #tab-payment input[type=\'radio\']').removeAttr('checked');

			$('#tab-payment input[name=\'firstname\']').val(json['firstname']);
			$('#tab-payment input[name=\'lastname\']').val(json['lastname']);
			$('#tab-payment input[name=\'company\']').val(json['company']);
			$('#tab-payment input[name=\'profession\']').val(json['profession']);
			$('#tab-payment input[name=\'position\']').val(json['position']);
			$('#tab-payment input[name=\'address_1\']').val(json['address_1']);
			$('#tab-payment input[name=\'address_2\']').val(json['address_2']);
			$('#tab-payment input[name=\'city\']').val(json['city']);
			$('#tab-payment input[name=\'postcode\']').val(json['postcode']);
			$('#tab-payment select[name=\'country_id\']').val(json['country_id']);

			payment_zone_id = json['zone_id'];

			for (i in json['custom_field']) {
				$('#tab-payment select[name=\'custom_field[' + i + ']\']').val(json['custom_field'][i]);
				$('#tab-payment textarea[name=\'custom_field[' + i + ']\']').val(json['custom_field'][i]);
				$('#tab-payment input[name^=\'custom_field[' + i + ']\'][type=\'text\']').val(json['custom_field'][i]);
				$('#tab-payment input[name^=\'custom_field[' + i + ']\'][type=\'hidden\']').val(json['custom_field'][i]);
				$('#tab-payment input[name^=\'custom_field[' + i + ']\'][type=\'radio\'][value=\'' + json['custom_field'][i] + '\']').prop('checked', true);
				$('#tab-payment input[name^=\'custom_field[' + i + ']\'][type=\'checkbox\'][value=\'' + json['custom_field'][i] + '\']').prop('checked', true);

				if (json['custom_field'][i] instanceof Array) {
					for (j = 0; j < json['custom_field'][i].length; j++) {
						$('#tab-payment input[name^=\'custom_field[' + i + ']\'][type=\'checkbox\'][value=\'' + json['custom_field'][i][j] + '\']').prop('checked', true);
					}
				}
			}

			$('#tab-payment select[name=\'country_id\']').trigger('change');
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

var payment_zone_id = '<?php echo $payment_zone_id; ?>';

$('#tab-payment select[name=\'country_id\']').on('change', function() {
	$.ajax({
		url: 'index.php?route=localisation/country/country&token=<?php echo $token; ?>&country_id=' + this.value,
		dataType: 'json',
		beforeSend: function() {
			$('#tab-payment select[name=\'country_id\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
		},
		complete: function() {
			$('#tab-payment .fa-spin').remove();
		},
		success: function(json) {
			if (json['postcode_required'] == '1') {
				$('#tab-payment input[name=\'postcode\']').parent().parent().addClass('required');
			} else {
				$('#tab-payment input[name=\'postcode\']').parent().parent().removeClass('required');
			}

			html = '<option value=""><?php echo $text_select; ?></option>';

			if (json['zone'] && json['zone'] != '') {
				for (i = 0; i < json['zone'].length; i++) {
        			html += '<option value="' + json['zone'][i]['zone_id'] + '"';

					if (json['zone'][i]['zone_id'] == payment_zone_id) {
	      				html += ' selected="selected"';
	    			}

	    			html += '>' + json['zone'][i]['name'] + '</option>';
				}
			} else {
				html += '<option value="0" selected="selected"><?php echo $text_none; ?></option>';
			}

			$('#tab-payment select[name=\'zone_id\']').html(html);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('#tab-payment select[name=\'country_id\']').trigger('change');

$('#button-payment-address').on('click', function() {
	$.ajax({
		url: '<?php echo $store_url; ?>index.php?route=api/payment/address&token=' + token + '&store_id=' + $('select[name=\'store_id\'] option:selected').val(),
		type: 'post',
		data: $('#tab-payment input[type=\'text\'], #tab-payment input[type=\'hidden\'], #tab-payment input[type=\'radio\']:checked, #tab-payment input[type=\'checkbox\']:checked, #tab-payment select, #tab-payment textarea'),
		dataType: 'json',
		crossDomain: true,
		beforeSend: function() {
			$('#button-payment-address').button('loading');
		},
		complete: function() {
			$('#button-payment-address').button('reset');
		},
		success: function(json) {
			$('.alert, .text-danger').remove();
			$('.form-group').removeClass('has-error');

			// Check for errors
			if (json['error']) {
				if (json['error']['warning']) {
					$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				}

				for (i in json['error']) {
					var element = $('#input-payment-' + i.replace('_', '-'));

					if ($(element).parent().hasClass('input-group')) {
						$(element).parent().after('<div class="text-danger">' + json['error'][i] + '</div>');
					} else {
						$(element).after('<div class="text-danger">' + json['error'][i] + '</div>');
					}
				}

				// Highlight any found errors
				$('.text-danger').parentsUntil('.form-group').parent().addClass('has-error');
			} else {
				// Payment Methods
				$.ajax({
					url: '<?php echo $store_url; ?>index.php?route=api/payment/methods&token=' + token + '&store_id=' + $('select[name=\'store_id\'] option:selected').val(),
					dataType: 'json',
					crossDomain: true,
					beforeSend: function() {
						$('#button-payment-address i').replaceWith('<i class="fa fa-circle-o-notch fa-spin"></i>');
						$('#button-payment-address').prop('disabled', true);
					},
					complete: function() {
						$('#button-payment-address i').replaceWith('<i class="fa fa-arrow-right"></i>');
						$('#button-payment-address').prop('disabled', false);
					},
					success: function(json) {
						if (json['error']) {
							$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
						} else {
							html = '<option value=""><?php echo $text_select; ?></option>';

							if (json['payment_methods']) {
								for (i in json['payment_methods']) {
									if (json['payment_methods'][i]['code'] == $('select[name=\'payment_method\'] option:selected').val()) {
										html += '<option value="' + json['payment_methods'][i]['code'] + '" selected="selected">' + json['payment_methods'][i]['title'] + '</option>';
									} else {
										html += '<option value="' + json['payment_methods'][i]['code'] + '">' + json['payment_methods'][i]['title'] + '</option>';
									}
								}
							}

							$('select[name=\'payment_method\']').html(html);
						}
					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				}).done(function() {
                    // Refresh products, vouchers and totals
    				$('#button-refresh').trigger('click');

					$('a[href=\'#tab-total\']').tab('show');
                });
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

// Payment Method
$('#button-payment-method').on('click', function() {
	$.ajax({
		url: '<?php echo $store_url; ?>index.php?route=api/payment/method&token=' + token + '&store_id=' + $('select[name=\'store_id\'] option:selected').val(),
		type: 'post',
		data: 'payment_method=' + $('select[name=\'payment_method\'] option:selected').val(),
		dataType: 'json',
		crossDomain: true,
		beforeSend: function() {
			$('#button-payment-method').button('loading');
		},
		complete: function() {
			$('#button-payment-method').button('reset');
		},
		success: function(json) {
			$('.alert, .text-danger').remove();
			$('.form-group').removeClass('has-error');

			if (json['error']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

				// Highlight any found errors
				$('select[name=\'payment_method\']').parent().parent().parent().addClass('has-error');
			}

			if (json['success']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

				// Refresh products, vouchers and totals
				$('#button-refresh').trigger('click');
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

// Marketing Budget
$('#button-marketing-budget').on('click', function() {
	$.ajax({
		url: '<?php echo $store_url; ?>index.php?route=api/marketing_budget&token=' + token + '&store_id=' + $('select[name=\'store_id\'] option:selected').val(),
		type: 'post',
		data: 'marketing_budget=' + $('input[name=\'marketing_budget\']').val(),
		dataType: 'json',
		crossDomain: true,
		beforeSend: function() {
			$('#button-marketing-budget').button('loading');
		},
		complete: function() {
			$('#button-marketing-budget').button('reset');
		},
		success: function(json) {
			$('.alert, .text-danger').remove();
			$('.form-group').removeClass('has-error');

			if (json['error']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

				// Highlight any found errors
				$('input[name=\'marketing_budget\']').parent().parent().parent().addClass('has-error');
			}

			if (json['success']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

				// Refresh products, vouchers and totals
				$('#button-refresh').trigger('click');
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

// Coupon
$('#button-coupon').on('click', function() {
	$.ajax({
		url: '<?php echo $store_url; ?>index.php?route=api/coupon&token=' + token + '&store_id=' + $('select[name=\'store_id\'] option:selected').val(),
		type: 'post',
		data: 'coupon=' + $('input[name=\'coupon\']').val(),
		dataType: 'json',
		crossDomain: true,
		beforeSend: function() {
			$('#button-coupon').button('loading');
		},
		complete: function() {
			$('#button-coupon').button('reset');
		},
		success: function(json) {
			$('.alert, .text-danger').remove();
			$('.form-group').removeClass('has-error');

			if (json['error']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

				// Highlight any found errors
				$('input[name=\'coupon\']').parent().parent().parent().addClass('has-error');
			}

			if (json['success']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

				// Refresh products, vouchers and totals
				$('#button-refresh').trigger('click');
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

// Reward
$('#button-reward').on('click', function() {
	$.ajax({
		url: '<?php echo $store_url; ?>index.php?route=api/reward&token=' + token + '&store_id=' + $('select[name=\'store_id\'] option:selected').val(),
		type: 'post',
		data: 'reward=' + $('input[name=\'reward\']').val(),
		dataType: 'json',
		crossDomain: true,
		beforeSend: function() {
			$('#button-reward').button('loading');
		},
		complete: function() {
			$('#button-reward').button('reset');
		},
		success: function(json) {
			$('.alert, .text-danger').remove();
			$('.form-group').removeClass('has-error');

			if (json['error']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

				// Highlight any found errors
				$('input[name=\'reward\']').parent().parent().parent().addClass('has-error');
			}

			if (json['success']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

				// Refresh products, vouchers and totals
				$('#button-refresh').trigger('click');
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

// Affiliate
$('input[name=\'affiliate\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=marketing/affiliate/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				json.unshift({
					affiliate_id: 0,
					name: '<?php echo $text_none; ?>'
				});

				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['affiliate_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'affiliate\']').val(item['label']);
		$('input[name=\'affiliate_id\']').val(item['value']);
	}
});

// Checkout
$('#button-save').on('click', function() {
	if ($('input[name=\'order_id\']').val() == 0) {
		var url = '<?php echo $store_url; ?>index.php?route=api/order/add&token=' + token + '&store_id=' + $('select[name=\'store_id\'] option:selected').val();
	} else {
		var url = '<?php echo $store_url; ?>index.php?route=api/order/edit&token=' + token + '&store_id=' + $('select[name=\'store_id\'] option:selected').val() + '&order_id=' + $('input[name=\'order_id\']').val();
	}

	$.ajax({
		url: url,
		type: 'post',
		data: 'payment_method=' + encodeURIComponent($('select[name=\'payment_method\']').val()) + '&order_status_id=' + encodeURIComponent($('#tab-total select[name=\'order_status_id\']').val()) + '&comment=' + encodeURIComponent($('#tab-total textarea[name=\'comment\']').val()) + '&affiliate_id=' + Number($('#tab-total input[name=\'affiliate_id\']').val()) + '&user_id=<?php echo $user_id; ?>',
		dataType: 'json',
		crossDomain: true,
		beforeSend: function() {
			$('#button-save').button('loading');
		},
		complete: function() {
			$('#button-save').button('reset');
		},
		success: function(json) {
			$('.alert, .text-danger').remove();

			if (json['error']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
			}

			if (json['success']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '  <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

				if (json['order_id']) {
					order_id = json['order_id'];
				} else {
					order_id = <?php echo $order_id; ?>;
				}
			
				location = 'index.php?route=sale/order/info&token=<?php echo $token; ?>&order_id=' + order_id + '<?php echo $url; ?>';
            }
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('#content').delegate('button[id^=\'button-upload\'], button[id^=\'button-custom-field\'], button[id^=\'button-payment-custom-field\']', 'click', function() {
	var node = this;

	$('#form-upload').remove();

	$('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" /></form>');

	$('#form-upload input[name=\'file\']').trigger('click');

	if (typeof timer != 'undefined') {
    	clearInterval(timer);
	}

	timer = setInterval(function() {
		if ($('#form-upload input[name=\'file\']').val() != '') {
			clearInterval(timer);

			$.ajax({
				url: 'index.php?route=tool/upload/upload&token=<?php echo $token; ?>',
				type: 'post',
				dataType: 'json',
				data: new FormData($('#form-upload')[0]),
				cache: false,
				contentType: false,
				processData: false,
				beforeSend: function() {
					$(node).button('loading');
				},
				complete: function() {
					$(node).button('reset');
				},
				success: function(json) {
					$(node).parent().find('.text-danger').remove();

					if (json['error']) {
						$(node).parent().find('input[type=\'hidden\']').after('<div class="text-danger">' + json['error'] + '</div>');
					}

					if (json['success']) {
						alert(json['success']);
					}

					if (json['code']) {
						$(node).parent().find('input[type=\'hidden\']').attr('value', json['code']);
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		}
	}, 500);
});

$('.date').datetimepicker({
	pickTime: false
});

$('.datetime').datetimepicker({
	pickDate: true,
	pickTime: true
});

$('.time').datetimepicker({
	pickDate: false
});
</script>
  <script type="text/javascript">
// Sort the custom fields
$('#tab-customer .form-group[data-sort]').detach().each(function() {
	if ($(this).attr('data-sort') >= 0 && $(this).attr('data-sort') <= $('#tab-customer .form-group').length) {
		$('#tab-customer .form-group').eq($(this).attr('data-sort')).before(this);
	}

	if ($(this).attr('data-sort') > $('#tab-customer .form-group').length) {
		$('#tab-customer .form-group:last').after(this);
	}

	if ($(this).attr('data-sort') < -$('#tab-customer .form-group').length) {
		$('#tab-customer .form-group:first').before(this);
	}
});

// Sort the custom fields
$('#tab-payment .form-group[data-sort]').detach().each(function() {
	if ($(this).attr('data-sort') >= 0 && $(this).attr('data-sort') <= $('#tab-payment .form-group').length) {
		$('#tab-payment .form-group').eq($(this).attr('data-sort')).before(this);
	}

	if ($(this).attr('data-sort') > $('#tab-payment .form-group').length) {
		$('#tab-payment .form-group:last').after(this);
	}

	if ($(this).attr('data-sort') < -$('#tab-payment .form-group').length) {
		$('#tab-payment .form-group:first').before(this);
	}
});
</script></div>
<?php echo $footer; ?>

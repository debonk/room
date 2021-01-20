<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
	    <a href="<?php echo $document; ?>" target="_blank" class="btn btn-primary"><i class="fa fa-file-text-o"></i> <?php echo $button_document; ?></a>
		<div class="btn-group">
		  <button type="button" data-toggle="dropdown" class="btn btn-primary dropdown-toggle" id="button-agreement"><i class="fa fa-file-o"></i> <?php echo $button_agreement; ?></button>
		  <ul class="dropdown-menu dropdown-menu-left">
			<li><a href="<?php echo $agreement_preview; ?>" target="_blank"><?php echo $text_preview; ?></a></li>
			<?php if (!$printed) { ?>
			<li><a href="<?php echo $agreement_print; ?>" target="_blank" id="agreement-print"><?php echo $text_print; ?></a></li>
			<?php } ?>
		  </ul>
		</div>
		<a href="<?php echo $edit; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
		<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
	  </div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
	<?php if ($information) { ?>
	<?php if ($auto_expired) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $information; ?><button type="button" id="button-expired" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-danger btn-xs pull-right"><i class="fa fa-eraser"></i> <?php echo $button_expired; ?></button></div>
	<?php } else { ?>
    <div class="alert alert-warning"><i class="fa fa-exclamation-circle"></i> <?php echo $information; ?></div>
	<?php } ?>
	<?php } ?>
    <div class="row">
      <div class="col-md-4">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-shopping-cart"></i> <?php echo $text_order_detail; ?></h3>
          </div>
          <table class="table">
            <tbody>
              <tr>
                <td style="width: 1%;"><button data-toggle="tooltip" title="<?php echo $text_event_date; ?>" class="btn btn-info btn-xs"><i class="fa fa-calendar fa-fw"></i></button></td>
                <td><?php echo $event_date; ?></td>
              </tr>
              <tr>
                <td><button data-toggle="tooltip" title="<?php echo $text_slot; ?>" class="btn btn-info btn-xs"><i class="fa fa-clock-o fa-fw"></i></button></td>
                <td><?php echo $slot; ?></td>
              </tr>
              <tr>
                <td><button data-toggle="tooltip" title="<?php echo $text_ceremony; ?>" class="btn btn-info btn-xs"><i class="fa fa-birthday-cake fa-fw"></i></button></td>
                <td><?php echo $ceremony; ?></td>
              </tr>
              <tr>
                <td><button data-toggle="tooltip" title="<?php echo $text_date_added; ?>" class="btn btn-info btn-xs"><i class="fa fa-calendar-plus-o fa-fw"></i></button></td>
                <td><?php echo $date_added; ?></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="col-md-4">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-pie-chart"></i> <?php echo $text_vendor; ?></h3>
          </div>
          <table class="table" id="order-vendors">
            <tbody>
			  <?php foreach ($order_vendors as $order_vendor) { ?>
              <tr>
                <td><?php echo $order_vendor['title']; ?></td>
				<td class="text-right nowrap">
                  <button data-toggle="tooltip" title="<?php echo $button_vendor_purchase; ?>" class="btn btn-success btn-xs" id="vendor-purchase<?php echo $order_vendor['vendor_id']; ?>" value="<?php echo $order_vendor['vendor_id']; ?>"><i class="fa fa-shopping-cart fa-fw"></i></button>
                  <a href="<?php echo $order_vendor['agreement_href']; ?>" target="_blank" data-toggle="tooltip" title="<?php echo $button_vendor_agreement; ?>" class="<?php echo $order_vendor['agreement_printed']; ?>-agreement btn btn-success btn-xs" id="vendor-agreement<?php echo $order_vendor['vendor_id']; ?>" value="<?php echo $order_vendor['vendor_id']; ?>"><i class="fa fa-paperclip fa-fw"></i></a>
				  <?php if ($order_vendor['admission_href']) { ?>
                  <a href="<?php echo $order_vendor['admission_href']; ?>" target="_blank" data-toggle="tooltip" title="<?php echo $button_admission; ?>" class="<?php echo $order_vendor['admission_printed']; ?>-admission btn btn-success btn-xs" id="vendor-admission<?php echo $order_vendor['vendor_id']; ?>" value="<?php echo $order_vendor['vendor_id']; ?>"><i class="fa fa-file-text-o fa-fw"></i></a>
				  <?php } else { ?>
                  <button data-toggle="tooltip" title="<?php echo $button_vendor_remove; ?>" class="btn btn-danger btn-xs" id="vendor-remove<?php echo $order_vendor['vendor_id']; ?>" value="<?php echo $order_vendor['vendor_id']; ?>"><i class="fa fa-minus-circle fa-fw"></i></button>
				  <?php } ?>
				</td>
              </tr>
			  <?php } ?>
            </tbody>
            <tfoot>
              <tr>
                <td colspan="2">
				  <div class="btn-group pull-right" id="order-vendors-add">
				    <button type="button" data-toggle="dropdown" class="btn btn-primary dropdown-toggle"><i class="fa fa-plus-circle"></i> <?php echo $button_vendor_add; ?></button>
				    <ul class="dropdown-menu dropdown-menu-right">
					  <li class="dropdown-header"><?php echo $text_vendor; ?></li>
				  	  <?php foreach ($vendors as $vendor) { ?>
				  	  <li><a href="" value="<?php echo $vendor['vendor_id']; ?>" id="vendor-add<?php echo $vendor['vendor_id']; ?>"><?php echo $vendor['title']; ?></a></li>
				  	  <?php } ?>
				    </ul>
				  </div>
                </td>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
      <div class="col-md-4">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-cog"></i> <?php echo $text_option; ?></h3>
          </div>
          <table class="table">
            <tbody>
              <tr>
                <td><?php echo $text_invoice; ?></td>
                <td id="invoice" class="text-right"><?php echo $invoice_no; ?></td>
                <td style="width: 1%;" class="text-center"><?php if (!$invoice_no) { ?>
                  <button id="button-invoice" data-loading-text="<?php echo $text_loading; ?>" data-toggle="tooltip" title="<?php echo $button_generate; ?>" class="btn btn-success btn-xs"><i class="fa fa-refresh"></i></button>
                  <?php } else { ?>
                  <button disabled="disabled" class="btn btn-success btn-xs"><i class="fa fa-refresh"></i></button>
                  <?php } ?></td>
              </tr>
              <tr>
                <td><?php echo $text_reward; ?></td>
                <td class="text-right"><?php echo $reward; ?></td>
                <td class="text-center"><?php if ($customer && $reward) { ?>
                  <?php if (!$reward_total) { ?>
                  <button id="button-reward-add" data-loading-text="<?php echo $text_loading; ?>" data-toggle="tooltip" title="<?php echo $button_reward_add; ?>" class="btn btn-success btn-xs"><i class="fa fa-plus-circle"></i></button>
                  <?php } else { ?>
                  <button id="button-reward-remove" data-loading-text="<?php echo $text_loading; ?>" data-toggle="tooltip" title="<?php echo $button_reward_remove; ?>" class="btn btn-danger btn-xs"><i class="fa fa-minus-circle"></i></button>
                  <?php } ?>
                  <?php } else { ?>
                  <button disabled="disabled" class="btn btn-success btn-xs"><i class="fa fa-plus-circle"></i></button>
                  <?php } ?></td>
              </tr>
              <tr>
                <td><?php echo $text_affiliate; ?>
                  <?php if ($affiliate) { ?>
                  (<a href="<?php echo $affiliate; ?>"><?php echo $affiliate_firstname; ?> <?php echo $affiliate_lastname; ?></a>)
                  <?php } ?></td>
                <td class="text-right"><?php echo $commission; ?></td>
                <td class="text-center"><?php if ($affiliate) { ?>
                  <?php if (!$commission_total) { ?>
                  <button id="button-commission-add" data-loading-text="<?php echo $text_loading; ?>" data-toggle="tooltip" title="<?php echo $button_commission_add; ?>" class="btn btn-success btn-xs"><i class="fa fa-plus-circle"></i></button>
                  <?php } else { ?>
                  <button id="button-commission-remove" data-loading-text="<?php echo $text_loading; ?>" data-toggle="tooltip" title="<?php echo $button_commission_remove; ?>" class="btn btn-danger btn-xs"><i class="fa fa-minus-circle"></i></button>
                  <?php } ?>
                  <?php } else { ?>
                  <button disabled="disabled" class="btn btn-success btn-xs"><i class="fa fa-plus-circle"></i></button>
                  <?php } ?></td>
              </tr>
              <tr>
                <td><?php echo $text_username; ?></td>
                <td class="text-right"><?php echo $username; ?></td>
				<td></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-info-circle"></i> <?php echo $text_order; ?></h3>
      </div>
      <div class="panel-body">
        <table class="table table-bordered text-left">
          <thead>
            <tr>
              <td style="width: 50%;" colspan="2"><?php echo $text_customer_detail; ?></td>
              <td><?php echo $text_payment_address; ?></td>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><?php echo $text_customer; ?></td>
              <td><?php if ($customer) { ?>
                <a href="<?php echo $customer; ?>" target="_blank"><?php echo $firstname . ' ' . $lastname; ?></a><?php echo ' - ' . $customer_group; ?>
                <?php } else { ?>
                <?php echo $firstname . ' ' . $lastname . ' - ' . $customer_group; ?>
                <?php } ?></td>
              <td rowspan="4"><?php echo $payment_address; ?></td>
            </tr>
            <tr>
              <td><?php echo $text_email; ?></td>
              <td><a href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a></td>
            </tr>
            <tr>
              <td><?php echo $text_telephone; ?></td>
              <td><?php echo $telephone; ?></td>
            </tr>
            <tr>
              <td><?php echo $text_payment_method; ?></td>
              <td><?php echo $payment_method; ?></td>
            </tr>
          </tbody>
        </table>
		<div class="table-responsive">
        <table class="table table-bordered" id="order-products">
          <thead>
            <tr>
              <td class="text-left"><?php echo $column_product_type; ?></td>
              <td class="text-left"><?php echo $column_product; ?></td>
              <td class="text-left"><?php echo $column_model; ?></td>
              <td class="text-right"><?php echo $column_quantity; ?></td>
              <td class="text-right"><?php echo $column_price; ?></td>
              <td class="text-right"><?php echo $column_total; ?></td>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($products as $product) { ?>
            <tr>
              <td class="text-left">
                <?php if ($product['primary_type']) { ?>
                <?= $text_primary_type; ?><br />
                <strong>
                  <?php echo $product['category']; ?>
                </strong>
                <?php } else { ?>
                <?php echo $product['category']; ?>
                <?php } ?>
              </td>
              <td class="text-left"><a href="<?php echo $product['href']; ?>">
                  <?php echo $product['name']; ?>
                </a>
                <?php foreach ($product['option'] as $option) { ?>
                <br />
                <?php if ($option['type'] != 'file') { ?>
                &nbsp;<small>&nbsp;-&nbsp;
                  <?php echo $option['name']; ?>:
                  <?php echo $option['value']; ?>
                </small>
                <?php } else { ?>
                &nbsp;<small>&nbsp;-&nbsp;
                  <?php echo $option['name']; ?>: <a href="<?php echo $option['href']; ?>">
                    <?php echo $option['value']; ?>
                  </a>
                </small>
                <?php } ?>
                <?php } ?>
                <?php foreach ($product['attribute'] as $attribute_group => $attributes) { ?>
                <br />
                <small>
                  <?php echo $attribute_group; ?>
                </small>
                <?php foreach ($attributes as $attribute) { ?>
                <br />
                &nbsp;<small>&nbsp;-&nbsp;
                  <?php echo $attribute['name']; ?>:
                  <?php echo $attribute['value']; ?>
                </small>
                <?php } ?>
                <?php } ?>
              </td>
              <td class="text-left">
                <?php echo $product['model']; ?>
              </td>
              <td class="text-right">
                <?php echo $product['quantity']; ?>
              </td>
              <td class="text-right">
                <?php echo $product['price']; ?>
              </td>
              <td class="text-right">
                <?php echo $product['total']; ?>
              </td>
            </tr>
            <?php } ?>
            <?php foreach ($totals as $total) { ?>
            <tr>
              <td colspan="5" class="text-right">
			    <?php if ($total['receipt']) { ?>
				<a href="<?php echo $total['receipt']; ?>" data-toggle="tooltip" title="<?php echo $button_receipt; ?>" target="_blank" class="<?php echo $total['print']; ?>-receipt"><?php echo $total['title']; ?> <i class="fa fa-external-link"></i></a>
			    <?php } else { ?>
				<?php echo $total['title']; ?>
			    <?php } ?>
			  </td>
              <td class="text-right"><?php echo $total['text']; ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
		</div>
        <?php if ($comment) { ?>
        <table class="table table-bordered">
          <thead>
            <tr>
              <td><?php echo $text_comment; ?></td>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><?php echo $comment; ?></td>
            </tr>
          </tbody>
        </table>
        <?php } ?>
        <table class="table table-bordered">
          <thead>
            <tr>
              <td class="text-left"><?php echo $column_phase_title; ?></td>
              <td class="text-right"><?php echo $column_phase_amount; ?></td>
              <td class="text-left"><?php echo $column_phase_limit_date; ?></td>
              <td class="text-center"><?php echo $column_phase_status; ?></td>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($payment_phases as $payment_phase) { ?>
            <tr>
              <td class="text-left"><?php echo $payment_phase['title']; ?></td>
              <td class="text-right"><?php echo $payment_phase['amount']; ?></td>
              <td class="text-left"><?php echo $payment_phase['limit_date']; ?></td>
              <td class="text-center"><?php echo $payment_phase['status'] ? '&check;' : '?'; ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-comment-o"></i> <?php echo $text_history; ?></h3>
      </div>
      <div class="panel-body">
        <ul class="nav nav-tabs">
          <li class="active"><a href="#tab-history" data-toggle="tab"><?php echo $tab_history; ?></a></li>
          <li><a href="#tab-vendor" data-toggle="tab"><?php echo $tab_vendor; ?></a></li>
          <li><a href="#tab-additional" data-toggle="tab"><?php echo $tab_additional; ?></a></li>
          <?php foreach ($tabs as $tab) { ?>
          <li><a href="#tab-<?php echo $tab['code']; ?>" data-toggle="tab"><?php echo $tab['title']; ?></a></li>
          <?php } ?>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="tab-history">
            <div id="history"></div>
            <br />
            <fieldset>
              <legend><?php echo $text_history_add; ?></legend>
              <form class="form-horizontal">
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
                  </div>
                </div>
                <div class="form-group transaction">
                  <label class="col-sm-2 control-label" for="input-date"><?php echo $entry_date; ?></label>
                  <div class="col-sm-10">
                    <div class="input-group date">
                      <input type="text" name="date" value="" placeholder="<?php echo $entry_date; ?>" data-date-format="YYYY-MM-DD" id="input-date" class="form-control" />
                      <span class="input-group-btn">
                      <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                      </span></div>
                  </div>
                </div>
			    <div class="form-group transaction">
				  <label class="col-sm-2 control-label" for="input-amount"><?php echo $entry_amount; ?></label>
				  <div class="col-sm-10">
				    <input type="text" name="amount" value="" placeholder="<?php echo $entry_amount; ?>" class="form-control" id="input-amount" />
				  </div>
			    </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-override"><span data-toggle="tooltip" title="<?php echo $help_override; ?>"><?php echo $entry_override; ?></span></label>
                  <div class="col-sm-10">
                    <input type="checkbox" name="override" value="1" id="input-override" />
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-notify"><?php echo $entry_notify; ?></label>
                  <div class="col-sm-10">
                    <input type="checkbox" name="notify" value="1" id="input-notify" />
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-comment"><?php echo $entry_comment; ?></label>
                  <div class="col-sm-10">
                    <textarea name="comment" rows="8" id="input-comment" class="form-control"></textarea>
                  </div>
                </div>
              </form>
            </fieldset>
            <div class="text-right">
              <button id="button-history" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i> <?php echo $button_history_add; ?></button>
            </div>
          </div>
          <div class="tab-pane" id="tab-vendor">
            <div id="vendor-transaction"></div>
          </div>
          <div class="tab-pane" id="tab-additional">
            <?php if ($account_custom_fields) { ?>
            <table class="table table-bordered">
              <thead>
                <tr>
                  <td colspan="2"><?php echo $text_account_custom_field; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($account_custom_fields as $custom_field) { ?>
                <tr>
                  <td><?php echo $custom_field['name']; ?></td>
                  <td><?php echo $custom_field['value']; ?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
            <?php } ?>
            <?php if ($payment_custom_fields) { ?>
            <table class="table table-bordered">
              <thead>
                <tr>
                  <td colspan="2"><?php echo $text_payment_custom_field; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($payment_custom_fields as $custom_field) { ?>
                <tr>
                  <td><?php echo $custom_field['name']; ?></td>
                  <td><?php echo $custom_field['value']; ?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
            <?php } ?>
            <table class="table table-bordered">
              <thead>
                <tr>
                  <td colspan="2"><?php echo $text_browser; ?></td>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td><?php echo $text_ip; ?></td>
                  <td><?php echo $ip; ?></td>
                </tr>
                <?php if ($forwarded_ip) { ?>
                <tr>
                  <td><?php echo $text_forwarded_ip; ?></td>
                  <td><?php echo $forwarded_ip; ?></td>
                </tr>
                <?php } ?>
                <tr>
                  <td><?php echo $text_user_agent; ?></td>
                  <td><?php echo $user_agent; ?></td>
                </tr>
                <tr>
                  <td><?php echo $text_accept_language; ?></td>
                  <td><?php echo $accept_language; ?></td>
                </tr>
              </tbody>
            </table>
          </div>
          <?php foreach ($tabs as $tab) { ?>
          <div class="tab-pane" id="tab-<?php echo $tab['code']; ?>"><?php echo $tab['content']; ?></div>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript">
$('#order-products, #order-vendors, #vendor-transaction').on('click', 'td a[class^=\'print\']', function(e) {
	e.preventDefault();

	if (confirm('<?php echo $text_print_confirm; ?>')) {
		var url = this.href;

		open(url);
	}
});

$('#agreement-print').on('click', function(e) {
	e.preventDefault();

	if (confirm('<?php echo $text_print_confirm; ?>')) {
		var url = this.href;
		
		this.closest('li').remove();
		open(url);
	}
});

$('#order-vendors-add ul').on('click', 'a[id^=\'vendor-add\']', function(e) {
	e.preventDefault();
	
	var node = this;
	
	vendor_id = encodeURIComponent($(node).attr('value'));

	$.ajax({
		url: 'index.php?route=sale/order/addOrderVendor&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
		type: 'post',
		data: 'vendor_id=' + vendor_id,
		dataType: 'json',
		success: function(json) {
			if (json['error']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
			}

			if (json['title']) {
                html  = '<tr>';
                html += '  <td>' + json['title'] + '</td>';
                html += '  <td class="text-right nowrap">';
                html += '  <button data-toggle="tooltip" title="<?php echo $button_vendor_purchase; ?>" class="btn btn-success btn-xs" id="vendor-purchase' + vendor_id + '" value="' + vendor_id + '"><i class="fa fa-shopping-cart fa-fw"></i></button>';
                html += '  <a href="' + json['agreement_href'] + '" target="_blank" data-toggle="tooltip" title="<?php echo $button_vendor_agreement; ?>" class="print-agreement btn btn-success btn-xs" id="vendor-agreement' + vendor_id + '" value="' + vendor_id + '"><i class="fa fa-paperclip fa-fw"></i></a>';
                html += '  <button data-toggle="tooltip" title="<?php echo $button_vendor_remove; ?>" class="btn btn-danger btn-xs" id="vendor-remove' + vendor_id + '" value="' + vendor_id + '"><i class="fa fa-minus-circle fa-fw"></i></button></td></tr>';

				$('#order-vendors tbody').append(html);
				
				$(node).closest('li').remove();

				$('#vendor-transaction').load('index.php?route=sale/order/vendorTransaction&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>');
			}
		},
		
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('#order-vendors tbody').on('click', 'button[id^=\'vendor-purchase\']', function() {
		var node = this;
		
    vendor_id = encodeURIComponent($(node).attr('value'));
		
		$.ajax({
			url: 'index.php?route=sale/order/purchaseOrder&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
			type: 'post',
			data: 'vendor_id=' + vendor_id,
			dataType: 'json',
			success: function(json) {
        $('.alert').remove();

        if (json['error']) {
					$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
        }
        
				if (json['purchase_url']) {
					open(json['purchase_url']);
				}
			},
			
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
});

$('#order-vendors tbody').on('click', 'button[id^=\'vendor-remove\']', function() {
	if (confirm('<?php echo $text_confirm; ?>')) {
		var node = this;
		
		vendor_id = encodeURIComponent($(node).attr('value'));
		
		$.ajax({
			url: 'index.php?route=sale/order/deleteOrderVendor&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
			type: 'post',
			data: 'vendor_id=' + vendor_id,
			dataType: 'json',
			success: function(json) {
				if (json['error']) {
					$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				}

				if (json['title']) {
					html  = '<li><a href="" value="' + vendor_id + '" id="vendor-add' + vendor_id + '">' + json['title'] + '</a></li>'

					$('#order-vendors-add ul').append(html);
					
					$(node).closest('tr').remove();

					$('#vendor-transaction').load('index.php?route=sale/order/vendorTransaction&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>');
				}
			},
			
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}
});

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

$(document).delegate('#button-invoice', 'click', function() {
	$.ajax({
		url: 'index.php?route=sale/order/createinvoiceno&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
		dataType: 'json',
		beforeSend: function() {
			$('#button-invoice').button('loading');
		},
		complete: function() {
			$('#button-invoice').button('reset');
		},
		success: function(json) {
			$('.alert').remove();

			if (json['error']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
			}

			if (json['invoice_no']) {
				$('#invoice').html(json['invoice_no']);

				$('#button-invoice').replaceWith('<button disabled="disabled" class="btn btn-success btn-xs"><i class="fa fa-cog"></i></button>');
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$(document).delegate('#button-reward-add', 'click', function() {
	$.ajax({
		url: 'index.php?route=sale/order/addreward&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
		type: 'post',
		dataType: 'json',
		beforeSend: function() {
			$('#button-reward-add').button('loading');
		},
		complete: function() {
			$('#button-reward-add').button('reset');
		},
		success: function(json) {
			$('.alert').remove();

			if (json['error']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
			}

			if (json['success']) {
                $('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');

				$('#button-reward-add').replaceWith('<button id="button-reward-remove" data-toggle="tooltip" title="<?php echo $button_reward_remove; ?>" class="btn btn-danger btn-xs"><i class="fa fa-minus-circle"></i></button>');
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$(document).delegate('#button-reward-remove', 'click', function() {
	$.ajax({
		url: 'index.php?route=sale/order/removereward&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
		type: 'post',
		dataType: 'json',
		beforeSend: function() {
			$('#button-reward-remove').button('loading');
		},
		complete: function() {
			$('#button-reward-remove').button('reset');
		},
		success: function(json) {
			$('.alert').remove();

			if (json['error']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
			}

			if (json['success']) {
                $('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');

				$('#button-reward-remove').replaceWith('<button id="button-reward-add" data-toggle="tooltip" title="<?php echo $button_reward_add; ?>" class="btn btn-success btn-xs"><i class="fa fa-plus-circle"></i></button>');
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$(document).delegate('#button-commission-add', 'click', function() {
	$.ajax({
		url: 'index.php?route=sale/order/addcommission&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
		type: 'post',
		dataType: 'json',
		beforeSend: function() {
			$('#button-commission-add').button('loading');
		},
		complete: function() {
			$('#button-commission-add').button('reset');
		},
		success: function(json) {
			$('.alert').remove();

			if (json['error']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
			}

			if (json['success']) {
                $('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');

				$('#button-commission-add').replaceWith('<button id="button-commission-remove" data-toggle="tooltip" title="<?php echo $button_commission_remove; ?>" class="btn btn-danger btn-xs"><i class="fa fa-minus-circle"></i></button>');
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$(document).delegate('#button-commission-remove', 'click', function() {
	$.ajax({
		url: 'index.php?route=sale/order/removecommission&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
		type: 'post',
		dataType: 'json',
		beforeSend: function() {
			$('#button-commission-remove').button('loading');
		},
		complete: function() {
			$('#button-commission-remove').button('reset');
		},
		success: function(json) {
			$('.alert').remove();

			if (json['error']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
			}

			if (json['success']) {
                $('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');

				$('#button-commission-remove').replaceWith('<button id="button-commission-add" data-toggle="tooltip" title="<?php echo $button_commission_add; ?>" class="btn btn-success btn-xs"><i class="fa fa-plus-circle"></i></button>');
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
	dataType: 'json',
	data: 'key=<?php echo $api_key; ?>',
	crossDomain: true,
	success: function(json) {
		// $('.alert').remove();

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
		}
	},
	error: function(xhr, ajaxOptions, thrownError) {
		alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
	}
});

$('#history').delegate('.pagination a', 'click', function(e) {
	e.preventDefault();

	$('#history').load(this.href);
});

$('#history').load('index.php?route=sale/order/history&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>');

$('#vendor-transaction').delegate('.pagination a', 'click', function(e) {
	e.preventDefault();

	$('#vendor-transaction').load(this.href);
});

$('#vendor-transaction').load('index.php?route=sale/order/vendorTransaction&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>');

$('select[name=\'order_status_id\']').on('change', function() {
	var order_status_id = $('select[name=\'order_status_id\']').val();
	
	$.ajax({
		url: 'index.php?route=sale/order/orderStatus&token=<?php echo $token; ?>&order_status_id=' + order_status_id + '&order_id=<?php echo $order_id; ?>',
		dataType: 'json',
		beforeSend: function() {
			$('select[name=\'primary_type\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
		},
		complete: function() {
			$('.fa-spin').remove();
		},
		success: function(json) {
			if (json['transaction']) {
				$('.transaction').slideDown();
			} else {
				$('.transaction').slideUp();
			}

			$('input[name=\'amount\']').val(json['amount']);
		},
		
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('select[name=\'order_status_id\']').trigger('change');

$('#button-history').on('click', function() {
	$.ajax({
		url: '<?php echo $store_url; ?>index.php?route=api/order/history&token=' + token + '&order_id=<?php echo $order_id; ?>',
		type: 'post',
		dataType: 'json',
		data: 'order_status_id=' + encodeURIComponent($('select[name=\'order_status_id\']').val()) + '&date=' + encodeURIComponent($('input[name=\'date\']').val()) + '&amount=' + Number($('input[name=\'amount\']').val()) + '&notify=' + ($('input[name=\'notify\']').prop('checked') ? 1 : 0) + '&override=' + ($('input[name=\'override\']').prop('checked') ? 1 : 0) + '&comment=' + encodeURIComponent($('textarea[name=\'comment\']').val()) + '&user_id=<?php echo $user_id; ?>',
		beforeSend: function() {
			$('#button-history').button('loading');
		},
		complete: function() {
			$('#button-history').button('reset');
		},
		success: function(json) {
			$('.alert').remove();
			$('.text-danger').remove();
			
			if (json['error']) {
				$('#history').before('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
			}

			if (json['error_date']) {
				$('#input-date').parent().after('<div class="text-danger">' + json['error_date'] + '</div>');
			}

			if (json['error_amount']) {
				$('#input-amount').after('<div class="text-danger">' + json['error_amount'] + '</div>');
			}

			if (json['success']) {
				// $('#history').load('index.php?route=sale/order/history&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>');

				// $('#history').before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

				$('input[name=\'date\']').val('');
				$('input[name=\'amount\']').val('');
				$('textarea[name=\'comment\']').val('');
				location.reload();
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('#button-expired').on('click', function() {
	$.ajax({
		url: '<?php echo $store_url; ?>index.php?route=api/order/expired&token=' + token + '&order_id=<?php echo $order_id; ?>',
		type: 'post',
		dataType: 'json',
		data: 'user_id=<?php echo $user_id; ?>',
		beforeSend: function() {
			$('#button-expired').button('loading');
		},
		complete: function() {
			$('#button-expired').button('reset');
		},
		success: function(json) {
			if (json['error']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
			}

			if (json['success']) {
				$('.alert').remove();
				$('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

				$('input[name=\'date\']').val('');
				$('input[name=\'amount\']').val('');
				$('textarea[name=\'comment\']').val('');
				location.reload();
				
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});
</script> 
  <script type="text/javascript">
$('.date').datetimepicker({
	pickTime: false
});
</script></div>
<?php echo $footer; ?> 
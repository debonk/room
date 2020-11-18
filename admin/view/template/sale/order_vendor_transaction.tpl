<legend><?php echo $text_vendor; ?></legend>
<table class="table table-bordered">
  <thead>
    <tr>
      <td class="text-left"><?php echo $column_vendor; ?></td>
      <td class="text-left"><?php echo $column_telephone; ?></td>
      <td class="text-left"><?php echo $column_email; ?></td>
      <td class="text-right"><?php echo $column_vendor_total; ?></td>
    </tr>
  </thead>
  <tbody>
    <?php if ($order_vendors_summary) { ?>
    <?php foreach ($order_vendors_summary as $order_vendor_summary) { ?>
    <tr>
      <td class="text-left"><a href="<?php echo $order_vendor_summary['href']; ?>" target="_blank"><?php echo $order_vendor_summary['title']; ?></a></td>
      <td class="text-left"><?php echo $order_vendor_summary['telephone']; ?></td>
      <td class="text-left"><?php echo $order_vendor_summary['email']; ?></td>
      <td class="text-right"><?php echo $order_vendor_summary['total']; ?></td>
    </tr>
    <?php } ?>
    <?php } else { ?>
    <tr>
      <td class="text-center" colspan="6"><?php echo $text_no_results; ?></td>
    </tr>
    <?php } ?>
  </tbody>
</table>
<br />
<legend><?php echo $text_vendor_transaction; ?></legend>
<table class="table table-bordered">
  <thead>
    <tr>
      <td class="text-left"><?php echo $column_date; ?></td>
      <td class="text-left"><?php echo $column_payment_method; ?></td>
      <td class="text-left"><?php echo $column_description; ?></td>
      <td class="text-right"><?php echo $column_amount; ?></td>
      <td class="text-left"><?php echo $column_date_added; ?></td>
      <td class="text-left"><?php echo $column_username; ?></td>
    </tr>
  </thead>
  <tbody>
    <?php if ($vendor_transactions) { ?>
    <?php foreach ($vendor_transactions as $vendor_transaction) { ?>
    <tr>
      <td class="text-left"><?php echo $vendor_transaction['date']; ?></td>
      <td class="text-left"><?php echo $vendor_transaction['payment_method']; ?></td>
      <td class="text-left"><a href="<?php echo $vendor_transaction['receipt']; ?>" data-toggle="tooltip" title="<?php echo $button_receipt; ?>" target="_blank" class="<?php echo $vendor_transaction['print']; ?>-receipt"><?php echo $vendor_transaction['description']; ?> <i class="fa fa-external-link"></i></a></td>
      <td class="text-right"><?php echo $vendor_transaction['amount']; ?></td>
      <td class="text-left"><?php echo $vendor_transaction['date_added']; ?></td>
      <td class="text-left"><?php echo $vendor_transaction['username']; ?></td>
    </tr>
    <?php } ?>
    <?php } else { ?>
    <tr>
      <td class="text-center" colspan="6"><?php echo $text_no_results; ?></td>
    </tr>
    <?php } ?>
  </tbody>
</table>
<div class="row">
  <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
  <div class="col-sm-6 text-right"><?php echo $results; ?></div>
</div>
<br />
<fieldset>
  <legend><?php echo $text_vendor_transaction_add; ?></legend>
  <form class="form-horizontal">
    <div class="form-group required">
      <label class="col-sm-2 control-label" for="input-transaction-vendor"><?php echo $entry_vendor; ?></label>
      <div class="col-sm-10">
        <select name="transaction_vendor_id" id="input-transaction-vendor" class="form-control">
          <option value=""><?php echo $text_select; ?></option>
          <?php foreach ($order_vendors as $order_vendor) { ?>
          <option value="<?php echo $order_vendor['vendor_id']; ?>"><?php echo $order_vendor['title']; ?></option>
          <?php } ?>
        </select>
      </div>
    </div>
    <div class="form-group required">
      <label class="col-sm-2 control-label" for="input-transaction-payment-code"><?php echo $entry_payment_method; ?></label>
      <div class="col-sm-10">
        <select name="transaction_payment_code" id="input-transaction-payment-code" class="form-control">
	    <option value=""><?php echo $text_select; ?></option>
        <?php foreach ($payment_accounts as $payment_code => $payment_account) { ?>
        <option value="<?php echo $payment_code; ?>"><?php echo $payment_account; ?></option>
        <?php } ?>
        </select>
      </div>
    </div>
    <div class="form-group required">
      <label class="col-sm-2 control-label" for="input-transaction-date"><?php echo $entry_date; ?></label>
      <div class="col-sm-10">
        <div class="input-group date">
          <input type="text" name="transaction_date" value="" placeholder="<?php echo $entry_date; ?>" data-date-format="YYYY-MM-DD" id="input-transaction-date" class="form-control" />
          <span class="input-group-btn">
          <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
          </span></div>
      </div>
    </div>
    <div class="form-group required">
      <label class="col-sm-2 control-label" for="input-transaction-description"><?php echo $entry_description; ?></label>
      <div class="col-sm-10">
        <input type="text" name="transaction_description" value=""  placeholder="<?php echo $entry_description; ?>" id="input-transaction-description" class="form-control" />
      </div>
    </div>
    <div class="form-group required">
      <label class="col-sm-2 control-label" for="input-transaction-amount"><span data-toggle="tooltip" title="<?php echo $help_amount; ?>"><?php echo $entry_amount; ?></span></label>
      <div class="col-sm-10">
        <input type="text" name="transaction_amount" value="" placeholder="<?php echo $entry_amount . ' - ' . $help_amount; ?>" id="input-transaction-amount" class="form-control" />
      </div>
    </div>
  </form>
</fieldset>
<div class="text-right">
  <button id="button-transaction" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i> <?php echo $button_transaction_add; ?></button>
</div>
<script type="text/javascript"><!--
$('#button-transaction').on('click', function() {
	var data = {
		transaction_vendor_id: encodeURIComponent($('[name=\'transaction_vendor_id\']').val()),
		transaction_payment_code: $('[name=\'transaction_payment_code\']').val(),
		transaction_date: encodeURIComponent($('[name=\'transaction_date\']').val()),
		transaction_description: $('[name=\'transaction_description\']').val(),
		transaction_amount: encodeURIComponent($('[name=\'transaction_amount\']').val())
		};
	
	$.ajax({
		url: 'index.php?route=sale/order/transaction&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
		type: 'post',
		dataType: 'json',
		data: data,
		beforeSend: function() {
			$('#button-transaction').button('loading');
		},
		complete: function() {
			$('#button-transaction').button('reset');
		},
		success: function(json) {
			$('.alert').remove();
			$('.text-danger').remove();
			
			if (json['error']) {
				$('#vendor-transaction').before('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
			}

			if (json['error_transaction_vendor']) {
				$('#input-transaction-vendor').after('<div class="text-danger">' + json['error_transaction_vendor'] + '</div>');
			}

			if (json['error_transaction_payment_code']) {
				$('#input-transaction-payment-code').after('<div class="text-danger">' + json['error_transaction_payment_code'] + '</div>');
			}

			if (json['error_transaction_date']) {
				$('#input-transaction-date').parent().after('<div class="text-danger">' + json['error_transaction_date'] + '</div>');
			}

			if (json['error_transaction_description']) {
				$('#input-transaction-description').after('<div class="text-danger">' + json['error_transaction_description'] + '</div>');
			}

			if (json['error_transaction_amount']) {
				$('#input-transaction-amount').after('<div class="text-danger">' + json['error_transaction_amount'] + '</div>');
			}

			if (json['success']) {
				$('#vendor-transaction').load('index.php?route=sale/order/vendorTransaction&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>');

				$('#vendor-transaction').before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

				$('input[name^=\'transaction\']').val('');
				
				if (json['admission_href']) {
					$('#vendor-remove' + data["transaction_vendor_id"]).replaceWith('<a href="' + json['admission_href'] + '" target="_blank" data-toggle="tooltip" title="<?php echo $button_admission; ?>" class="btn btn-success btn-xs" id="vendor-admission' + data["transaction_vendor_id"] + '" value="' + data["transaction_vendor_id"] + '"><i class="fa fa-file-text-o fa-fw"></i></a>');
				} else {
					$('#vendor-admission' + data["transaction_vendor_id"]).replaceWith('<button data-toggle="tooltip" title="<?php echo $button_vendor_remove; ?>" class="btn btn-danger btn-xs" id="vendor-remove' + data["transaction_vendor_id"] + '" value="' + data["transaction_vendor_id"] + '"><i class="fa fa-minus-circle fa-fw"></i></button>');
				}
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});
//--></script>
<script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script>
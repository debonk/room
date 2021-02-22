<legend>
	<?= $text_customer; ?>
</legend>
<div class="table-responsive">
	<table class="table table-bordered">
		<thead>
			<tr>
				<td class="text-left">
					<?= $column_transaction_type; ?>
				</td>
				<td class="text-right">
					<?= $column_amount; ?>
				</td>
				<td class="text-right">
					<?= $column_total; ?>
				</td>
				<td class="text-right">
					<?= $column_balance; ?>
				</td>
			</tr>
		</thead>
		<tbody>
			<?php if ($customers_transaction_summary) { ?>
			<?php foreach ($customers_transaction_summary as $transaction_summary) { ?>
			<tr>
				<td class="text-left">
					<?= $transaction_summary['transaction_type']; ?>
				</td>
				<td class="text-right">
					<?= $transaction_summary['amount']; ?>
				</td>
				<td class="text-right">
					<?= $transaction_summary['total']; ?>
				</td>
				<td class="text-right">
					<?= $transaction_summary['balance']; ?>
				</td>
			</tr>
			<?php } ?>
			<?php } else { ?>
			<tr>
				<td class="text-center" colspan="6">
					<?= $text_no_results; ?>
				</td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
</div>
<br />
<legend>
	<?= $text_customer_transaction; ?>
</legend>
<div class="table-responsive">
	<table class="table table-bordered" id="customer-transaction">
		<thead>
			<tr>
				<td class="text-left">
					<?= $column_date; ?>
				</td>
				<td class="text-left">
					<?= $column_transaction_type; ?>
				</td>
				<td class="text-left">
					<?= $column_reference; ?>
				</td>
				<td class="text-left">
					<?= $column_asset; ?>
				</td>
				<td class="text-left">
					<?= $column_description; ?>
				</td>
				<td class="text-right">
					<?= $column_amount; ?>
				</td>
				<td class="text-left">
					<?= $column_date_added; ?>
				</td>
				<td class="text-left">
					<?= $column_username; ?>
				</td>
				<td class="text-right">
					<?= $column_action; ?>
				</td>
			</tr>
		</thead>
		<tbody>
			<?php if ($customer_transactions) { ?>
			<?php foreach ($customer_transactions as $customer_transaction) { ?>
			<tr>
				<td class="text-left">
					<?= $customer_transaction['date']; ?>
				</td>
				<td class="text-left">
					<?= $customer_transaction['transaction_type']; ?>
				</td>
				<td class="text-left">
					<?= $customer_transaction['reference']; ?>
				</td>
				<td class="text-left">
					<?= $customer_transaction['asset']; ?>
				</td>
				<td class="text-left">
					<?= $customer_transaction['description']; ?>
				</td>
				<td class="text-right">
					<?= $customer_transaction['amount']; ?>
				</td>
				<td class="text-left">
					<?= $customer_transaction['date_added']; ?>
				</td>
				<td class="text-left">
					<?= $customer_transaction['username']; ?>
				</td>
				<td class="text-right nowrap">
					<a href="<?= $customer_transaction['receipt']; ?>" target="_blank" class="btn btn-info btn-sm" data-toggle="tooltip" title="<?= $button_view; ?>"><i
							class="fa fa-eye"></i>
					</a>
					<?php if ($customer_transaction['print']) { ?>
					<button type="button" class="btn btn-success btn-sm" disabled="disabled"><i class="fa fa-print"></i></button>
					<?php } else { ?>
					<button type="button" value="<?= $customer_transaction['transaction_id']; ?>"
						id="button-print<?= $customer_transaction['transaction_id']; ?>" data-loading-text="<?= $text_loading; ?>"
						data-toggle="tooltip" data-print="print" title="<?= $button_print; ?>" class="btn btn-success btn-sm"><i
							class="fa fa-print"></i></button>
					<?php } ?>
				</td>
			</tr>
			<?php } ?>
			<?php } else { ?>
			<tr>
				<td class="text-center" colspan="8">
					<?= $text_no_results; ?>
				</td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
</div>
<div class="row">
	<div class="col-sm-6 text-left">
		<?= $pagination; ?>
	</div>
	<div class="col-sm-6 text-right">
		<?= $results; ?>
	</div>
</div>
<br />
<fieldset>
	<legend>
		<?= $text_customer_transaction_add; ?>
	</legend>
	<form class="form-horizontal">
		<div class="form-group required">
			<label class="col-sm-2 control-label" for="input-customer-transaction-date">
				<?= $entry_date; ?>
			</label>
			<div class="col-sm-10">
				<div class="input-group date">
					<input type="text" name="customer_transaction_date" value="" placeholder="<?= $entry_date; ?>"
						data-date-format="YYYY-MM-DD" id="input-customer-transaction-date" class="form-control" />
					<span class="input-group-btn">
						<button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
					</span>
				</div>
			</div>
		</div>
		<div class="form-group required">
			<label class="col-sm-2 control-label" for="input-customer-transaction-type">
				<?= $entry_transaction_type; ?>
			</label>
			<div class="col-sm-10">
				<select name="customer_transaction_type_id" id="input-customer-transaction-type" class="form-control">
					<option value="">
						<?= $text_select; ?>
					</option>
					<?php foreach ($transaction_types as $transaction_type) { ?>
					<option value="<?= $transaction_type['transaction_type_id']; ?>">
						<?= $transaction_type['name']; ?>
					</option>
					<?php } ?>
				</select>
			</div>
		</div>
		<div class="form-group required">
			<label class="col-sm-2 control-label" for="input-customer-transaction-asset">
				<?php echo $entry_asset; ?>
			</label>
			<div class="col-sm-10">
				<select name="customer_transaction_asset_id" id="input-customer-transaction-asset" class="form-control">
					<option value="">
						<?php echo $text_select; ?>
					</option>
					<?php foreach ($assets as $account) { ?>
					<optgroup label="<?php echo $account['text']; ?>">
						<?php if ($account['child']) { ?>
						<?php foreach ($account['child'] as $child) { ?>
						<option value="<?php echo $child['account_id']; ?>">
							<?php echo $child['text']; ?>
						</option>
						<?php } ?>
						<?php } ?>
					</optgroup>
					<?php } ?>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="input-customer-transaction-description">
				<?= $entry_description; ?>
			</label>
			<div class="col-sm-10">
				<input type="text" name="customer_transaction_description" value="" placeholder="<?= $entry_description; ?>"
					id="input-customer-transaction-description" class="form-control" />
			</div>
		</div>
		<div class="form-group required">
			<label class="col-sm-2 control-label" for="input-customer-transaction-amount"><span data-toggle="tooltip"
					title="<?= $help_amount; ?>">
					<?= $entry_amount; ?>
				</span></label>
			<div class="col-sm-10">
				<input type="text" name="customer_transaction_amount" value=""
					placeholder="<?= $entry_amount . ' - ' . $help_amount; ?>" id="input-customer-transaction-amount"
					class="form-control" />
			</div>
		</div>
	</form>
</fieldset>
<div class="text-right">
	<button id="button-customer-transaction" data-loading-text="<?= $text_loading; ?>" class="btn btn-primary"><i
			class="fa fa-plus-circle"></i>
		<?= $button_transaction_add; ?>
	</button>
</div>
<script type="text/javascript">
	$('#customer-transaction button[id^=\'button-print\']').on('click', function (e) {
		const transaction_id = this.value;

		if (confirm('<?= $text_print_confirm; ?>')) {
			open('index.php?route=sale/order/receipt&token=<?= $token; ?>&transaction_id=' + transaction_id + '&print=1');
		}
	});

	$('#button-customer-transaction').on('click', function () {
		let data = {
			customer_transaction_date: encodeURIComponent($('[name=\'customer_transaction_date\']').val()),
			customer_transaction_type_id: encodeURIComponent($('[name=\'customer_transaction_type_id\']').val()),
			customer_transaction_asset_id: encodeURIComponent($('[name=\'customer_transaction_asset_id\']').val()),
			customer_transaction_description: $('[name=\'customer_transaction_description\']').val(),
			customer_transaction_amount: encodeURIComponent($('[name=\'customer_transaction_amount\']').val())
		};

		$.ajax({
			url: 'index.php?route=sale/customer/transaction&token=<?= $token; ?>&order_id=<?= $order_id; ?>',
			type: 'post',
			dataType: 'json',
			data: data,
			beforeSend: function () {
				$('#button-customer-transaction').button('loading');
			},
			complete: function () {
				$('#button-customer-transaction').button('reset');
			},
			success: function (json) {
				$('.alert, .text-danger').remove();
				$('.form-group').removeClass('has-error');

				if (json['error']) {
					if (json['error']['warning']) {
						$('#order-customer').before('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
					}

					if (json['error_customer_transaction']) {
						for (const i in json['error_customer_transaction']) {
							const element = $('#input-customer-transaction-' + i.replace('_', '-'));

							if (element.parent().hasClass('input-group')) {
								$(element).parent().after('<div class="text-danger">' + json['error_customer_transaction'][i] + '</div>');
							} else {
								$(element).after('<div class="text-danger">' + json['error_customer_transaction'][i] + '</div>');
							}
						}

						// Highlight any found errors
						$('.text-danger').parentsUntil('.form-group').parent().addClass('has-error');
					}
				}

				if (json['success']) {
					$('#order-customer').load('index.php?route=sale/customer&token=<?= $token; ?>&order_id=<?= $order_id; ?>');

					$('#order-customer').before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

					$('input[name^=\'customer_transaction\']').val('');

				}
			},
			error: function (xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});
</script>
<script type="text/javascript">
	$('.date').datetimepicker({
		pickTime: false
	});
</script>
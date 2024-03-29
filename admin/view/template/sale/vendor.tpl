<legend>
	<?php echo $text_vendor; ?>
</legend>
<div class="table-responsive">
	<table class="table table-bordered" id="vendor-summary">
		<thead>
			<tr>
				<td class="text-left">
					<?php echo $column_vendor; ?>
				</td>
				<td class="text-right">
					<?php echo $column_debit; ?>
				</td>
				<td class="text-right">
					<?php echo $column_credit; ?>
				</td>
				<td class="text-right">
					<?php echo $column_balance; ?>
				</td>
			</tr>
		</thead>
		<tbody>
			<?php if ($vendor_transaction_summary) { ?>
			<?php foreach ($vendor_transaction_summary as $vendor_id => $transaction_summary) { ?>
			<tr>
				<td class="text-left" colspan="4"><a href="<?php echo $transaction_summary['href']; ?>" target="_blank" rel="noopener noreferrer">
						<?php echo $transaction_summary['title']; ?>
					</a>
					<div class="pull-right">
						<?php foreach ($transaction_summary['document'] as $type => $document) { ?>
						<div class="btn-group">
							<button type="button" data-toggle="dropdown" class="btn btn-info dropdown-toggle btn-sm"
								id="button-<?php echo $type . $vendor_id; ?>" <?php echo $document['status'] ? '' : 'disabled' ; ?> ><i
									class="fa fa-file-text-o"></i>
								<?php echo $document['button_text']; ?>
							</button>
							<ul class="dropdown-menu dropdown-menu-right">
								<li><a href="<?php echo $document['href']; ?>" target="_blank" rel="noopener noreferrer">
										<?php echo $button_view; ?>
									</a></li>
								<?php if (empty($document['printed'])) { ?>
								<li><a href="<?php echo $document['href']; ?>" target="_blank" rel="noopener noreferrer"
										id="button-print-<?php echo $type . $vendor_id; ?>">
										<?php echo $button_print; ?>
									</a></li>
								<?php } ?>
							</ul>
						</div>
						<?php } ?>
					</div>
				</td>
			</tr>
			<?php foreach ($transaction_summary['summary'] as $summary) { ?>
			<tr>
				<td class="text-left">
					<?php echo '&nbsp;&nbsp;- ' . $summary['text_category']; ?>
				</td>
				<td class="text-right">
					<?php echo $summary['debit']; ?>
				</td>
				<td class="text-right">
					<?php echo $summary['credit']; ?>
				</td>
				<td class="text-right">
					<?php echo $summary['balance']; ?>
				</td>
			</tr>
			<?php } ?>
			<?php } ?>
			<?php } else { ?>
			<tr>
				<td class="text-center" colspan="6">
					<?php echo $text_no_results; ?>
				</td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
</div>
<br />
<legend>
	<?php echo $text_vendor_transaction; ?>
</legend>
<div class="table-responsive">
	<table class="table table-bordered" id="vendor-transaction">
		<thead>
			<tr>
				<td class="text-left">
					<?php echo $column_date; ?>
				</td>
				<td class="text-left">
					<?php echo $column_vendor; ?>
				</td>
				<td class="text-left">
					<?php echo $column_transaction_type; ?>
				</td>
				<td class="text-left">
					<?php echo $column_reference; ?>
				</td>
				<td class="text-left">
					<?php echo $column_payment_method; ?>
				</td>
				<td class="text-left">
					<?php echo $column_description; ?>
				</td>
				<td class="text-right">
					<?php echo $column_amount; ?>
				</td>
				<td class="text-left">
					<?php echo $column_date_added; ?>
				</td>
				<td class="text-left">
					<?php echo $column_username; ?>
				</td>
				<td class="text-right">
					<?= $column_action; ?>
				</td>
			</tr>
		</thead>
		<tbody>
			<?php if ($vendor_transactions) { ?>
			<?php foreach ($vendor_transactions as $vendor_transaction) { ?>
			<tr>
				<td class="text-left">
					<?php echo $vendor_transaction['date']; ?>
				</td>
				<td class="text-left">
					<?php echo $vendor_transaction['vendor_name']; ?>
				</td>
				<td class="text-left">
					<?php echo $vendor_transaction['transaction_type']; ?>
				</td>
				<td class="text-left">
					<?php echo $vendor_transaction['reference']; ?>
				</td>
				<td class="text-left">
					<?php echo $vendor_transaction['payment_method']; ?>
				</td>
				<td class="text-left">
					<?php echo $vendor_transaction['description']; ?>
				</td>
				<td class="text-right">
					<?php echo $vendor_transaction['amount']; ?>
				</td>
				<td class="text-left">
					<?php echo $vendor_transaction['date_added']; ?>
				</td>
				<td class="text-left">
					<?php echo $vendor_transaction['username']; ?>
				</td>
				<td class="text-right nowrap">
					<?php if ($vendor_transaction['receipt']) { ?>
					<a href="<?= $vendor_transaction['receipt']; ?>" target="_blank" rel="noopener noreferrer" class="btn btn-info btn-sm"
						data-toggle="tooltip" title="<?= $button_view; ?>"><i class="fa fa-eye"></i>
					</a>
					<?php if ($vendor_transaction['print']) { ?>
					<button type="button" class="btn btn-success btn-sm" disabled="disabled"><i class="fa fa-print"></i></button>
					<?php } else { ?>
					<button type="button" value="<?= $vendor_transaction['transaction_id']; ?>"
						id="button-print<?= $vendor_transaction['transaction_id']; ?>" data-loading-text="<?= $text_loading; ?>"
						data-toggle="tooltip" title="<?= $button_print; ?>" class="btn btn-success btn-sm"><i
							class="fa fa-print"></i></button>
					<?php } ?>
					<?php } ?>
				</td>
			</tr>
			<?php } ?>
			<?php } else { ?>
			<tr>
				<td class="text-center" colspan="10">
					<?php echo $text_no_results; ?>
				</td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
</div>
<div class="row">
	<div class="col-sm-6 text-left">
		<?php echo $pagination; ?>
	</div>
	<div class="col-sm-6 text-right">
		<?php echo $results; ?>
	</div>
</div>
<br />
<fieldset id="form-vendor-transaction">
	<legend>
		<?php echo $text_vendor_transaction_add; ?>
	</legend>
	<form class="form-horizontal">
		<div class="form-group required">
			<label class="col-sm-2 control-label" for="input-vendor-transaction-vendor">
				<?php echo $entry_vendor; ?>
			</label>
			<div class="col-sm-10">
				<select name="vendor_transaction_vendor_id" id="input-vendor-transaction-vendor" class="form-control">
					<option value="">
						<?php echo $text_select; ?>
					</option>
					<?php foreach ($order_vendors as $order_vendor) { ?>
					<option value="<?php echo $order_vendor['vendor_id']; ?>">
						<?php echo $order_vendor['title']; ?>
					</option>
					<?php } ?>
				</select>
			</div>
		</div>
		<div class="form-group required">
			<label class="col-sm-2 control-label" for="input-vendor-transaction-date">
				<?php echo $entry_date; ?>
			</label>
			<div class="col-sm-10">
				<div class="input-group date">
					<input type="text" name="vendor_transaction_date" value="" placeholder="<?php echo $entry_date; ?>"
						data-date-format="YYYY-MM-DD" id="input-vendor-transaction-date" class="form-control" />
					<span class="input-group-btn">
						<button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
					</span>
				</div>
			</div>
		</div>
		<div class="form-group required">
			<label class="col-sm-2 control-label" for="input-vendor-transaction-type">
				<?= $entry_transaction_type; ?>
			</label>
			<div class="col-sm-10">
				<select name="vendor_transaction_type_id" id="input-vendor-transaction-type" class="form-control">
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
		<div class="form-group required" id="input-account-debit">
			<label class="col-sm-2 control-label" for="input-vendor-transaction-account-debit">
				<?php echo $entry_account_debit; ?>
			</label>
			<div class="col-sm-10">
				<select name="vendor_transaction_account_debit_id" id="input-vendor-transaction-account-debit"
					class="form-control">
				</select>
			</div>
		</div>
		<div class="form-group required" id="input-account-credit">
			<label class="col-sm-2 control-label" for="input-vendor-transaction-account-credit">
				<?php echo $entry_account_credit; ?>
			</label>
			<div class="col-sm-10">
				<select name="vendor_transaction_account_credit_id" id="input-vendor-transaction-account-credit"
					class="form-control">
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="input-vendor-transaction-description">
				<?php echo $entry_description; ?>
			</label>
			<div class="col-sm-10">
				<input type="text" name="vendor_transaction_description" value=""
					placeholder="<?php echo $entry_description; ?>" id="input-vendor-transaction-description"
					class="form-control" />
			</div>
		</div>
		<div class="form-group required">
			<label class="col-sm-2 control-label" for="input-vendor-transaction-amount"><span data-toggle="tooltip"
					title="<?php echo $help_amount; ?>">
					<?php echo $entry_amount; ?>
				</span></label>
			<div class="col-sm-10">
				<input type="text" name="vendor_transaction_amount" value=""
					placeholder="<?php echo $entry_amount . ' - ' . $help_amount; ?>" id="input-vendor-transaction-amount"
					class="form-control" />
			</div>
		</div>
	</form>
</fieldset>
<div class="text-right">
	<button id="button-vendor-transaction" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary"><i
			class="fa fa-plus-circle"></i>
		<?php echo $button_transaction_add; ?>
	</button>
</div>
<script type="text/javascript">
	$('select[name=\'vendor_transaction_type_id\']').on('change', function () {
		let account_debit, account_credit, child;
		let transaction_type_id = $('select[name=\'vendor_transaction_type_id\']').val();

		$.ajax({
			url: 'index.php?route=sale/vendor/transactionTypeAccounts&token=<?php echo $token; ?>&transaction_type_id=' + transaction_type_id,
			dataType: 'json',
			beforeSend: function () {
				$('label[for=\'input-vendor-transaction-type\']').append(' <i class="fa fa-circle-o-notch fa-spin"></i>');
			},
			complete: function () {
				$('.fa-spin').remove();
			},
			success: function (json) {
				if (json['lock_debit']) {
					$('#form-vendor-transaction #input-account-debit').slideUp('slow');
					html = '';
				} else {
					$('#form-vendor-transaction #input-account-debit').slideDown('slow');
					html = '<option value=""><?php echo $text_select; ?></option>';
				}

				account_debit = json['account_debit'];

				for (let i in account_debit) {
					html += '	<optgroup label="' + account_debit[i]['text'] + '">';

					if (account_debit[i]['child']) {
						child = account_debit[i]['child'];

						for (let j in child) {
							html += '	  <option value="' + child[j]['account_id'] + '">' + child[j]['text'] + '</option>';
						}
					}

					html += '	</optgroup>';
				}

				$('select[name=\'vendor_transaction_account_debit_id\']').html(html);

				if (json['lock_credit']) {
					$('#form-vendor-transaction #input-account-credit').slideUp('slow');
					html = '';
				} else {
					$('#form-vendor-transaction #input-account-credit').slideDown('slow');
					html = '<option value=""><?php echo $text_select; ?></option>';
				}

				account_credit = json['account_credit'];
				for (let i in account_credit) {
					html += '	<optgroup label="' + account_credit[i]['text'] + '">';

					if (account_credit[i]['child']) {
						child = account_credit[i]['child'];

						for (let j in child) {
							html += '	  <option value="' + child[j]['account_id'] + '">' + child[j]['text'] + '</option>';
						}
					}

					html += '	</optgroup>';
				}

				$('select[name=\'vendor_transaction_account_credit_id\']').html(html);
			},

			error: function (xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});

	$('select[name=\'vendor_transaction_type_id\']').trigger('change');

	$('#vendor-transaction button[id^=\'button-print\']').on('click', function (e) {
		const transaction_id = this.value;

		if (confirm('<?= $text_print_confirm; ?>')) {
			open('index.php?route=sale/order/receipt&token=<?= $token; ?>&transaction_id=' + transaction_id + '&print=1');
		}
	});

	$('#vendor-summary a[id^=\'button-print\']').on('click', function (e) {
		e.preventDefault();

		if (confirm('<?= $text_print_confirm; ?>')) {
			const url = this.href + '&print=1';
			open(url);
		}
	});

	let warning_pos = $('#form-vendor-transaction').position();

	$('#button-vendor-transaction').on('click', function () {
		let data = {
			vendor_transaction_vendor_id: encodeURIComponent($('[name=\'vendor_transaction_vendor_id\']').val()),
			vendor_transaction_date: encodeURIComponent($('[name=\'vendor_transaction_date\']').val()),
			vendor_transaction_type_id: encodeURIComponent($('[name=\'vendor_transaction_type_id\']').val()),
			vendor_transaction_account_debit_id: $('[name=\'vendor_transaction_account_debit_id\']').val(),
			vendor_transaction_account_credit_id: $('[name=\'vendor_transaction_account_credit_id\']').val(),
			vendor_transaction_description: $('[name=\'vendor_transaction_description\']').val(),
			vendor_transaction_amount: encodeURIComponent($('[name=\'vendor_transaction_amount\']').val())
		};

		$.ajax({
			url: 'index.php?route=sale/vendor/transaction&token=<?= $token; ?>&order_id=<?= $order_id; ?>',
			type: 'post',
			dataType: 'json',
			data: data,
			beforeSend: function () {
				$('#button-vendor-transaction').button('loading');
			},
			complete: function () {
				$('#button-vendor-transaction').button('reset');
			},
			success: function (json) {
				$('.alert, .text-danger').remove();
				$('.form-group').removeClass('has-error');

				if (json['error']) {
					if (json['error']['warning']) {
						$('#form-vendor-transaction').before('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
						$('html, body').animate({ scrollTop: warning_pos.top - 70 }, 500);
					}

					if (json['error_vendor_transaction']) {
						for (const i in json['error_vendor_transaction']) {
							const element = $('#input-vendor-transaction-' + i.replace('_', '-'));

							if (element.parent().hasClass('input-group')) {
								$(element).parent().after('<div class="text-danger">' + json['error_vendor_transaction'][i] + '</div>');
							} else {
								$(element).after('<div class="text-danger">' + json['error_vendor_transaction'][i] + '</div>');
							}
						}

						// Highlight any found errors
						$('.text-danger').parentsUntil('.form-group').parent().addClass('has-error');
					}
				}

				if (json['success']) {
					$('#order-vendor').load('index.php?route=sale/vendor&token=<?= $token; ?>&order_id=<?= $order_id; ?>', function () {

						$('#form-vendor-transaction').before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
						$('html, body').animate({ scrollTop: warning_pos.top - 70 }, 500);
					});

					$('input[name^=\'vendor_transaction\']').val('');
				}
			},
			error: function (xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});

	$('.date').datetimepicker({
		pickTime: false
	});
</script>
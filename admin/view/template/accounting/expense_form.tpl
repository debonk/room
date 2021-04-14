<?php echo $header; ?>
<?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button type="submit" form="form-transaction" data-toggle="tooltip" title="<?php echo $button_save; ?>"
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
				<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-transaction"
					class="form-horizontal">
					<div class="form-group">
						<label class="col-sm-2 control-label">
							<?php echo $text_reference; ?>
						</label>
						<div class="col-sm-10">
							<h4 class="form-control-static">
								<?php echo $reference; ?>
							</h4>
						</div>
					</div>
					<div class="form-group required">
						<label class="col-sm-2 control-label" for="input-transaction-type">
							<?php echo $entry_transaction_type; ?>
						</label>
						<div class="col-sm-10">
							<select name="transaction_type_id" id="input-transaction-type" class="form-control">
								<?php foreach ($transaction_types as $transaction_type) { ?>
								<?php if ($transaction_type['transaction_type_id'] == $transaction_type_id) { ?>
								<option value="<?php echo $transaction_type['transaction_type_id']; ?>" selected="selected">
									<?php echo $transaction_type['name']; ?>
								</option>
								<?php } else { ?>
								<option value="<?php echo $transaction_type['transaction_type_id']; ?>">
									<?php echo $transaction_type['name']; ?>
								</option>
								<?php } ?>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="form-group required">
						<label class="col-sm-2 control-label" for="input-account-debit">
							<?php echo $entry_account_debit; ?>
						</label>
						<div class="col-sm-10">
							<select name="account_debit_id" id="input-account-debit" class="form-control">
							</select>
							<?php if ($error_account_debit) { ?>
							<div class="text-danger">
								<?php echo $error_account_debit; ?>
							</div>
							<?php } ?>
						</div>
					</div>
					<div class="form-group required">
						<label class="col-sm-2 control-label" for="input-account-credit">
							<?php echo $entry_account_credit; ?>
						</label>
						<div class="col-sm-10">
							<select name="account_credit_id" id="input-account-credit" class="form-control">
							</select>
							<?php if ($error_account_credit) { ?>
							<div class="text-danger">
								<?php echo $error_account_credit; ?>
							</div>
							<?php } ?>
						</div>
					</div>
					<div class="form-group required">
						<label class="col-sm-2 control-label" for="input-date">
							<?php echo $entry_date; ?>
						</label>
						<div class="col-sm-10">
							<div class="input-group date">
								<input type="text" name="date" value="<?php echo $date; ?>" placeholder="<?php echo $entry_date; ?>"
									data-date-format="YYYY-MM-DD" id="input-date" class="form-control" />
								<span class="input-group-btn">
									<button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
								</span>
							</div>
							<?php if ($error_date) { ?>
							<div class="text-danger">
								<?php echo $error_date; ?>
							</div>
							<?php } ?>
						</div>
					</div>
					<div class="form-group required">
						<label class="col-sm-2 control-label" for="input-description">
							<?php echo $entry_description; ?>
						</label>
						<div class="col-sm-10">
							<input type="text" name="description" value="<?php echo $description; ?>"
								placeholder="<?php echo $entry_description; ?>" id="input-description" class="form-control" />
							<?php if ($error_description) { ?>
							<div class="text-danger">
								<?php echo $error_description; ?>
							</div>
							<?php } ?>
						</div>
					</div>
					<div class="form-group required">
						<label class="col-sm-2 control-label" for="input-amount">
							<?php echo $entry_amount; ?>
						</label>
						<div class="col-sm-10">
							<input type="text" name="amount" value="<?php echo $amount; ?>"
								placeholder="<?php echo $entry_amount . ' - ' . $help_amount; ?>" id="input-amount"
								class="form-control" />
							<?php if ($error_amount) { ?>
							<div class="text-danger">
								<?php echo $error_amount; ?>
							</div>
							<?php } ?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-customer-name">
							<?php echo $entry_customer_name; ?>
						</label>
						<div class="col-sm-10">
							<input type="text" name="customer_name" value="<?php echo $customer_name; ?>"
								placeholder="<?php echo $entry_customer_name; ?>" id="input-customer-name" class="form-control" />
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		let account_debit, account_credit, child;

		$('select[name=\'transaction_type_id\']').on('change', function () {
			let transaction_type_id = $('select[name=\'transaction_type_id\']').val();

			$.ajax({
				url: 'index.php?route=accounting/expense/transactionTypeAccounts&token=<?php echo $token; ?>&transaction_type_id=' + transaction_type_id,
				dataType: 'json',
				beforeSend: function () {
					$('label[for=\'input-transaction-type\']').append(' <i class="fa fa-circle-o-notch fa-spin"></i>');
				},
				complete: function () {
					$('.fa-spin').remove();
				},
				success: function (json) {
					account_debit = json['account_debit'];
					html = '<option value=""><?php echo $text_select; ?></option>';

					for (let i in account_debit) {
						html += '	<optgroup label="' + account_debit[i]['text'] + '">';

						if (account_debit[i]['child']) {
							child = account_debit[i]['child'];

							for (let j in child) {
								if (child[j]['account_id'] == '<?php echo $account_debit_id; ?>') {
									html += '	  <option value="' + child[j]['account_id'] + '" selected="selected">' + child[j]['text'] + '</option>';
								} else {
									html += '	  <option value="' + child[j]['account_id'] + '">' + child[j]['text'] + '</option>';
								}
							}
						}

						html += '	</optgroup>';
					}
					$('select[name=\'account_debit_id\']').html(html);

					account_credit = json['account_credit'];
					html = '<option value=""><?php echo $text_select; ?></option>';

					for (let i in account_credit) {
						html += '	<optgroup label="' + account_credit[i]['text'] + '">';

						if (account_credit[i]['child']) {
							child = account_credit[i]['child'];

							for (let j in child) {
								if (child[j]['account_id'] == '<?php echo $account_credit_id; ?>') {
									html += '	  <option value="' + child[j]['account_id'] + '" selected="selected">' + child[j]['text'] + '</option>';
								} else {
									html += '	  <option value="' + child[j]['account_id'] + '">' + child[j]['text'] + '</option>';
								}
							}
						}

						html += '	</optgroup>';
					}

					$('select[name=\'account_credit_id\']').html(html);
				},

				error: function (xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		});

		$('select[name=\'transaction_type_id\']').trigger('change');

		$('.date').datetimepicker({
			pickTime: false
		});
	</script>
</div>
<?php echo $footer; ?>
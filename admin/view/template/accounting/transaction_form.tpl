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
					<div class="form-group">
					</div>
					<fieldset <?php echo $order_id ? 'disabled' : '' ; ?>>
						<div class="form-group required">
							<label class="col-sm-2 control-label" for="input-transaction-type">
								<?php echo $entry_transaction_type; ?>
							</label>
							<div class="col-sm-10">
								<?php if ($order_id) { ?>
									<input type="text" value="<?php echo $transaction_type; ?>" class="form-control" />
								<?php } else { ?>
								<select name="transaction_type_id" id="input-transaction-type" class="form-control">
									<option value="">
										<?php echo $text_select; ?>
									</option>
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
								<?php if ($error_transaction_type) { ?>
								<div class="text-danger">
									<?php echo $error_transaction_type; ?>
								</div>
								<?php } ?>
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
									placeholder="<?php echo $entry_amount; ?>" id="input-amount" class="form-control" />
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
						<div class="form-group">
						</div>
					</fieldset>
					<legend>
						<?php echo $text_account; ?>
					</legend>
					<table id="transaction-account" class="table table-bordered table-hover">
						<thead>
							<tr>
								<td class="text-left">
									<?php echo $entry_account; ?>
								</td>
								<td class="text-right">
									<?php echo $entry_debit; ?>
								</td>
								<td class="text-right">
									<?php echo $entry_credit; ?>
								</td>
								<td class="text-right">
									<?php echo $column_action; ?>
								</td>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($transaction_accounts as $row => $transaction_account) { ?>
							<tr id="transaction-account-row<?php echo $row; ?>">
								<td class="text-left">
									<select name="transaction_account[<?php echo $row; ?>][account_id]" class="form-control">
										<option value="0">
											<?php echo $text_none; ?>
										</option>
										<?php foreach ($accounts as $account) { ?>
										<optgroup label="<?php echo $account['text']; ?>">
											<?php if ($account['child']) { ?>
											<?php foreach ($account['child'] as $child) { ?>
											<?php if ($child['account_id'] == $transaction_account['account_id']) { ?>
											<option value="<?php echo $child['account_id']; ?>" selected="selected">
												<?php echo $child['text']; ?>
											</option>
											<?php } else { ?>
											<option value="<?php echo $child['account_id']; ?>">
												<?php echo $child['text']; ?>
											</option>
											<?php } ?>
											<?php } ?>
											<?php } ?>
										</optgroup>
										<?php } ?>
									</select>
								</td>
								<td class="text-right">
									<input type="text" name="transaction_account[<?php echo $row; ?>][debit]"
										value="<?php echo $transaction_account['debit']; ?>" placeholder="<?php echo $entry_debit; ?>"
										class="form-control" />
								</td>
								<td class="text-right">
									<input type="text" name="transaction_account[<?php echo $row; ?>][credit]"
										value="<?php echo $transaction_account['credit']; ?>" placeholder="<?php echo $entry_credit; ?>"
										class="form-control" />
								</td>
								<td class="text-right"><button type="button" value="<?php echo $row; ?>" data-toggle="tooltip"
										title="<?php echo $button_remove; ?>" class="btn btn-danger btn-remove"><i
											class="fa fa-minus-circle"></i></button></td>
							</tr>
							<?php } ?>
						</tbody>
						<tfoot>
							<tr>
								<td colspan="3"></td>
								<td class="text-right"><button type="button" onclick="addTransactionAccount();" data-toggle="tooltip"
										title="<?php echo $button_account_add; ?>" class="btn btn-primary"><i
											class="fa fa-plus-circle"></i></button></td>
							</tr>
						</tfoot>
					</table>
				</form>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		let row = '<?php echo $transaction_accounts_idx ?>';

		function addTransactionAccount() {
			html = '<tr id="transaction-account-row' + row + '" value="' + row + '">';
			html += '  <td class="text-left">';
			html += `  <select name="transaction_account[` + row + `][account_id]" class="form-control">
							<option value="0"><?php echo $text_none; ?></option>
							<?php foreach ($accounts as $account) { ?>
							<optgroup label="<?php echo $account['text']; ?>">
								<?php if ($account['child']) { ?>
								<?php foreach ($account['child'] as $child) { ?>
								<option value="<?php echo $child['account_id']; ?>">
									<?php echo $child['text']; ?>
								</option>
								<?php } ?>
								<?php } ?>
							</optgroup>
							<?php } ?>'
							</select>`;
			html += '  </td>';
			html += '  <td class="text-right">';
			html += '  <input type="text" name="transaction_account[' + row + '][debit]" value="" placeholder="<?php echo $entry_debit; ?>" class="form-control" />';
			html += '  </td>';
			html += '  <td class="text-right">';
			html += '  <input type="text" name="transaction_account[' + row + '][credit]" value="" placeholder="<?php echo $entry_credit; ?>" class="form-control" />';
			html += '  </td>';
			html += '  <td class="text-right"><button type="button" value="' + row + '" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger btn-remove"><i class="fa fa-minus-circle"></i></button></td>';
			html += '</tr>';

			$('#transaction-account tbody').append(html);

			row++;
		};

		$('#transaction-account').on('click', '.btn-remove', function () {
			$('#transaction-account-row' + this.value).remove();
		});

		$('.date').datetimepicker({
			pickTime: false
		});
	</script>
</div>
<?php echo $footer; ?>
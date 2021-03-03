<?php echo $header; ?>
<?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button type="submit" form="form-transaction-type" data-toggle="tooltip" title="<?php echo $button_save; ?>"
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
				<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-transaction-type"
					class="form-horizontal">
					<div class="form-group required">
						<label class="col-sm-2 control-label" for="input-name">
							<?php echo $entry_name; ?>
						</label>
						<div class="col-sm-10">
							<input type="text" name="name" value="<?php echo $name; ?>" placeholder="<?php echo $entry_name; ?>"
								id="input-name" class="form-control" />
							<?php if ($error_name) { ?>
							<div class="text-danger">
								<?php echo $error_name; ?>
							</div>
							<?php } ?>
						</div>
					</div>
					<div class="form-group required">
						<label class="col-sm-2 control-label" for="input-client-label">
							<?php echo $entry_client_label; ?>
						</label>
						<div class="col-sm-10">
							<select name="client_label" id="input-client-label" class="form-control">
								<option value="">
									<?php echo $text_select; ?>
								</option>
								<?php foreach ($clients_label as $label) { ?>
								<?php if ($label['value'] == $client_label) { ?>
								<option value="<?php echo $label['value']; ?>" selected="selected">
									<?php echo $label['text']; ?>
								</option>
								<?php } else { ?>
								<option value="<?php echo $label['value']; ?>">
									<?php echo $label['text']; ?>
								</option>
								<?php } ?>
								<?php } ?>
							</select>
							<?php if ($error_client_label) { ?>
							<div class="text-danger">
								<?php echo $error_client_label; ?>
							</div>
							<?php } ?>
						</div>
					</div>
					<div class="form-group required">
						<label class="col-sm-2 control-label" for="input-category-label">
							<?php echo $entry_category_label; ?>
						</label>
						<div class="col-sm-10">
							<select name="category_label" id="input-category-label" class="form-control">
								<option value="">
									<?php echo $text_select; ?>
								</option>
								<?php foreach ($categories_label as $label) { ?>
								<?php if ($label['value'] == $category_label) { ?>
								<option value="<?php echo $label['value']; ?>" selected="selected">
									<?php echo $label['text']; ?>
								</option>
								<?php } else { ?>
								<option value="<?php echo $label['value']; ?>">
									<?php echo $label['text']; ?>
								</option>
								<?php } ?>
								<?php } ?>
							</select>
							<?php if ($error_client_label) { ?>
							<div class="text-danger">
								<?php echo $error_client_label; ?>
							</div>
							<?php } ?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-manual-select"><span data-toggle="tooltip"
								title="<?php echo $help_manual_select; ?>">
								<?php echo $entry_manual_select; ?>
							</span>
						</label>
						<div class="col-sm-10">
							<?php if ($manual_select) { ?>
							<input type="checkbox" name="manual_select" value="1" id="input-manual-select" checked="checked" />
							<?php } else { ?>
							<input type="checkbox" name="manual_select" value="1" id="input-manual-select" />
							<?php } ?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-sort-order">
							<?php echo $entry_sort_order; ?>
						</label>
						<div class="col-sm-10">
							<input type="text" name="sort_order" value="<?php echo $sort_order; ?>"
								placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
						</div>
					</div>
					<br>
					<legend>
						<?php echo $text_account; ?>
					</legend>
					<table id="transaction-type-account" class="table table-bordered table-hover">
						<thead>
							<tr>
								<td class="text-left">
									<?php echo $entry_transaction_label; ?>
								</td>
								<td class="text-left">
									<?php echo $entry_account_debit; ?>
								</td>
								<td class="text-left">
									<?php echo $entry_account_credit; ?>
								</td>
								<td class="text-right">
									<?php echo $column_action; ?>
								</td>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($transaction_type_accounts as $row => $transaction_type_account) { ?>
							<tr id="transaction-type-account-row<?php echo $row; ?>">
								<td class="text-left">
									<select name="transaction_type_account[<?php echo $row; ?>][transaction_label]" class="form-control">
										<option value="">
											<?php echo $text_select; ?>
										</option>
										<?php foreach ($transactions_label as $label) { ?>
										<?php if ($label['value'] == $transaction_type_account['transaction_label']) { ?>
										<option value="<?php echo $label['value']; ?>" selected="selected">
											<?php echo $label['text']; ?>
										</option>
										<?php } else { ?>
										<option value="<?php echo $label['value']; ?>">
											<?php echo $label['text']; ?>
										</option>
										<?php } ?>
										<?php } ?>
									</select>
								</td>
								<td class="text-left">
									<select name="transaction_type_account[<?php echo $row; ?>][account_debit_id]" class="form-control">
										<option value="0">
											<?php echo $text_none; ?>
										</option>
										<?php foreach ($accounts as $account) { ?>
										<optgroup label="<?php echo $account['text']; ?>">
											<?php if ($account['child']) { ?>
											<?php foreach ($account['child'] as $child) { ?>
											<?php if ($child['account_id'] == $transaction_type_account['account_debit_id']) { ?>
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
								<td class="text-left">
									<select name="transaction_type_account[<?php echo $row; ?>][account_credit_id]" class="form-control">
										<option value="0">
											<?php echo $text_none; ?>
										</option>
										<?php foreach ($accounts as $account) { ?>
										<optgroup label="<?php echo $account['text']; ?>">
											<?php if ($account['child']) { ?>
											<?php foreach ($account['child'] as $child) { ?>
											<?php if ($child['account_id'] == $transaction_type_account['account_credit_id']) { ?>
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
								<td class="text-right"><button type="button" value="<?php echo $row; ?>" data-toggle="tooltip"
										title="<?php echo $button_remove; ?>" class="btn btn-danger btn-remove"><i
											class="fa fa-minus-circle"></i></button></td>
							</tr>
							<?php } ?>
						</tbody>
						<tfoot>
							<tr>
								<td colspan="3"></td>
								<td class="text-right"><button type="button" onclick="addTransactionTypeAccount();"
										data-toggle="tooltip" title="<?php echo $button_account_add; ?>" class="btn btn-primary"><i
											class="fa fa-plus-circle"></i></button></td>
							</tr>
						</tfoot>
					</table>
				</form>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		let row = '<?php echo $transaction_type_accounts_idx ?>';

		function addTransactionTypeAccount() {
			html = '<tr id="transaction-type-account-row' + row + '" value="' + row + '">';
			html += '  <td class="text-left">';
			html += `  <select name="transaction_type_account[` + row + `][transaction_label]" class="form-control">
									<option value=""><?php echo $text_select; ?></option>
									<?php foreach ($transactions_label as $label) { ?>
									<option value="<?php echo $label['value']; ?>"><?php echo $label['text']; ?></option>
									<?php } ?>'
									</select>`;
			html += '  </td>';
			html += '  <td class="text-left">';
			html += `  <select name="transaction_type_account[` + row + `][account_debit_id]" class="form-control">
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
			html += '  <td class="text-left">';
			html += `  <select name="transaction_type_account[` + row + `][account_credit_id]" class="form-control">
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
			html += '  <td class="text-right"><button type="button" value="' + row + '" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger btn-remove"><i class="fa fa-minus-circle"></i></button></td>';
			html += '</tr>';

			$('#transaction-type-account tbody').append(html);

			row++;
		};

		$('#transaction-type-account').on('click', '.btn-remove', function () {
			$('#transaction-type-account-row' + this.value).remove();
		});
	</script>
</div>
<?php echo $footer; ?>
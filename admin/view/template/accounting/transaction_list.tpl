<?= $header; ?>
<?= $column_left; ?>
<div id="content">
	<div class="page-header hide-print">
		<div class="container-fluid">
			<div class="pull-right"><a href="<?= $add; ?>" data-toggle="tooltip" title="<?= $button_add; ?>"
					class="btn btn-primary"><i class="fa fa-plus"></i></a>
				<button type="button" id="button-print" data-toggle="tooltip" title="<?= $button_print; ?>"
					class="btn btn-info"><i class="fa fa-print"></i></button>
				<button type="button" data-toggle="tooltip" title="<?= $button_delete; ?>" class="btn btn-danger"
					onclick="confirm('<?= $text_confirm; ?>') ? $('#form-transaction').submit() : false;"><i
						class="fa fa-trash-o"></i></button>
			</div>
			<h1>
				<?= $heading_title; ?>
			</h1>
			<ul class="breadcrumb">
				<?php foreach ($breadcrumbs as $breadcrumb) { ?>
				<li><a href="<?= $breadcrumb['href']; ?>">
						<?= $breadcrumb['text']; ?>
					</a></li>
				<?php } ?>
			</ul>
		</div>
	</div>
	<div class="container-fluid">
		<?php if ($error_warning) { ?>
		<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i>
			<?= $error_warning; ?>
			<button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
		<?php } ?>
		<?php if ($success) { ?>
		<div class="alert alert-success"><i class="fa fa-check-circle"></i>
			<?= $success; ?>
			<button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
		<?php } ?>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-bar-chart"></i>
					<?= $text_list; ?>
				</h3>
			</div>
			<div class="panel-body">
				<div class="well">
					<div class="row">
						<div class="col-sm-6 col-md-3">
							<div class="form-group">
								<label class="control-label" for="input-date-start">
									<?= $entry_date_start; ?>
								</label>
								<div class="input-group date">
									<input type="text" name="filter[date_start]" value="<?= $filter['date_start']; ?>"
										placeholder="<?= $entry_date_start; ?>" data-date-format="YYYY-MM-DD" id="input-date-start"
										class="form-control" />
									<span class="input-group-btn">
										<button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
									</span>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label" for="input-date-end">
									<?= $entry_date_end; ?>
								</label>
								<div class="input-group date">
									<input type="text" name="filter[date_end]" value="<?= $filter['date_end']; ?>"
										placeholder="<?= $entry_date_end; ?>" data-date-format="YYYY-MM-DD" id="input-date-end"
										class="form-control" />
									<span class="input-group-btn">
										<button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
									</span>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label" for="input-order-id">
									<?= $entry_order_id; ?>
								</label>
								<input type="text" name="filter[order_id]" value="<?= $filter['order_id']; ?>"
									placeholder="<?= $entry_order_id; ?>" id="input-description" class="form-control" />
							</div>
						</div>
						<div class="col-sm-6 col-md-3">
							<div class="form-group">
								<label class="control-label" for="input-reference">
									<?= $entry_reference; ?>
								</label>
								<input type="text" name="filter[reference]" value="<?= $filter['reference']; ?>"
									placeholder="<?= $entry_reference; ?>" id="input-reference" class="form-control" />
							</div>
							<div class="form-group">
								<label class="control-label" for="input-transaction-type">
									<?= $entry_transaction_type; ?>
								</label>
								<select name="filter[transaction_type_id]" id="input-transaction-type" class="form-control">
									<option value="">
										<?= $text_all; ?>
									</option>
									<?php foreach ($transaction_types as $transaction_type) { ?>
									<?php if ($transaction_type['transaction_type_id'] == $filter['transaction_type_id']) { ?>
									<option value="<?= $transaction_type['transaction_type_id']; ?>" selected="selected">
										<?= $transaction_type['name']; ?>
									</option>
									<?php } else { ?>
									<option value="<?= $transaction_type['transaction_type_id']; ?>">
										<?= $transaction_type['name']; ?>
									</option>
									<?php } ?>
									<?php } ?>
								</select>
							</div>
							<div class="form-group">
								<label class="control-label" for="input-account">
									<?= $entry_account; ?>
								</label>
								<select name="filter[account_id]" id="input-account" class="form-control">
									<option value="">
										<?= $text_all; ?>
									</option>
									<?php if ($filter['account_id'] === '-') { ?>
									<option value="-" selected="selected">
										<?= $text_none; ?>
									</option>
									<?php } else { ?>
									<option value="-">
										<?= $text_none; ?>
									</option>
									<?php } ?>
									<?php foreach ($accounts as $account) { ?>
									<?php if ($account['account_id'] == $filter['account_id']) { ?>
									<option value="<?= $account['account_id']; ?>" selected="selected">
										<?= $account['text']; ?>
									</option>
									<?php } else { ?>
									<option value="<?= $account['account_id']; ?>">
										<?= $account['text']; ?>
									</option>
									<?php } ?>
									<?php if ($account['child']) { ?>
									<?php foreach ($account['child'] as $child) { ?>
									<?php if ($child['account_id'] == $filter['account_id']) { ?>
									<option value="<?= $child['account_id']; ?>" selected="selected">&nbsp;&nbsp;&nbsp;
										<?= $child['text']; ?>
									</option>
									<?php } else { ?>
									<option value="<?= $child['account_id']; ?>">&nbsp;&nbsp;&nbsp;
										<?= $child['text']; ?>
									</option>
									<?php } ?>
									<?php } ?>
									<?php } ?>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="col-sm-6 col-md-3">
							<div class="form-group">
								<label class="control-label" for="input-description">
									<?= $entry_description; ?>
								</label>
								<input type="text" name="filter[description]" value="<?= $filter['description']; ?>"
									placeholder="<?= $entry_description; ?>" id="input-description" class="form-control" />
							</div>
							<div class="form-group">
								<label class="control-label" for="input-customer-name">
									<?= $entry_customer_name; ?>
								</label>
								<input type="text" name="filter[customer_name]" value="<?= $filter['customer_name']; ?>"
									placeholder="<?= $entry_customer_name; ?>" id="input-customer-name" class="form-control" />
							</div>
						</div>
						<div class="col-sm-6 col-md-3">
							<div class="form-group">
								<label class="control-label" for="input-validated">
									<?= $entry_validated; ?>
								</label>
								<select name="filter[validated]" id="input-validated" class="form-control">
									<option value="*">
										<?= $text_all; ?>
									</option>
									<?php if ($filter['validated']) { ?>
									<option value="1" selected="selected">
										<?php echo $text_yes; ?>
									</option>
									<?php } else { ?>
									<option value="1">
										<?php echo $text_yes; ?>
									</option>
									<?php } ?>
									<?php if (!$filter['validated'] && !is_null($filter['validated'])) { ?>
									<option value="0" selected="selected">
										<?php echo $text_no; ?>
									</option>
									<?php } else { ?>
									<option value="0">
										<?php echo $text_no; ?>
									</option>
									<?php } ?>
								</select>
							</div>
							<div class="form-group">
								<label class="control-label" for="input-username">
									<?= $entry_username; ?>
								</label>
								<input type="text" name="filter[username]" value="<?= $filter['username']; ?>"
									placeholder="<?= $entry_username; ?>" id="input-username" class="form-control" />
							</div>
							<button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i>
								<?= $button_filter; ?>
							</button>
						</div>
					</div>
				</div>
				<form action="<?= $delete; ?>" method="post" enctype="multipart/form-data" id="form-transaction">
					<div class="table-responsive">
						<table class="table table-bordered table-hover">
							<thead>
								<tr>
									<td style="width: 1px;" class="text-center"><input type="checkbox"
											onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
									<td class="text-left">
										<?php if ($sort == 't.date') { ?>
										<a href="<?= $sort_date; ?>" class="<?= strtolower($order); ?>">
											<?= $column_date; ?>
										</a>
										<?php } else { ?>
										<a href="<?= $sort_date; ?>">
											<?= $column_date; ?>
										</a>
										<?php } ?>
									</td>
									<td class="text-left">
										<?php if ($sort == 'transaction_type') { ?>
										<a href="<?= $sort_transaction_type; ?>" class="<?= strtolower($order); ?>">
											<?= $column_transaction_type; ?>
										</a>
										<?php } else { ?>
										<a href="<?= $sort_transaction_type; ?>">
											<?= $column_transaction_type; ?>
										</a>
										<?php } ?> |
										<?php if ($sort == 'reference') { ?>
										<a href="<?= $sort_reference; ?>" class="<?= strtolower($order); ?>">
											<?= $column_reference; ?>
										</a>
										<?php } else { ?>
										<a href="<?= $sort_reference; ?>">
											<?= $column_reference; ?>
										</a>
										<?php } ?> |
										<?php if ($sort == 't.description') { ?>
										<a href="<?= $sort_description; ?>" class="<?= strtolower($order); ?>">
											<?= $column_description; ?>
										</a>
										<?php } else { ?>
										<a href="<?= $sort_description; ?>">
											<?= $column_description; ?>
										</a>
										<?php } ?> |
										<?php if ($sort == 't.customer_name') { ?>
										<a href="<?= $sort_customer_name; ?>" class="<?= strtolower($order); ?>">
											<?= $column_customer_name; ?>
										</a>
										<?php } else { ?>
										<a href="<?= $sort_customer_name; ?>">
											<?= $column_customer_name; ?>
										</a>
										<?php } ?>
									</td>
									<td class="text-right">
										<?php if ($sort == 't.amount') { ?>
										<a href="<?= $sort_amount; ?>" class="<?= strtolower($order); ?>">
											<?= $column_amount; ?>
										</a>
										<?php } else { ?>
										<a href="<?= $sort_amount; ?>">
											<?= $column_amount; ?>
										</a>
										<?php } ?>
									</td>
									<td class="text-left">
										<?= $column_account_debit; ?>
									</td>
									<td class="text-left">
										<?= $column_account_credit; ?>
									</td>
									<td class="text-center">
										<?php if ($sort == 't.edit_permission') { ?>
										<a href="<?= $sort_validated; ?>" class="<?= strtolower($order); ?>">
											<?= $column_validated; ?>
										</a>
										<?php } else { ?>
										<a href="<?= $sort_validated; ?>">
											<?= $column_validated; ?>
										</a>
										<?php } ?>
									</td>
									<td class="text-left">
										<?php if ($sort == 'u.username') { ?>
										<a href="<?= $sort_username; ?>" class="<?= strtolower($order); ?>">
											<?= $column_username; ?>
										</a>
										<?php } else { ?>
										<a href="<?= $sort_username; ?>">
											<?= $column_username; ?>
										</a>
										<?php } ?>
									</td>
									<td class="text-right">
										<?= $column_action; ?>
									</td>
								</tr>
							</thead>
							<tbody>
								<?php if ($transactions) { ?>
								<?php foreach ($transactions as $transaction) { ?>
								<tr <?=$transaction['uncomplete'] ? 'class="danger"' : '' ; ?>>
									<td class="text-center">
										<?php if (in_array($transaction['transaction_id'], $selected)) { ?>
										<input type="checkbox" name="selected[]" value="<?= $transaction['transaction_id']; ?>"
											checked="checked" />
										<?php } else { ?>
										<input type="checkbox" name="selected[]" value="<?= $transaction['transaction_id']; ?>" />
										<?php } ?>
									</td>
									<td class="text-left">
										<?= $transaction['date']; ?>
									</td>
									<td class="text-left">
										<p><strong>
												<?= $transaction['transaction_type']; ?>
											</strong><br>

											<?php if ($transaction['order_url']) { ?>
											<a href="<?= $transaction['order_url']; ?>" target="_blank">
												<?= $transaction['reference']; ?>
											</a>
											<?php } else { ?>
											<?= $transaction['reference']; ?>
											<?php } ?>
										</p>
										<p>
											<?= $transaction['description']; ?>
										</p>
										<em>
											<?= $transaction['customer_name']; ?>
										</em>
									</td>
									<td class="text-right">
										<?= $transaction['amount']; ?>
									</td>
									<td class="text-left">
										<?php foreach ($transaction['account']['debit'] as $account) { ?>
										<?= $account; ?><br>
										<?php } ?>
									</td>
									<td class="text-left">
										<?php foreach ($transaction['account']['credit'] as $account) { ?>
										<?= $account; ?><br>
										<?php } ?>
									</td>
									<td class="text-center text-success">
										<i class="fa fa-lg <?= $transaction['validated'] ? 'fa-check-square' : 'fa-square-o'; ?>"></i>
									</td>
									<td class="text-left">
										<?= $transaction['username']; ?>
									</td>
									<td class="text-right nowrap">
										<?php if ($transaction['unlock']) { ?>
										<button type="button" id="btn-lock-toggle<?= $transaction['transaction_id']; ?>"
											value="<?= $transaction['transaction_id']; ?>" class="btn btn-sm btn-warning"
											data-toggle="tooltip" title="<?= $button_edit_lock; ?>"><i class="fa fa-unlock-alt"></i></button>
										<?php } else { ?>
										<button type="button" id="btn-lock-toggle<?= $transaction['transaction_id']; ?>"
											value="<?= $transaction['transaction_id']; ?>" class="btn btn-sm btn-primary"
											data-toggle="tooltip" title="<?= $button_edit_unlock; ?>"><i class="fa fa-lock"></i></button>
										<?php } ?>
										<a href="<?= $transaction['edit']; ?>" data-toggle="tooltip" title="<?= $button_edit; ?>"
											class="btn btn-sm btn-primary"><i class="fa fa-pencil"></i></a>
									</td>
								</tr>
								<?php } ?>
								<?php } else { ?>
								<tr>
									<td class="text-center" colspan="10">
										<?= $text_no_results; ?>
									</td>
								</tr>
								<?php } ?>
							</tbody>
							<tfoot>
								<tr>
									<td class="text-right" colspan="3">
										<?= $text_total; ?>
									</td>
									<td class="text-right">
										<?= $total; ?>
									</td>
								</tr>
							</tfoot>
						</table>
					</div>
				</form>
				<div class="row">
					<div class="col-sm-6 text-left">
						<?= $pagination; ?>
					</div>
					<div class="col-sm-6 text-right">
						<?= $results; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		$('#button-filter').on('click', function () {
			url = 'index.php?route=accounting/transaction&token=<?= $token; ?>';

			let filter_items = [
				'date_start',
				'date_end',
				'account_id',
				'transaction_type_id',
				'description',
				'reference',
				'order_id',
				'customer_name',
				'username'
			];

			let filter = [];

			for (let i = 0; i < filter_items.length; i++) {
				filter[filter_items[i]] = $('[name=\'filter[' + filter_items[i] + ']\']').val();

				if (filter[filter_items[i]]) {
					url += '&filter_' + filter_items[i] + '=' + encodeURIComponent(filter[filter_items[i]]);
				}
			}

			filter['validated'] = $('[name=\'filter[validated]\']').val();

			if (filter['validated'] != '*') {
				url += '&filter_validated=' + encodeURIComponent(filter['validated']);
			}

			location = url;
		});
	</script>
	<script type="text/javascript">
		$('#button-print').on('click', function () {
			if (confirm('<?= $text_confirm_print; ?>')) {
				let url = 'index.php?route=accounting/transaction/print&token=<?= $token . $url; ?>';

				$('#form-transaction').attr('action', url).submit();
			}
		});

		$('#form-transaction').on('click', 'button[id^=\'btn-lock-toggle\']', function (e) {
			var node = this;

			$.ajax({
				url: 'index.php?route=accounting/transaction/editPermission&token=<?= $token; ?>',
				type: 'post',
				dataType: 'json',
				data: 'transaction_id=' + $(node).val(),
				crossDomain: false,
				beforeSend: function () {
					$(node).button('loading');
				},
				complete: function () {
					$(node).button('reset');
				},
				success: function (json) {
					$('.alert').remove();

					if (json['error']) {
						$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
					}

					if (json['success']) {
						$('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

						if (json['unlock_status']) {
							$(node).replaceWith('<button type="button" id="btn-lock-toggle' + $(node).val() + '" value="' + $(node).val() + '" class="btn btn-sm btn-warning" data-toggle="tooltip" title="<?= $button_edit_lock; ?>"><i class="fa fa-unlock-alt"></i></button>');
						} else {
							$(node).replaceWith('<button type="button" id="btn-lock-toggle' + $(node).val() + '" value="' + $(node).val() + '" class="btn btn-sm btn-primary" data-toggle="tooltip" title="<?= $button_edit_unlock; ?>"><i class="fa fa-lock"></i></button>');
						}
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

		$(document).keypress(function (e) {
			if (e.which == 13) {
				$("#button-filter").click();
			}
		});
	</script>
</div>
<?= $footer; ?>
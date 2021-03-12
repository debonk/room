<?php echo $header; ?>
<?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right"><a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>"
					class="btn btn-primary"><i class="fa fa-plus"></i></a>
				<button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger"
					onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-transaction').submit() : false;"><i
						class="fa fa-trash-o"></i></button>
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
		<?php if ($success) { ?>
		<div class="alert alert-success"><i class="fa fa-check-circle"></i>
			<?php echo $success; ?>
			<button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
		<?php } ?>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-bar-chart"></i>
					<?php echo $text_list; ?>
				</h3>
			</div>
			<div class="panel-body">
				<div class="well">
					<div class="row">
						<div class="col-sm-6 col-md-4">
							<div class="form-group">
								<label class="control-label" for="input-date-start">
									<?php echo $entry_date_start; ?>
								</label>
								<div class="input-group date">
									<input type="text" name="filter[date_start]" value="<?php echo $filter['date_start']; ?>"
										placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" id="input-date-start"
										class="form-control" />
									<span class="input-group-btn">
										<button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
									</span>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label" for="input-date-end">
									<?php echo $entry_date_end; ?>
								</label>
								<div class="input-group date">
									<input type="text" name="filter[date_end]" value="<?php echo $filter['date_end']; ?>"
										placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" id="input-date-end"
										class="form-control" />
									<span class="input-group-btn">
										<button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
									</span>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label" for="input-order-id">
									<?php echo $entry_order_id; ?>
								</label>
								<input type="text" name="filter[order_id]" value="<?php echo $filter['order_id']; ?>"
									placeholder="<?php echo $entry_order_id; ?>" id="input-description" class="form-control" />
							</div>
						</div>
						<div class="col-sm-6 col-md-4">
							<div class="form-group">
								<label class="control-label" for="input-reference">
									<?php echo $entry_reference; ?>
								</label>
								<input type="text" name="filter[reference]" value="<?php echo $filter['reference']; ?>"
									placeholder="<?php echo $entry_reference; ?>" id="input-reference" class="form-control" />
							</div>
							<div class="form-group">
								<label class="control-label" for="input-transaction-type">
									<?php echo $entry_transaction_type; ?>
								</label>
								<select name="filter[transaction_type_id]" id="input-transaction-type" class="form-control">
									<option value="">
										<?php echo $text_all; ?>
									</option>
									<?php foreach ($transaction_types as $transaction_type) { ?>
									<?php if ($transaction_type['transaction_type_id'] == $filter['transaction_type_id']) { ?>
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
							<div class="form-group">
								<label class="control-label" for="input-account">
									<?php echo $entry_account; ?>
								</label>
								<select name="filter[account_id]" id="input-account" class="form-control">
									<option value="">
										<?php echo $text_all; ?>
									</option>
									<?php foreach ($accounts as $account) { ?>
									<?php if ($account['account_id'] == $filter['account_id']) { ?>
									<option value="<?php echo $account['account_id']; ?>" selected="selected">
										<?php echo $account['text']; ?>
									</option>
									<?php } else { ?>
									<option value="<?php echo $account['account_id']; ?>">
										<?php echo $account['text']; ?>
									</option>
									<?php } ?>
									<?php if ($account['child']) { ?>
									<?php foreach ($account['child'] as $child) { ?>
									<?php if ($child['account_id'] == $filter['account_id']) { ?>
									<option value="<?php echo $child['account_id']; ?>" selected="selected">&nbsp;&nbsp;&nbsp;
										<?php echo $child['text']; ?>
									</option>
									<?php } else { ?>
									<option value="<?php echo $child['account_id']; ?>">&nbsp;&nbsp;&nbsp;
										<?php echo $child['text']; ?>
									</option>
									<?php } ?>
									<?php } ?>
									<?php } ?>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="col-sm-6 col-md-4">
							<div class="form-group">
								<label class="control-label" for="input-description">
									<?php echo $entry_description; ?>
								</label>
								<input type="text" name="filter[description]" value="<?php echo $filter['description']; ?>"
									placeholder="<?php echo $entry_description; ?>" id="input-description" class="form-control" />
							</div>
							<div class="form-group">
								<label class="control-label" for="input-customer-name">
									<?php echo $entry_customer_name; ?>
								</label>
								<input type="text" name="filter[customer_name]" value="<?php echo $filter['customer_name']; ?>"
									placeholder="<?php echo $entry_customer_name; ?>" id="input-customer-name" class="form-control" />
							</div>
							<div class="form-group">
								<label class="control-label" for="input-username">
									<?php echo $entry_username; ?>
								</label>
								<input type="text" name="filter[username]" value="<?php echo $filter['username']; ?>"
									placeholder="<?php echo $entry_username; ?>" id="input-username" class="form-control" />
							</div>
							<button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i>
								<?php echo $button_filter; ?>
							</button>
						</div>
					</div>
				</div>
				<form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-transaction">
					<div class="table-responsive">
						<table class="table table-bordered table-hover">
							<thead>
								<tr>
									<td style="width: 1px;" class="text-center"><input type="checkbox"
											onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
									<td class="text-left">
										<?php if ($sort == 't.date') { ?>
										<a href="<?php echo $sort_date; ?>" class="<?php echo strtolower($order); ?>">
											<?php echo $column_date; ?>
										</a>
										<?php } else { ?>
										<a href="<?php echo $sort_date; ?>">
											<?php echo $column_date; ?>
										</a>
										<?php } ?>
									</td>
									<td class="text-left">
										<?php if ($sort == 'transaction_type') { ?>
										<a href="<?php echo $sort_transaction_type; ?>" class="<?php echo strtolower($order); ?>">
											<?php echo $column_transaction_type; ?>
										</a>
										<?php } else { ?>
										<a href="<?php echo $sort_transaction_type; ?>">
											<?php echo $column_transaction_type; ?>
										</a>
										<?php } ?>
									</td>
									<td class="text-left">
										<?php if ($sort == 'reference') { ?>
										<a href="<?php echo $sort_reference; ?>" class="<?php echo strtolower($order); ?>">
											<?php echo $column_reference; ?>
										</a>
										<?php } else { ?>
										<a href="<?php echo $sort_reference; ?>">
											<?php echo $column_reference; ?>
										</a>
										<?php } ?>
									</td>
									<td class="text-left">
										<?php if ($sort == 't.description') { ?>
										<a href="<?php echo $sort_description; ?>" class="<?php echo strtolower($order); ?>">
											<?php echo $column_description; ?>
										</a>
										<?php } else { ?>
										<a href="<?php echo $sort_description; ?>">
											<?php echo $column_description; ?>
										</a>
										<?php } ?>
									</td>
									<td class="text-left">
										<?php if ($sort == 't.customer_name') { ?>
										<a href="<?php echo $sort_customer_name; ?>" class="<?php echo strtolower($order); ?>">
											<?php echo $column_customer_name; ?>
										</a>
										<?php } else { ?>
										<a href="<?php echo $sort_customer_name; ?>">
											<?php echo $column_customer_name; ?>
										</a>
										<?php } ?>
									</td>
									<td class="text-right">
										<?php if ($sort == 't.amount') { ?>
										<a href="<?php echo $sort_amount; ?>" class="<?php echo strtolower($order); ?>">
											<?php echo $column_amount; ?>
										</a>
										<?php } else { ?>
										<a href="<?php echo $sort_amount; ?>">
											<?php echo $column_amount; ?>
										</a>
										<?php } ?>
									</td>
									<td class="text-left">
										<?php echo $column_account_debit; ?>
									</td>
									<td class="text-left">
										<?php echo $column_account_credit; ?>
									</td>
									<td class="text-left">
										<?php if ($sort == 'u.username') { ?>
										<a href="<?php echo $sort_username; ?>" class="<?php echo strtolower($order); ?>">
											<?php echo $column_username; ?>
										</a>
										<?php } else { ?>
										<a href="<?php echo $sort_username; ?>">
											<?php echo $column_username; ?>
										</a>
										<?php } ?>
									</td>
									<td class="text-right">
										<?php echo $column_action; ?>
									</td>
								</tr>
							</thead>
							<tbody>
								<?php if ($transactions) { ?>
								<?php foreach ($transactions as $transaction) { ?>
								<tr <?php echo $transaction['uncomplete'] ? 'class="danger"' : '' ; ?>>
									<td class="text-center">
										<?php if (in_array($transaction['transaction_id'], $selected)) { ?>
										<input type="checkbox" name="selected[]" value="<?php echo $transaction['transaction_id']; ?>"
											checked="checked" />
										<?php } else { ?>
										<input type="checkbox" name="selected[]" value="<?php echo $transaction['transaction_id']; ?>" />
										<?php } ?>
									</td>
									<td class="text-left">
										<?php echo $transaction['date']; ?>
									</td>
									<td class="text-left">
										<?php echo $transaction['transaction_type']; ?>
									</td>
									<?php if ($transaction['order_url']) { ?>
									<td class="text-left"><a href="<?php echo $transaction['order_url']; ?>" target="_blank">
											<?php echo $transaction['reference']; ?>
										</a></td>
									<?php } else { ?>
									<td class="text-left">
										<?php echo $transaction['reference']; ?>
									</td>
									<?php } ?>
									<td class="text-left">
										<?php echo $transaction['description']; ?>
									</td>
									<td class="text-left">
										<?php echo $transaction['customer_name']; ?>
									</td>
									<td class="text-right">
										<?php echo $transaction['amount']; ?>
									</td>
									<td class="text-left">
										<?php foreach ($transaction['account']['debit'] as $account) { ?>
										<?php echo $account; ?><br>
										<?php } ?>
									</td>
									<td class="text-left">
										<?php foreach ($transaction['account']['credit'] as $account) { ?>
										<?php echo $account; ?><br>
										<?php } ?>
									</td>
									<td class="text-left">
										<?php echo $transaction['username']; ?>
									</td>
									<td class="text-right nowrap">
										<?php if ($transaction['unlock']) { ?>
										<button type="button" id="btn-lock-toggle<?php echo $transaction['transaction_id']; ?>"
											value="<?php echo $transaction['transaction_id']; ?>" class="btn btn-sm btn-warning"
											data-toggle="tooltip" title="<?php echo $button_edit_lock; ?>"><i
												class="fa fa-unlock-alt"></i></button>
										<?php } else { ?>
										<button type="button" id="btn-lock-toggle<?php echo $transaction['transaction_id']; ?>"
											value="<?php echo $transaction['transaction_id']; ?>" class="btn btn-sm btn-primary"
											data-toggle="tooltip" title="<?php echo $button_edit_unlock; ?>"><i
												class="fa fa-lock"></i></button>
										<?php } ?>
										<a href="<?php echo $transaction['edit']; ?>" data-toggle="tooltip"
											title="<?php echo $button_edit; ?>" class="btn btn-sm btn-primary"><i
												class="fa fa-pencil"></i></a>
									</td>
								</tr>
								<?php } ?>
								<?php } else { ?>
								<tr>
									<td class="text-center" colspan="11">
										<?php echo $text_no_results; ?>
									</td>
								</tr>
								<?php } ?>
							</tbody>
							<tfoot>
								<tr>
									<td class="text-right" colspan="6">
										<?php echo $text_total; ?>
									</td>
									<td class="text-right">
										<?php echo $total; ?>
									</td>
								</tr>
							</tfoot>
						</table>
					</div>
				</form>
				<div class="row">
					<div class="col-sm-6 text-left">
						<?php echo $pagination; ?>
					</div>
					<div class="col-sm-6 text-right">
						<?php echo $results; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		$('#button-filter').on('click', function () {
			url = 'index.php?route=accounting/transaction&token=<?php echo $token; ?>';

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

			location = url;
		});
	</script>
	<script type="text/javascript">
		$('#form-transaction').on('click', 'button[id^=\'btn-lock-toggle\']', function (e) {
			var node = this;

			$.ajax({
				url: 'index.php?route=accounting/transaction/editPermission&token=<?php echo $token; ?>',
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
							$(node).replaceWith('<button type="button" id="btn-lock-toggle' + $(node).val() + '" value="' + $(node).val() + '" class="btn btn-sm btn-warning" data-toggle="tooltip" title="<?php echo $button_edit_lock; ?>"><i class="fa fa-unlock-alt"></i></button>');
						} else {
							$(node).replaceWith('<button type="button" id="btn-lock-toggle' + $(node).val() + '" value="' + $(node).val() + '" class="btn btn-sm btn-primary" data-toggle="tooltip" title="<?php echo $button_edit_unlock; ?>"><i class="fa fa-lock"></i></button>');
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
<?php echo $footer; ?>
<?= $header; ?>
<?= $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<a href="<?= $year_view; ?>" data-toggle="tooltip" title="<?= $button_year_view; ?>"
					class="btn btn-info"><i class="fa fa-calendar-o"></i></a>
				<a href="<?= $calendar; ?>" data-toggle="tooltip" title="<?= $button_calendar; ?>"
					class="btn btn-info"><i class="fa fa-calendar"></i></a>
				<a href="<?= $add; ?>" data-toggle="tooltip" title="<?= $button_add; ?>"
					class="btn btn-primary"><i class="fa fa-plus"></i></a>
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
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-list"></i>
					<?= $text_list; ?>
				</h3>
			</div>
			<div class="panel-body">
				<div class="well">
					<div class="row">
						<div class="col-sm-3">
							<div class="form-group">
								<label class="control-label" for="input-order-id">
									<?= $entry_order_id; ?>
								</label>
								<input type="text" name="filter_order_id" value="<?= $filter_order_id; ?>"
									placeholder="<?= $entry_order_id; ?>" id="input-order-id" class="form-control" />
							</div>
							<div class="form-group">
								<label class="control-label" for="input-customer">
									<?= $entry_customer; ?>
								</label>
								<input type="text" name="filter_customer" value="<?= $filter_customer; ?>"
									placeholder="<?= $entry_customer; ?>" id="input-customer" class="form-control" />
							</div>
						</div>
						<div class="col-sm-3">
							<div class="form-group">
								<label class="control-label" for="input-date-start">
									<?= $entry_date_start; ?>
								</label>
								<div class="input-group date">
									<input type="text" name="filter_date_start" value="<?= $filter_date_start; ?>"
										placeholder="<?= $entry_date_start; ?>" data-date-format="YYYY-MM-DD" id="input-date-start"
										class="form-control" />
									<span class="input-group-btn">
										<button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
									</span>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label" for="input-date-start">
									<?= $entry_date_end; ?>
								</label>
								<div class="input-group date">
									<input type="text" name="filter_date_end" value="<?= $filter_date_end; ?>"
										placeholder="<?= $entry_date_end; ?>" data-date-format="YYYY-MM-DD" id="input-date-start"
										class="form-control" />
									<span class="input-group-btn">
										<button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
									</span>
								</div>
							</div>
						</div>
						<div class="col-sm-3">
							<div class="form-group">
								<label class="control-label" for="input-venue">
									<?= $entry_venue; ?>
								</label>
								<select name="filter_model" id="input-venue" class="form-control">
									<option value="*">
										<?= $text_all; ?>
									</option>
									<?php foreach ($venues as $venue) { ?>
									<?php if ($venue['code'] == $filter_model) { ?>
									<option value="<?= $venue['code']; ?>" selected="selected">
										<?= $venue['name']; ?>
									</option>
									<?php } else { ?>
									<option value="<?= $venue['code']; ?>">
										<?= $venue['name']; ?>
									</option>
									<?php } ?>
									<?php } ?>
								</select>
							</div>
							<div class="form-group">
								<label class="control-label" for="input-order-status">
									<?= $entry_order_status; ?>
								</label>
								<select name="filter_order_status" id="input-order-status" class="form-control">
									<option value="*">
										<?= $text_all; ?>
									</option>
									<?php if ($filter_order_status == '0') { ?>
									<option value="0" selected="selected">
										<?= $text_missing; ?>
									</option>
									<?php } else { ?>
									<option value="0">
										<?= $text_missing; ?>
									</option>
									<?php } ?>
									<?php foreach ($order_statuses as $order_status) { ?>
									<?php if ($order_status['order_status_id'] == $filter_order_status) { ?>
									<option value="<?= $order_status['order_status_id']; ?>" selected="selected">
										<?= $order_status['name']; ?>
									</option>
									<?php } else { ?>
									<option value="<?= $order_status['order_status_id']; ?>">
										<?= $order_status['name']; ?>
									</option>
									<?php } ?>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="col-sm-3">
							<div class="form-group">
								<label class="control-label" for="input-username">
									<?= $entry_username; ?>
								</label>
								<input type="text" name="filter_username" value="<?= $filter_username; ?>"
									placeholder="<?= $entry_username; ?>" id="input-username" class="form-control" />
							</div>
							<div class="form-group">
								<label class="control-label" for="input-date-added">
									<?= $entry_date_added; ?>
								</label>
								<div class="input-group date">
									<input type="text" name="filter_date_added" value="<?= $filter_date_added; ?>"
										placeholder="<?= $entry_date_added; ?>" data-date-format="YYYY-MM-DD" id="input-date-added"
										class="form-control" />
									<span class="input-group-btn">
										<button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
									</span>
								</div>
							</div>
							<button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i>
								<?= $button_filter; ?>
							</button>
						</div>
					</div>
				</div>
				<form method="post" action="" enctype="multipart/form-data" target="_blank" rel="noopener noreferrer"
					id="form-order">
					<div class="table-responsive">
						<table class="table table-bordered table-hover">
							<thead>
								<tr>
									<td class="text-right">
										<?php if ($sort == 'o.order_id') { ?>
										<a href="<?= $sort_order; ?>" class="<?= strtolower($order); ?>">
											<?= $column_order_id; ?>
										</a>
										<?php } else { ?>
										<a href="<?= $sort_order; ?>">
											<?= $column_order_id; ?>
										</a>
										<?php } ?>
									</td>
									<td class="text-left">
										<?php if ($sort == 'o.event_date') { ?>
										<a href="<?= $sort_event_date; ?>" class="<?= strtolower($order); ?>">
											<?= $column_event_date; ?>
										</a>
										<?php } else { ?>
										<a href="<?= $sort_event_date; ?>">
											<?= $column_event_date; ?>
										</a>
										<?php } ?>
									</td>
									<td class="text-left" style="min-width: 20%;">
										<?php if ($sort == 'primary_product') { ?>
										<a href="<?= $sort_primary_product; ?>" class="<?= strtolower($order); ?>">
											<?= $column_primary_product; ?>
										</a>
										<?php } else { ?>
										<a href="<?= $sort_primary_product; ?>">
											<?= $column_primary_product; ?>
										</a>
										<?php } ?>
									</td>
									<td class="text-left">
										<?php if ($sort == 'customer') { ?>
										<a href="<?= $sort_customer; ?>" class="<?= strtolower($order); ?>">
											<?= $column_customer; ?>
										</a>
										<?php } else { ?>
										<a href="<?= $sort_customer; ?>">
											<?= $column_customer; ?>
										</a>
										<?php } ?>
									</td>
									<td class="text-right">
										<?php if ($sort == 'o.total') { ?>
										<a href="<?= $sort_total; ?>" class="<?= strtolower($order); ?>">
											<?= $column_total; ?>
										</a>
										<?php } else { ?>
										<a href="<?= $sort_total; ?>">
											<?= $column_total; ?>
										</a>
										<?php } ?>
									</td>
									<td class="text-right">
										<?= $column_vendor_balance; ?>
									</td>
									<td class="text-left">
										<?php if ($sort == 'order_status') { ?>
										<a href="<?= $sort_status; ?>" class="<?= strtolower($order); ?>">
											<?= $column_status; ?>
										</a>
										<?php } else { ?>
										<a href="<?= $sort_status; ?>">
											<?= $column_status; ?>
										</a>
										<?php } ?>
									</td>
									<td class="text-left">
										<?php if ($sort == 'o.date_added') { ?>
										<a href="<?= $sort_date_added; ?>" class="<?= strtolower($order); ?>">
											<?= $column_date_added; ?>
										</a>
										<?php } else { ?>
										<a href="<?= $sort_date_added; ?>">
											<?= $column_date_added; ?>
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
								<?php if ($orders) { ?>
								<?php foreach ($orders as $order) { ?>
								<tr>
									<td class="text-right">
										<?= $order['order_id']; ?>
									</td>
									<td class="text-left">
										<?= $order['event_date']; ?><br />
										<?= $text_slot . ': ' . $order['slot']; ?>
									</td>
									<td class="text-left">
										<?= $order['primary_product']; ?><br />
										<?= $text_title . ': ' . $order['title']; ?><br />
										<?= $text_invoice . ': ' . $order['invoice_no']; ?><br />
									</td>
									<td class="text-left">
										<?= $order['customer']; ?>
									</td>
									<td class="text-right">
										<?= $column_total . ': ' . $order['total']; ?><br />
										<?= $text_balance . ': ' . $order['balance']; ?>
									</td>
									<td class="text-right">
										<?= $order['vendor_balance']; ?>
									</td>
									<td class="text-left">
										<?= $order['order_status']; ?>
										<?php if ($order['payment_status']) { ?>
										<i class="fa fa-exclamation-triangle text-warning"></i>
										<?php } ?>
									</td>
									<td class="text-left">
										<?= $order['date_added']; ?>
									</td>
									<td class="text-left">
										<?= $order['username']; ?>
									</td>
									<td class="text-right nowrap">
										<a href="<?= $order['view']; ?>" data-toggle="tooltip" title="<?= $button_view; ?>"
											class="btn btn-info btn-sm"><i class="fa fa-eye"></i></a>
										<a href="<?= $order['edit']; ?>" data-toggle="tooltip" title="<?= $button_edit; ?>"
											class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i></a>
										<button type="button" value="<?= $order['order_id']; ?>"
											id="button-delete<?= $order['order_id']; ?>"
											data-loading-text="<?= $text_loading; ?>" data-toggle="tooltip"
											title="<?= $button_delete; ?>" class="btn btn-danger btn-sm"><i
												class="fa fa-trash-o"></i></button>
									</td>
								</tr>
								<?php } ?>
								<?php } else { ?>
								<tr>
									<td class="text-center" colspan="11">
										<?= $text_no_results; ?>
									</td>
								</tr>
								<?php } ?>
							</tbody>
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
			url = 'index.php?route=sale/order/list&token=<?= $token; ?>';

			var filter_order_id = $('input[name=\'filter_order_id\']').val();

			if (filter_order_id) {
				url += '&filter_order_id=' + encodeURIComponent(filter_order_id);
			}

			var filter_customer = $('input[name=\'filter_customer\']').val();

			if (filter_customer) {
				url += '&filter_customer=' + encodeURIComponent(filter_customer);
			}

			var filter_order_status = $('select[name=\'filter_order_status\']').val();

			if (filter_order_status != '*') {
				url += '&filter_order_status=' + encodeURIComponent(filter_order_status);
			}

			var filter_date_start = $('input[name=\'filter_date_start\']').val();

			if (filter_date_start) {
				url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
			}

			var filter_date_end = $('input[name=\'filter_date_end\']').val();

			if (filter_date_end) {
				url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
			}

			var filter_date_added = $('input[name=\'filter_date_added\']').val();

			if (filter_date_added) {
				url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
			}

			var filter_model = $('select[name=\'filter_model\']').val();

			if (filter_model != '*') {
				url += '&filter_model=' + encodeURIComponent(filter_model);
			}

			var filter_username = $('input[name=\'filter_username\']').val();

			if (filter_username) {
				url += '&filter_username=' + encodeURIComponent(filter_username);
			}

			location = url;
		});
	</script>
	<script type="text/javascript">
		$('input[name=\'filter_customer\']').autocomplete({
			'source': function (request, response) {
				$.ajax({
					url: 'index.php?route=customer/customer/autocomplete&token=<?= $token; ?>&filter_name=' + encodeURIComponent(request),
					dataType: 'json',
					success: function (json) {
						response($.map(json, function (item) {
							return {
								label: item['name'],
								value: item['customer_id']
							}
						}));
					}
				});
			},
			'select': function (item) {
				$('input[name=\'filter_customer\']').val(item['label']);
			}
		});
	</script>
	<script type="text/javascript">
		// IE and Edge fix!
		// $('#button-receipt, #button-invoice').on('click', function(e) {
		// $('#form-order').attr('action', this.getAttribute('formAction'));
		// });

		$(document).delegate('#button-ip-add', 'click', function () {
			$.ajax({
				url: 'index.php?route=user/api/addip&token=<?= $token; ?>&api_id=<?= $api_id; ?>',
				type: 'post',
				data: 'ip=<?= $api_ip; ?>',
				dataType: 'json',
				beforeSend: function () {
					$('#button-ip-add').button('loading');
				},
				complete: function () {
					$('#button-ip-add').button('reset');
				},
				success: function (json) {
					$('.alert').remove();

					if (json['error']) {
						$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
					}

					if (json['success']) {
						$('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
						$(node).parents("tr").remove();
					}
				},
				error: function (xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		});

		// Login to the API
		var token = '';

		$.ajax({
			url: '<?= $store_url; ?>index.php?route=api/login',
			type: 'post',
			data: 'key=<?= $api_key; ?>',
			dataType: 'json',
			crossDomain: true,
			success: function (json) {
				$('.alert').remove();

				if (json['error']) {
					if (json['error']['key']) {
						$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['key'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
					}

					if (json['error']['ip']) {
						$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['ip'] + ' <button type="button" id="button-ip-add" data-loading-text="<?= $text_loading; ?>" class="btn btn-danger btn-xs pull-right"><i class="fa fa-plus"></i> <?= $button_ip_add; ?></button></div>');
					}
				}

				if (json['token']) {
					token = json['token'];
				}
			},
			error: function (xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});

		$('button[id^=\'button-delete\']').on('click', function (e) {
			if (confirm('<?= $text_confirm; ?>')) {
				var node = this;

				$.ajax({
					url: '<?= $store_url; ?>index.php?route=api/order/delete&token=' + token + '&order_id=' + $(node).val(),
					dataType: 'json',
					crossDomain: true,
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

							location.reload();
						}
					},
					error: function (xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});
			}
		});
	</script>
	<script src="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
	<link href="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css" type="text/css" rel="stylesheet"
		media="screen" />
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
<?= $header; ?>
<?= $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button type="button" class="btn btn-primary" id="button-checklist" <?= $event_status ? '' : 'disabled' ;
					?> ><i class="fa fa-list-alt"></i>
					<?= $button_checklist; ?>
				</button>
				<a href="<?= $document; ?>" target="_blank" class="btn btn-primary"><i class="fa fa-file-text-o"></i>
					<?= $button_document; ?>
				</a>
				<div class="btn-group">
					<button type="button" data-toggle="dropdown" class="btn btn-primary dropdown-toggle" id="button-agreement"
						<?= $initial_payment ? '' : 'disabled' ; ?> ><i class="fa fa-file-o"></i>
						<?= $button_agreement; ?>
					</button>
					<ul class="dropdown-menu dropdown-menu-left">
						<li><a href="<?= $agreement_preview; ?>" target="_blank">
								<?= $text_preview; ?>
							</a></li>
						<?php if (!$printed) { ?>
						<li><a href="<?= $agreement_print; ?>" target="_blank" id="agreement-print">
								<?= $text_print; ?>
							</a></li>
						<?php } ?>
					</ul>
				</div>
				<a href="<?= $edit; ?>" data-toggle="tooltip" title="<?= $button_edit; ?>"
					class="btn btn-primary"><i class="fa fa-pencil"></i></a>
				<a href="<?= $cancel; ?>" data-toggle="tooltip" title="<?= $button_cancel; ?>"
					class="btn btn-default"><i class="fa fa-reply"></i></a>
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
		<?php if ($information) { ?>
		<?php if ($auto_expired) { ?>
		<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i>
			<?= $information; ?><button type="button" id="button-expired"
				data-loading-text="<?= $text_loading; ?>" class="btn btn-danger btn-xs pull-right"><i
					class="fa fa-eraser"></i>
				<?= $button_expired; ?>
			</button>
		</div>
		<?php } else { ?>
		<div class="alert alert-warning"><i class="fa fa-exclamation-circle"></i>
			<?= $information; ?>
		</div>
		<?php } ?>
		<?php } ?>
		<div class="row">
			<div class="col-md-4">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title"><i class="fa fa-shopping-cart"></i>
							<?= $text_order; ?>
						</h3>
					</div>
					<table class="table">
						<tbody>
							<tr>
								<td style="width: 1%;"><button data-toggle="tooltip" title="<?= $text_title; ?>"
										class="btn btn-info btn-xs"><i class="fa fa-birthday-cake fa-fw"></i></button></td>
								<td>
									<?= $title; ?>
								</td>
							</tr>
							<tr>
								<td><button data-toggle="tooltip" title="<?= $text_package; ?>" class="btn btn-info btn-xs"><i
											class="fa fa-gift fa-fw"></i></button></td>
								<td>
									<?= $package; ?>
								</td>
							</tr>
							<tr>
								<td><button data-toggle="tooltip" title="<?= $text_venue; ?>" class="btn btn-info btn-xs"><i
											class="fa fa-location-arrow fa-fw"></i></button></td>
								<td>
									<?= $venue; ?>
								</td>
							</tr>
							<tr>
								<td><button data-toggle="tooltip" title="<?= $text_event_date; ?>" class="btn btn-info btn-xs"><i
											class="fa fa-calendar fa-fw"></i></button></td>
								<td>
									<?= $event_date; ?>
								</td>
							</tr>
							<tr>
								<td><button data-toggle="tooltip" title="<?= $text_slot; ?>" class="btn btn-info btn-xs"><i
											class="fa fa-clock-o fa-fw"></i></button></td>
								<td>
									<?= $slot; ?>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div class="col-md-4">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title"><i class="fa fa-pie-chart"></i>
							<?= $text_vendor; ?>
						</h3>
					</div>
					<table class="table" id="order-vendors">
						<tbody>
							<?php foreach ($order_vendors as $order_vendor) { ?>
							<tr>
								<td>
									<?= $order_vendor['title']; ?>
								</td>
								<td class="text-right nowrap">
									<button data-toggle="tooltip" title="<?= $button_vendor_remove; ?>"
										class="btn btn-danger btn-xs" id="vendor-remove<?= $order_vendor['vendor_id']; ?>"
										value="<?= $order_vendor['vendor_id']; ?>"><i class="fa fa-minus-circle fa-fw"></i></button>
								</td>
							</tr>
							<?php } ?>
						</tbody>
						<tfoot>
							<tr>
								<td colspan="2">
									<input type="text" name="order-vendor" value="" placeholder="<?= $entry_vendor_add; ?>"
										id="input-order-vendor" class="form-control" />
								</td>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
			<div class="col-md-4">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title"><i class="fa fa-cog"></i>
							<?= $text_option; ?>
						</h3>
					</div>
					<table class="table">
						<tbody>
							<tr>
								<td>
									<?= $text_invoice; ?>
								</td>
								<td id="invoice" class="text-right">
									<?= $invoice_no; ?>
								</td>
								<td style="width: 1%;" class="text-center">
									<?php if (!$invoice_no) { ?>
									<button id="button-invoice" data-loading-text="<?= $text_loading; ?>" data-toggle="tooltip"
										title="<?= $button_generate; ?>" class="btn btn-success btn-xs"><i
											class="fa fa-refresh"></i></button>
									<?php } else { ?>
									<button disabled="disabled" class="btn btn-success btn-xs"><i class="fa fa-refresh"></i></button>
									<?php } ?>
								</td>
							</tr>
							<tr>
								<td>
									<?= $text_reward; ?>
								</td>
								<td class="text-right">
									<?= $reward; ?>
								</td>
								<td class="text-center">
									<?php if ($customer && $reward) { ?>
									<?php if (!$reward_total) { ?>
									<button id="button-reward-add" data-loading-text="<?= $text_loading; ?>" data-toggle="tooltip"
										title="<?= $button_reward_add; ?>" class="btn btn-success btn-xs"><i
											class="fa fa-plus-circle"></i></button>
									<?php } else { ?>
									<button id="button-reward-remove" data-loading-text="<?= $text_loading; ?>"
										data-toggle="tooltip" title="<?= $button_reward_remove; ?>" class="btn btn-danger btn-xs"><i
											class="fa fa-minus-circle"></i></button>
									<?php } ?>
									<?php } else { ?>
									<button disabled="disabled" class="btn btn-success btn-xs"><i class="fa fa-plus-circle"></i></button>
									<?php } ?>
								</td>
							</tr>
							<tr>
								<td>
									<?= $text_affiliate; ?>
									<?php if ($affiliate) { ?>
									(<a href="<?= $affiliate; ?>">
										<?= $affiliate_firstname; ?>
										<?= $affiliate_lastname; ?>
									</a>)
									<?php } ?>
								</td>
								<td class="text-right">
									<?= $commission; ?>
								</td>
								<td class="text-center">
									<?php if ($affiliate) { ?>
									<?php if (!$commission_total) { ?>
									<button id="button-commission-add" data-loading-text="<?= $text_loading; ?>"
										data-toggle="tooltip" title="<?= $button_commission_add; ?>"
										class="btn btn-success btn-xs"><i class="fa fa-plus-circle"></i></button>
									<?php } else { ?>
									<button id="button-commission-remove" data-loading-text="<?= $text_loading; ?>"
										data-toggle="tooltip" title="<?= $button_commission_remove; ?>"
										class="btn btn-danger btn-xs"><i class="fa fa-minus-circle"></i></button>
									<?php } ?>
									<?php } else { ?>
									<button disabled="disabled" class="btn btn-success btn-xs"><i class="fa fa-plus-circle"></i></button>
									<?php } ?>
								</td>
							</tr>
							<tr>
								<td>
									<?= $text_date_added; ?>
								</td>
								<td class="text-right">
									<?= $date_added; ?>
								</td>
								<td></td>
							</tr>
							<tr>
								<td>
									<?= $text_sales; ?>
								</td>
								<td class="text-right">
									<?= $sales; ?>
								</td>
								<td></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-info-circle"></i>
					<?= $text_order_detail; ?>
				</h3>
			</div>
			<div class="panel-body">
				<ul class="nav nav-tabs">
					<li class="active"><a href="#tab-order" data-toggle="tab">
							<?= $tab_order; ?>
						</a></li>
					<li><a href="#tab-customer" data-toggle="tab">
							<?= $tab_customer; ?>
						</a></li>
					<li><a href="#tab-purchase" data-toggle="tab">
							<?= $tab_purchase; ?>
						</a></li>
					<li><a href="#tab-vendor" data-toggle="tab">
							<?= $tab_vendor; ?>
						</a></li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane active" id="tab-order">
						<table class="table table-bordered text-left">
							<thead>
								<tr>
									<td style="width: 50%;" colspan="2">
										<?= $text_customer_detail; ?>
									</td>
									<td>
										<?= $text_payment_address; ?>
									</td>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>
										<?= $text_customer; ?>
									</td>
									<td>
										<?php if ($customer) { ?>
										<a href="<?= $customer; ?>" target="_blank">
											<?= $firstname . ' ' . $lastname; ?>
										</a>
										<?= ' - ' . $customer_group; ?>
										<?php } else { ?>
										<?= $firstname . ' ' . $lastname . ' - ' . $customer_group; ?>
										<?php } ?>
									</td>
									<td rowspan="4">
										<?= $payment_address; ?>
									</td>
								</tr>
								<tr>
									<td>
										<?= $text_email; ?>
									</td>
									<td><a href="mailto:<?= $email; ?>">
											<?= $email; ?>
										</a></td>
								</tr>
								<tr>
									<td>
										<?= $text_telephone; ?>
									</td>
									<td>
										<?= $telephone; ?>
									</td>
								</tr>
								<tr>
									<td>
										<?= $text_payment_method; ?>
									</td>
									<td>
										<?= $payment_method; ?>
									</td>
								</tr>
							</tbody>
						</table>
						<div class="table-responsive">
							<table class="table table-bordered" id="order-products">
								<thead>
									<tr>
										<td class="text-left">
											<?= $column_category; ?>
										</td>
										<td class="text-left">
											<?= $column_product; ?>
										</td>
										<td class="text-left">
											<?= $column_model; ?>
										</td>
										<td class="text-right">
											<?= $column_quantity; ?>
										</td>
										<td class="text-right">
											<?= $column_price; ?>
										</td>
										<td class="text-right">
											<?= $column_total; ?>
										</td>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($products as $product) { ?>
									<tr>
										<td class="text-left">
											<?php if ($product['primary_type']) { ?>
											<strong>
												<?= $product['category']; ?>
											</strong>
											<?php } else { ?>
											<?= $product['category']; ?>
											<?php } ?>
										</td>
										<td class="text-left"><a href="<?= $product['href']; ?>">
												<?= $product['name']; ?>
											</a>
											<?php foreach ($product['option'] as $option) { ?>
											<br />
											<?php if ($option['type'] != 'file') { ?>
											&nbsp;<small>&nbsp;-&nbsp;
												<?= $option['name']; ?>:
												<?= $option['value']; ?>
											</small>
											<?php } else { ?>
											&nbsp;<small>&nbsp;-&nbsp;
												<?= $option['name']; ?>: <a href="<?= $option['href']; ?>">
													<?= $option['value']; ?>
												</a>
											</small>
											<?php } ?>
											<?php } ?>
											<?php foreach ($product['attribute'] as $attribute_group => $attributes) { ?>
											<br />
											<small>
												<?= $attribute_group; ?>
											</small>
											<?php foreach ($attributes as $attribute) { ?>
											<br />
											&nbsp;<small>&nbsp;-&nbsp;
												<?= $attribute['name']; ?>:
												<?= $attribute['value']; ?>
											</small>
											<?php } ?>
											<?php } ?>
										</td>
										<td class="text-left">
											<?= $product['model']; ?>
										</td>
										<td class="text-right">
											<?= $product['quantity']; ?>
										</td>
										<td class="text-right">
											<?= $product['price']; ?>
										</td>
										<td class="text-right">
											<?= $product['total']; ?>
										</td>
									</tr>
									<?php } ?>
									<?php foreach ($totals as $total) { ?>
									<tr>
										<td colspan="5" class="text-right">
											<?= $total['title']; ?>
										</td>
										<td class="text-right">
											<?= $total['text']; ?>
										</td>
									</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
						<?php if ($comment) { ?>
						<table class="table table-bordered">
							<thead>
								<tr>
									<td>
										<?= $text_comment; ?>
									</td>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>
										<?= $comment; ?>
									</td>
								</tr>
							</tbody>
						</table>
						<?php } ?>
						<table class="table table-bordered">
							<thead>
								<tr>
									<td class="text-left">
										<?= $column_phase_title; ?>
									</td>
									<td class="text-right">
										<?= $column_phase_amount; ?>
									</td>
									<td class="text-left">
										<?= $column_phase_limit_date; ?>
									</td>
									<td class="text-center">
										<?= $column_phase_status; ?>
									</td>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($payment_phases as $payment_phase) { ?>
								<tr>
									<td class="text-left">
										<?= $payment_phase['title']; ?>
									</td>
									<td class="text-right">
										<?= $payment_phase['amount']; ?>
									</td>
									<td class="text-left">
										<?= $payment_phase['limit_date']; ?>
									</td>
									<td class="text-center">
										<?= $payment_phase['status'] ? '&check;' : '?'; ?>
									</td>
								</tr>
								<?php } ?>
							</tbody>
						</table>
						<div class="panel panel-default">
							<div class="panel-heading">
								<h3 class="panel-title"><i class="fa fa-comment-o"></i>
									<?= $text_history; ?>
								</h3>
							</div>
							<div class="panel-body">
								<ul class="nav nav-tabs">
									<li class="active"><a href="#tab-history" data-toggle="tab">
											<?= $tab_history; ?>
										</a></li>
									<!-- <li><a href="#tab-vendor" data-toggle="tab"><?= $tab_vendor; ?></a></li> -->
									<li><a href="#tab-additional" data-toggle="tab">
											<?= $tab_additional; ?>
										</a></li>
									<?php foreach ($tabs as $tab) { ?>
									<li><a href="#tab-<?= $tab['code']; ?>" data-toggle="tab">
											<?= $tab['title']; ?>
										</a></li>
									<?php } ?>
								</ul>
								<div class="tab-content">
									<div class="tab-pane active" id="tab-history">
										<div id="history"></div>
										<br />
										<fieldset>
											<legend>
												<?= $text_history_add; ?>
											</legend>
											<form class="form-horizontal">
												<div class="form-group">
													<label class="col-sm-2 control-label" for="input-order-status">
														<?= $entry_order_status; ?>
													</label>
													<div class="col-sm-10">
														<select name="order_status_id" id="input-order-status" class="form-control">
															<?php foreach ($order_statuses as $order_status) { ?>
															<?php if ($order_status['order_status_id'] == $order_status_id) { ?>
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
												<div class="form-group">
													<label class="col-sm-2 control-label" for="input-override"><span data-toggle="tooltip"
															title="<?= $help_override; ?>">
															<?= $entry_override; ?>
														</span></label>
													<div class="col-sm-10">
														<input type="checkbox" name="override" value="1" id="input-override" />
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-2 control-label" for="input-notify">
														<?= $entry_notify; ?>
													</label>
													<div class="col-sm-10">
														<input type="checkbox" name="notify" value="1" id="input-notify" />
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-2 control-label" for="input-comment">
														<?= $entry_comment; ?>
													</label>
													<div class="col-sm-10">
														<textarea name="comment" rows="8" id="input-comment" class="form-control"></textarea>
													</div>
												</div>
											</form>
										</fieldset>
										<div class="text-right">
											<button id="button-history" data-loading-text="<?= $text_loading; ?>"
												class="btn btn-primary"><i class="fa fa-plus-circle"></i>
												<?= $button_history_add; ?>
											</button>
										</div>
									</div>
									<div class="tab-pane" id="tab-additional">
										<?php if ($account_custom_fields) { ?>
										<table class="table table-bordered">
											<thead>
												<tr>
													<td colspan="2">
														<?= $text_account_custom_field; ?>
													</td>
												</tr>
											</thead>
											<tbody>
												<?php foreach ($account_custom_fields as $custom_field) { ?>
												<tr>
													<td>
														<?= $custom_field['name']; ?>
													</td>
													<td>
														<?= $custom_field['value']; ?>
													</td>
												</tr>
												<?php } ?>
											</tbody>
										</table>
										<?php } ?>
										<?php if ($payment_custom_fields) { ?>
										<table class="table table-bordered">
											<thead>
												<tr>
													<td colspan="2">
														<?= $text_payment_custom_field; ?>
													</td>
												</tr>
											</thead>
											<tbody>
												<?php foreach ($payment_custom_fields as $custom_field) { ?>
												<tr>
													<td>
														<?= $custom_field['name']; ?>
													</td>
													<td>
														<?= $custom_field['value']; ?>
													</td>
												</tr>
												<?php } ?>
											</tbody>
										</table>
										<?php } ?>
										<table class="table table-bordered">
											<thead>
												<tr>
													<td colspan="2">
														<?= $text_browser; ?>
													</td>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td>
														<?= $text_ip; ?>
													</td>
													<td>
														<?= $ip; ?>
													</td>
												</tr>
												<?php if ($forwarded_ip) { ?>
												<tr>
													<td>
														<?= $text_forwarded_ip; ?>
													</td>
													<td>
														<?= $forwarded_ip; ?>
													</td>
												</tr>
												<?php } ?>
												<tr>
													<td>
														<?= $text_user_agent; ?>
													</td>
													<td>
														<?= $user_agent; ?>
													</td>
												</tr>
												<tr>
													<td>
														<?= $text_accept_language; ?>
													</td>
													<td>
														<?= $accept_language; ?>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
									<?php foreach ($tabs as $tab) { ?>
									<div class="tab-pane" id="tab-<?= $tab['code']; ?>">
										<?= $tab['content']; ?>
									</div>
									<?php } ?>
								</div>
							</div>
						</div>
					</div>
					<div class="tab-pane" id="tab-customer">
						<div id="order-customer"></div>
					</div>
					<div class="tab-pane" id="tab-purchase">
						<div id="order-purchase"></div>
					</div>
					<div class="tab-pane" id="tab-vendor">
						<div id="order-vendor"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		let warning_pos = $('#history').position();

		$('.nav-tabs a[href="#tab-purchase"]').on('click', function () {
			$('.text-danger').remove();

			$('#order-purchase').load('index.php?route=sale/purchase&token=<?= $token; ?>&order_id=<?= $order_id; ?>');
		});

		$('.nav-tabs a[href="#tab-customer"]').on('click', function () {
			$('.text-danger').remove();

			$('#order-customer').load('index.php?route=sale/customer&token=<?= $token; ?>&order_id=<?= $order_id; ?>');
		});

		$('.nav-tabs a[href="#tab-vendor"]').on('click', function () {
			$('.text-danger').remove();

			$('#order-vendor').load('index.php?route=sale/vendor&token=<?= $token; ?>&order_id=<?= $order_id; ?>');
		});

		$('#agreement-print').on('click', function (e) {
			e.preventDefault();

			if (confirm('<?= $text_print_confirm; ?>')) {
				var url = this.href;

				this.closest('li').remove();
				open(url);
			}
		});

		$('#button-checklist').on('click', function () {
			open('index.php?route=sale/order/checklist&token=<?= $token; ?>&order_id=<?= $order_id; ?>');
		});

		$('input[name=\'order-vendor\']').autocomplete({
			'source': function (request, response) {
				$.ajax({
					url: 'index.php?route=sale/order/vendorAutocomplete&token=<?= $token; ?>&filter_name=' + encodeURIComponent(request),
					dataType: 'json',
					success: function (json) {
						response($.map(json, function (item) {
							return {
								label: item['title'],
								value: item['vendor_id']
							}
						}));
					}
				});
			},
			'select': function (item) {
				addOrderVendor(item['value']);
			}
		});

		function addOrderVendor(vendor_id) {
			$.ajax({
				url: 'index.php?route=sale/order/addOrderVendor&token=<?= $token; ?>&order_id=<?= $order_id; ?>',
				type: 'post',
				data: 'vendor_id=' + vendor_id,
				dataType: 'json',
				success: function (json) {
					$('.alert').remove();

					if (json['error']) {
						$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
					}

					if (json['title']) {
						html = '<tr>';
						html += '  <td>' + json['title'] + '</td>';
						html += '  <td><button data-toggle="tooltip" title="<?= $button_vendor_remove; ?>" class="btn btn-danger btn-xs" id="vendor-remove' + vendor_id + '" value="' + vendor_id + '"><i class="fa fa-minus-circle fa-fw"></i></button></td></tr>';

						$('#order-vendors tbody').append(html);

						$('#order-purchase').load('index.php?route=sale/purchase&token=<?= $token; ?>&order_id=<?= $order_id; ?>');
						$('#order-vendor').load('index.php?route=sale/vendor&token=<?= $token; ?>&order_id=<?= $order_id; ?>');

						$('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
					}
				},

				error: function (xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});

		}

		$('#order-vendors tbody').on('click', 'button[id^=\'vendor-remove\']', function () {
			if (confirm('<?= $text_confirm; ?>')) {
				var node = this;

				vendor_id = encodeURIComponent($(node).attr('value'));

				$.ajax({
					url: 'index.php?route=sale/order/deleteOrderVendor&token=<?= $token; ?>&order_id=<?= $order_id; ?>',
					type: 'post',
					data: 'vendor_id=' + vendor_id,
					dataType: 'json',
					success: function (json) {
						$('.alert').remove();

						if (json['error']) {
							$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
						}

						if (json['title']) {
							html = '<li><a href="" value="' + vendor_id + '" id="vendor-add' + vendor_id + '">' + json['title'] + '</a></li>'

							$('#order-vendors-add ul').append(html);

							$(node).closest('tr').remove();

							$('#order-purchase').load('index.php?route=sale/purchase&token=<?= $token; ?>&order_id=<?= $order_id; ?>');
							$('#order-vendor').load('index.php?route=sale/vendor&token=<?= $token; ?>&order_id=<?= $order_id; ?>');

							$('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
						}
					},

					error: function (xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});
			}
		});

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
					}
				},
				error: function (xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		});

		$(document).delegate('#button-invoice', 'click', function () {
			$.ajax({
				url: 'index.php?route=sale/order/createinvoiceno&token=<?= $token; ?>&order_id=<?= $order_id; ?>',
				dataType: 'json',
				beforeSend: function () {
					$('#button-invoice').button('loading');
				},
				complete: function () {
					$('#button-invoice').button('reset');
				},
				success: function (json) {
					$('.alert').remove();

					if (json['error']) {
						$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
					}

					if (json['invoice_no']) {
						$('#invoice').html(json['invoice_no']);

						$('#button-invoice').replaceWith('<button disabled="disabled" class="btn btn-success btn-xs"><i class="fa fa-cog"></i></button>');
					}
				},
				error: function (xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		});

		$(document).delegate('#button-reward-add', 'click', function () {
			$.ajax({
				url: 'index.php?route=sale/order/addreward&token=<?= $token; ?>&order_id=<?= $order_id; ?>',
				type: 'post',
				dataType: 'json',
				beforeSend: function () {
					$('#button-reward-add').button('loading');
				},
				complete: function () {
					$('#button-reward-add').button('reset');
				},
				success: function (json) {
					$('.alert').remove();

					if (json['error']) {
						$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
					}

					if (json['success']) {
						$('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');

						$('#button-reward-add').replaceWith('<button id="button-reward-remove" data-toggle="tooltip" title="<?= $button_reward_remove; ?>" class="btn btn-danger btn-xs"><i class="fa fa-minus-circle"></i></button>');
					}
				},
				error: function (xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		});

		$(document).delegate('#button-reward-remove', 'click', function () {
			$.ajax({
				url: 'index.php?route=sale/order/removereward&token=<?= $token; ?>&order_id=<?= $order_id; ?>',
				type: 'post',
				dataType: 'json',
				beforeSend: function () {
					$('#button-reward-remove').button('loading');
				},
				complete: function () {
					$('#button-reward-remove').button('reset');
				},
				success: function (json) {
					$('.alert').remove();

					if (json['error']) {
						$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
					}

					if (json['success']) {
						$('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');

						$('#button-reward-remove').replaceWith('<button id="button-reward-add" data-toggle="tooltip" title="<?= $button_reward_add; ?>" class="btn btn-success btn-xs"><i class="fa fa-plus-circle"></i></button>');
					}
				},
				error: function (xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		});

		$(document).delegate('#button-commission-add', 'click', function () {
			$.ajax({
				url: 'index.php?route=sale/order/addcommission&token=<?= $token; ?>&order_id=<?= $order_id; ?>',
				type: 'post',
				dataType: 'json',
				beforeSend: function () {
					$('#button-commission-add').button('loading');
				},
				complete: function () {
					$('#button-commission-add').button('reset');
				},
				success: function (json) {
					$('.alert').remove();

					if (json['error']) {
						$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
					}

					if (json['success']) {
						$('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');

						$('#button-commission-add').replaceWith('<button id="button-commission-remove" data-toggle="tooltip" title="<?= $button_commission_remove; ?>" class="btn btn-danger btn-xs"><i class="fa fa-minus-circle"></i></button>');
					}
				},
				error: function (xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		});

		$(document).delegate('#button-commission-remove', 'click', function () {
			$.ajax({
				url: 'index.php?route=sale/order/removecommission&token=<?= $token; ?>&order_id=<?= $order_id; ?>',
				type: 'post',
				dataType: 'json',
				beforeSend: function () {
					$('#button-commission-remove').button('loading');
				},
				complete: function () {
					$('#button-commission-remove').button('reset');
				},
				success: function (json) {
					$('.alert').remove();

					if (json['error']) {
						$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
					}

					if (json['success']) {
						$('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');

						$('#button-commission-remove').replaceWith('<button id="button-commission-add" data-toggle="tooltip" title="<?= $button_commission_add; ?>" class="btn btn-success btn-xs"><i class="fa fa-plus-circle"></i></button>');
					}
				},
				error: function (xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		});

		var token = '';

		// Login to the API
		$.ajax({
			url: '<?= $store_url; ?>index.php?route=api/login',
			type: 'post',
			dataType: 'json',
			data: 'key=<?= $api_key; ?>',
			crossDomain: true,
			success: function (json) {
				// $('.alert').remove();

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

		$('#history').delegate('.pagination a', 'click', function (e) {
			e.preventDefault();

			$('#history').load(this.href);
		});

		$('#history').load('index.php?route=sale/order/history&token=<?= $token; ?>&order_id=<?= $order_id; ?>');

		$('#order-customer').on('click', '.pagination a', function (e) {
			e.preventDefault();

			$('#order-customer').load(this.href);
		});

		$('#order-vendor').on('click', '.pagination a', function (e) {
			e.preventDefault();

			$('#order-vendor').load(this.href);
		});

		$('#button-history').on('click', function () {
			$.ajax({
				url: '<?= $store_url; ?>index.php?route=api/order/history&token=' + token + '&order_id=<?= $order_id; ?>',
				type: 'post',
				dataType: 'json',
				data: 'order_status_id=' + encodeURIComponent($('select[name=\'order_status_id\']').val()) + '&notify=' + ($('input[name=\'notify\']').prop('checked') ? 1 : 0) + '&override=' + ($('input[name=\'override\']').prop('checked') ? 1 : 0) + '&comment=' + encodeURIComponent($('textarea[name=\'comment\']').val()) + '&user_id=<?= $user_id; ?>',
				beforeSend: function () {
					$('#button-history').button('loading');
				},
				complete: function () {
					$('#button-history').button('reset');
				},
				success: function (json) {
					$('.alert').remove();
					$('.text-danger').remove();

					if (json['error']) {
						$('#history').before('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
						$('html, body').animate({ scrollTop: warning_pos.top - 70 }, 500);
					}

					if (json['error_date']) {
						$('#input-date').parent().after('<div class="text-danger">' + json['error_date'] + '</div>');
					}

					if (json['error_amount']) {
						$('#input-amount').after('<div class="text-danger">' + json['error_amount'] + '</div>');
					}

					if (json['success']) {
						// $('#history').load('index.php?route=sale/order/history&token=<?= $token; ?>&order_id=<?= $order_id; ?>');

						// $('#history').before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

						$('input[name=\'date\']').val('');
						$('input[name=\'amount\']').val('');
						$('textarea[name=\'comment\']').val('');
						location.reload();
					}
				},
				error: function (xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		});

		$('#button-expired').on('click', function () {
			$.ajax({
				url: '<?= $store_url; ?>index.php?route=api/order/expired&token=' + token + '&order_id=<?= $order_id; ?>',
				type: 'post',
				dataType: 'json',
				data: 'user_id=<?= $user_id; ?>',
				beforeSend: function () {
					$('#button-expired').button('loading');
				},
				complete: function () {
					$('#button-expired').button('reset');
				},
				success: function (json) {
					if (json['error']) {
						$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

						$('html, body').animate({ scrollTop: warning_pos.top - 70 }, 500);
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
				error: function (xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		});
	</script>
	<script type="text/javascript">
		// Currency Format
		function getNumber(str) {
			return Number(str.replace(/(?!-)[^0-9.]/g, ""));
		};

		$('#content').on('keyup', 'input.currency', function () {
			let node = this;
			$(node).val(getNumber($(node).val()).toLocaleString());
		});

		// $('input.currency').trigger('keyup');

		// Datetime Picker
		$('.date').datetimepicker({
			pickTime: false
		});
	</script>
</div>
<?= $footer; ?>
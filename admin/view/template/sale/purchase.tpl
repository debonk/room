<legend>
	<?= $text_product_list; ?>
</legend>
<div class="table-responsive">
	<?php if ($success) { ?>
	<div class="alert alert-success"><i class="fa fa-check-circle"></i>
		<?= $success; ?>
		<button type="button" class="close" data-dismiss="alert">&times;</button>
	</div>
	<?php } ?>
	<table class="table table-bordered" id="order-products">
		<thead>
			<tr>
				<td class="text-left">
					<?= $column_product; ?>
				</td>
				<td class="text-right">
					<?= $column_total_quantity; ?>
				</td>
				<td class="text-left">
					<?= $column_vendor; ?>
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
			<?php foreach ($product['purchase'] as $key => $purchase) { ?>
			<tr id="purchase[<?= $purchase['vendor_id']; ?>][<?= $product['product_id']; ?>]">
				<?php if (!$key) { ?>
				<td class="text-left" rowspan="<?= $product['rowspan']; ?>">
					<b>
						<?= $product['name']; ?>
					</b>
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
				<td class="text-right" rowspan="<?= $product['rowspan']; ?>">
					<?= $product['quantity']; ?>
				</td>
				<?php } ?>
				<td class="text-left">
					<?= $purchase['vendor_name']; ?>
					<?php if ($purchase['excluded']) { ?>
					<div class="text-danger">
						<?= $text_vendor_excluded; ?>
					</div>
					<?php } ?>
				</td>
				<td class="text-right">
					<input type="text" name="purchase[<?= $purchase['vendor_id']; ?>][<?= $product['product_id']; ?>][quantity]"
						value="<?= $purchase['quantity']; ?>"
						data-vendor-product="[<?= $purchase['vendor_id']; ?>][<?= $product['product_id']; ?>]"
						class="form-control currency" <?=$purchase['locked'] ? 'readonly="readonly"' : '' ; ?>/>
				</td>
				<td class="text-right">
					<?= $purchase['purchase_price_text']; ?>
					<input type="hidden"
						name="purchase[<?= $purchase['vendor_id']; ?>][<?= $product['product_id']; ?>][purchase_price]"
						value="<?= $purchase['purchase_price']; ?>" />
				</td>
				<td class="text-right">
					<input type="text" id="purchase[<?= $purchase['vendor_id']; ?>][<?= $product['product_id']; ?>][total]"
						value="<?= $purchase['total']; ?>" readonly="readonly" class="form-control currency" />
				</td>
			</tr>
			<?php } ?>
			<?php } ?>
		</tbody>
	</table>
</div>
<div class="text-right">
	<button id="button-purchase" data-loading-text="<?= $text_loading; ?>" class="btn btn-primary"><i
			class="fa fa-check"></i>
		<?= $button_purchase; ?>
	</button>
</div>
<br />
<legend>
	<?= $text_purchase; ?>
</legend>
<div class="table-responsive">
	<table class="table table-bordered" id="purchase-order">
		<thead>
			<tr>
				<td class="text-left">
					<?= $column_vendor; ?>
				</td>
				<td class="text-left">
					<?= $column_product; ?>
				</td>
				<td class="text-right">
					<?= $column_subtotal; ?>
				</td>
				<td class="text-right">
					<?= $column_adjustment; ?>
				</td>
				<td class="text-right">
					<?= $column_total; ?>
				</td>
				<td class="text-left">
					<?= $column_status; ?>
				</td>
				<td class="text-left">
					<?= $column_action; ?>
				</td>
			</tr>
		</thead>
		<tbody>
			<?php if ($order_purchases) { ?>
			<?php foreach ($order_purchases as $order_purchase) { ?>
			<tr>
				<td class="text-left td-top" rowspan="2">
					<p><?= $order_purchase['vendor_name']; ?></p>
					<p><?= $column_reference . ': ' . $order_purchase['reference']; ?></p>
				</td>
				<td class="text-left">
					<?php foreach ($order_purchase['order_product'] as $order_product) { ?>
					<?= $order_product['name']; ?><br />
					<cite>&nbsp;&nbsp;
						<?= $order_product['text_detail']; ?>
					</cite><br />
					<?php } ?>
				</td>
				<td class="text-right">
					<?= $order_purchase['subtotal_text']; ?>
					<input type="hidden" name="order_purchase[<?= $order_purchase['vendor_id']; ?>][subtotal]"
						value="<?= $order_purchase['subtotal']; ?>" readonly="readonly" />
				</td>
				<td class="text-right">
					<?php if ($order_purchase['completed']) { ?>
					<?= $order_purchase['adjustment_text']; ?>
					<?php } else { ?>
					<input type="text" name="order_purchase[<?= $order_purchase['vendor_id']; ?>][adjustment]"
						value="<?= $order_purchase['adjustment']; ?>" data-vendor-id="[<?= $order_purchase['vendor_id']; ?>]"
						class="form-control currency" />
					<?php } ?>
				</td>
				<td class="text-right">
					<?php if ($order_purchase['completed']) { ?>
					<?= $order_purchase['total_text']; ?>
					<?php } else { ?>
					<input type="text" name="order_purchase[<?= $order_purchase['vendor_id']; ?>][total]"
						value="<?= $order_purchase['total']; ?>" class="form-control currency" readonly="readonly" />
					<?php } ?>
				</td>
				<td class="text-left" rowspan="2">
					<?= $order_purchase['status']; ?>
				</td>
				<td class="text-left nowrap" rowspan="2">
					<div class="btn-group">
						<button type="button" data-toggle="dropdown" class="btn btn-info btn-sm dropdown-toggle"
							id="button-purchase-order"><i class="fa fa-shopping-cart"></i>
						</button>
						<ul class="dropdown-menu dropdown-menu-right">
							<li><a href="#" id="order-purchase[<?= $order_purchase['vendor_id']; ?>][preview]"
									data-vendor-id="<?= $order_purchase['vendor_id']; ?>">
									<?= $text_preview; ?>
								</a></li>
							<?php if (!$order_purchase['printed']) { ?>
							<li><a href="#" id="order-purchase[<?= $order_purchase['vendor_id']; ?>][print]"
									data-vendor-id="<?= $order_purchase['vendor_id']; ?>" data-print="1">
									<?= $text_print; ?>
								</a></li>
							<?php } ?>
						</ul>
					</div>
					<?php if ($order_purchase['completed']) { ?>
					<button type="button" class="btn btn-success btn-sm" disabled="disabled"><i class="fa fa-check"></i></button>
					<?php } else { ?>
					<button type="button" value="<?= $order_purchase['vendor_id']; ?>"
						id="button-complete<?= $order_purchase['vendor_id']; ?>" data-loading-text="<?= $text_loading; ?>"
						data-toggle="tooltip" title="<?= $button_complete; ?>" class="btn btn-success btn-sm"><i
							class="fa fa-check"></i></button>
					<?php } ?>
				</td>
			</tr>
			<tr>
				<td colspan="4">
					<?= $entry_vendor_reference; ?>
					<?php if ($order_purchase['completed']) { ?>
					<?= $order_purchase['vendor_reference']; ?><br>
					<?php } else { ?>
					<input type="text" name="order_purchase[<?= $order_purchase['vendor_id']; ?>][vendor_reference]"
						value="<?= $order_purchase['vendor_reference']; ?>" class="form-control" />
					<?php } ?>
					<?= $entry_comment; ?>
					<?php if ($order_purchase['completed']) { ?>
					<?= $order_purchase['comment']; ?><br>
					<?php } else { ?>
					<textarea name="order_purchase[<?= $order_purchase['vendor_id']; ?>][comment]" rows="1"
						class="form-control"><?= $order_purchase['comment']; ?></textarea>
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
<script type="text/javascript">
	$('input[name^=\'purchase\'].currency').on('keyup', function () {
		let idx = $(this).attr('data-vendor-product');
		let quantity = getNumber($('input[name=\'purchase' + idx + '[quantity]\']').val());
		let price = $('input[name=\'purchase' + idx + '[purchase_price]\']').val();

		let total = quantity * price;

		$('input[id=\'purchase' + idx + '[total]\']').val((total).toLocaleString());
	});

	$('input[name^=\'order_purchase\'].currency').on('keyup', function () {
		let idx = $(this).attr('data-vendor-id');
		let adjustment = getNumber($(this).val());
		let total = parseInt($('input[name=\'order_purchase' + idx + '[subtotal]\']').val()) + adjustment;

		$('input[name=\'order_purchase' + idx + '[total]\']').val((total).toLocaleString());
	});

	$('input.currency').trigger('keyup');
</script>
<script type="text/javascript">
	let warning_pos = $('#order-purchase #purchase-order').position();

	$('#button-purchase').on('click', function () {
		let data = $('#order-products input[name^=\'purchase\']');

		$.ajax({
			url: 'index.php?route=sale/purchase/purchase&token=<?= $token; ?>&order_id=<?= $order_id; ?>',
			type: 'post',
			dataType: 'json',
			data: data,
			beforeSend: function () {
				$('#button-purchase').button('loading');
			},
			complete: function () {
				$('#button-purchase').button('reset');
			},
			success: function (json) {
				$('.alert').remove();

				if (json['error']) {
					$('#order-purchase #order-products').before('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				}

				if (json['success']) {
					$('#order-purchase').load('index.php?route=sale/purchase/orderPurchase&token=<?= $token; ?>&order_id=<?= $order_id; ?>');

					$('#order-purchase').before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				}
			},
			error: function (xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});

	$('button[id^=\'button-complete\']').on('click', function () {
		if (confirm('<?= $text_complete_confirm; ?>')) {
			let idx = this.value;
			let data = {
				vendor_id: idx,
				adjustment: getNumber($('#purchase-order input[name^=\'order_purchase[' + idx + '][adjustment]\']').val()),
				comment: $('#purchase-order textarea[name^=\'order_purchase[' + idx + '][comment]\']').val(),
				vendor_reference: $('#purchase-order input[name^=\'order_purchase[' + idx + '][vendor_reference]\']').val()
			};

			$.ajax({
				url: 'index.php?route=sale/purchase/complete&token=<?= $token; ?>&order_id=<?= $order_id; ?>',
				type: 'post',
				dataType: 'json',
				data: data,
				beforeSend: function () {
					$('#button-complete').button('loading');
				},
				complete: function () {
					$('#button-complete').button('reset');
				},
				success: function (json) {
					$('.alert, .text-danger').remove();

					if (json['error']) {
						$('#order-purchase #purchase-order').before('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
						$('html, body').animate({ scrollTop: warning_pos.top - 70 }, 500);
					}

					if (json['error_vendor_reference']) {
						$('#purchase-order input[name^=\'order_purchase[' + idx + '][vendor_reference]\']').after('<div class="text-danger">' + json['error_vendor_reference'] + '</div>');
					}

					if (json['success']) {
						$('#order-purchase').load('index.php?route=sale/purchase/orderPurchase&token=<?= $token; ?>&order_id=<?= $order_id; ?>');

						$('#order-purchase').before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
						$('html, body').animate({ scrollTop: warning_pos.top - 70 }, 500);
					}
				},
				error: function (xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		}
	});

	$('a[id^=\'order-purchase\']').on('click', function (e) {
		e.preventDefault();

		let vendor_id = $(this).data('vendor-id');
		let url, comment;

		if ($(this).data('print')) {
			if (confirm('<?= $text_print_confirm; ?>')) {
				url = 'index.php?route=sale/purchase/purchaseOrder&token=<?= $token; ?>&order_id=<?= $order_id; ?>&vendor_id=' + vendor_id + '&print=1';

				this.closest('li').remove();
			}
		} else {
			url = 'index.php?route=sale/purchase/purchaseOrder&token=<?= $token; ?>&order_id=<?= $order_id; ?>&vendor_id=' + vendor_id;
		}

		if (typeof $('#purchase-order textarea[name^=\'order_purchase[' + vendor_id + '][comment]\']').val() !== 'undefined') {
			comment = $('#purchase-order textarea[name^=\'order_purchase[' + vendor_id + '][comment]\']').val();
		} else {
			comment = '';
		}

		if (url) {
			$.ajax({
				url: url,
				type: 'post',
				dataType: 'json',
				data: '&comment=' + comment,
				success: function (json) {
					if (json['purchase_order_url']) {
						open('index.php?route=sale/purchase/purchaseOrderDocument' + json['purchase_order_url']);
					}
				},
				error: function (xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		}
	});
</script>
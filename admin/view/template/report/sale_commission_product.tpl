<div class="table-responsive">
	<legend><?= $text_commission2; ?></legend>
	<table class="table table-bordered table-hover">
		<thead>
			<tr>
				<td>
					<?php if ($sort == 'username') { ?>
					<a href="<?= $sort_username; ?>" class="<?= strtolower($order); ?>">
						<?= $column_username; ?>
					</a>
					<?php } else { ?>
					<a href="<?= $sort_username; ?>">
						<?= $column_username; ?>
					</a>
					<?php } ?>
				</td>
				<td>
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
				<td>
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
				<td>
					<?= $column_order_detail; ?>
				</td>
				<td>
					<?= $column_customer; ?>
				</td>
				<td class="text-right">
					<?= $column_order_total; ?>
				</td>
				<td>
					<?php if ($sort == 'order_status') { ?>
					<a href="<?= $sort_order_status; ?>" class="<?= strtolower($order); ?>">
						<?= $column_order_status; ?>
					</a>
					<?php } else { ?>
					<a href="<?= $sort_order_status; ?>">
						<?= $column_order_status; ?>
					</a>
					<?php } ?>
				</td>
				<td class="text-right">
					<?= $column_commission; ?>
				</td>
			</tr>
		</thead>
		<tbody>
			<?php if ($commissions2) { ?>
			<?php foreach ($commissions2 as $order) { ?>
			<tr>
				<td>
					<?= $order['username']; ?>
				</td>
				<td>
					<?= $order['date_added']; ?>
				</td>
				<td>
					<?= $order['event_date']; ?>
				</td>
				<td>
					<?= $order['primary_product']; ?><br />
					<?= $text_title . ': ' . $order['title']; ?><br />
					<i><?= $text_invoice . ': '; ?><a href="<?= $order['href']; ?>" target="_blank" rel="noopener noreferrer">
						<?= $order['invoice_no']; ?>
					</a></i>
				</td>
				<td>
					<?= $order['customer']; ?>
				</td>
				<td class="text-right">
					<?= $order['total']; ?>
				</td>
				<td>
					<?= $order['order_status']; ?>
				</td>
				<td class="text-right">
					<?= $order['commission2']; ?>
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
		<tfoot>
			<tr class="text-right">
				<td colspan="7">
					<?= $text_total; ?>
				</td>
				<td>
					<?= $commission2_total; ?>
				</td>
			</tr>
		</tfoot>
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
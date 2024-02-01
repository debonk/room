<?= $header; ?>
<?= $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
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
				<h3 class="panel-title"><i class="fa fa-bar-chart"></i>
					<?= $text_list; ?>
				</h3>
			</div>
			<div class="panel-body">
				<div class="well">
					<div class="row">
						<div class="col-sm-6">
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
								<label class="control-label" for="input-date-end">
									<?= $entry_date_end; ?>
								</label>
								<div class="input-group date">
									<input type="text" name="filter_date_end" value="<?= $filter_date_end; ?>"
										placeholder="<?= $entry_date_end; ?>" data-date-format="YYYY-MM-DD" id="input-date-end"
										class="form-control" />
									<span class="input-group-btn">
										<button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
									</span>
								</div>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label class="control-label" for="input-group">
									<?= $entry_group; ?>
								</label>
								<select name="filter_group" id="input-group" class="form-control">
									<?php foreach ($groups as $group) { ?>
									<?php if ($group['value'] == $filter_group) { ?>
									<option value="<?= $group['value']; ?>" selected="selected">
										<?= $group['text']; ?>
									</option>
									<?php } else { ?>
									<option value="<?= $group['value']; ?>">
										<?= $group['text']; ?>
									</option>
									<?php } ?>
									<?php } ?>
								</select>
							</div>
							<div class="form-group">
								<label class="control-label" for="input-status">
									<?= $entry_status; ?>
								</label>
								<select name="filter_order_status_id" id="input-status" class="form-control">
									<option value="0">
										<?= $text_all_status; ?>
									</option>
									<?php foreach ($order_statuses as $order_status) { ?>
									<?php if ($order_status['order_status_id'] == $filter_order_status_id) { ?>
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
							<button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i>
								<?= $button_filter; ?>
							</button>
						</div>
					</div>
				</div>
				<div class="table-responsive">
					<table class="table table-bordered text-left">
						<thead>
							<tr>
								<td>
									<?= $column_period; ?>
								</td>
								<td>
									<?= $column_venue; ?>
								</td>
								<td>
									<?= $column_date_start; ?>
								</td>
								<td>
									<?= $column_date_end; ?>
								</td>
								<td class="text-right">
									<?= $column_orders; ?>
								</td>
								<td class="text-right">
									<?= $column_tax; ?>
								</td>
								<td class="text-right">
									<?= $column_total; ?>
								</td>
							</tr>
						</thead>
						<tbody>
							<?php if ($orders) { ?>
							<?php foreach ($orders as $key => $order_data) { ?>
								<tr>
									<th colspan="8"><?= $key; ?></th>
								</tr>
							<?php foreach ($order_data as $order) { ?>
							<tr>
								<td></td>
								<td>
									<a href="<?= $order['href']; ?>" target="_blank" rel="noopener noreferrer">
										<?= $order['venue_code']; ?>
									</a>
								</td>
								<td>
									<?= $order['date_start']; ?>
								</td>
								<td>
									<?= $order['date_end']; ?>
								</td>
								<td class="text-right">
									<?= $order['orders']; ?>
								</td>
								<td class="text-right">
									<?= $order['tax']; ?>
								</td>
								<td class="text-right">
									<?= $order['total']; ?>
								</td>
							</tr>
							<?php } ?>
							<tr>
								<th colspan="4" class="text-right"><cite><?= $text_subtotal; ?></cite></th>
								<th class="text-right">
									<cite><?= $subtotal[$key]['orders_count']; ?></cite>
								</th>
								<th class="text-right">
									<cite><?= $subtotal[$key]['taxes_total']; ?></cite>
								</th>
								<th class="text-right">
									<cite><?= $subtotal[$key]['totals_total']; ?></cite>
								</th>
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
			url = 'index.php?route=report/sale_order&token=<?= $token; ?>';

			var filter_date_start = $('input[name=\'filter_date_start\']').val();

			if (filter_date_start) {
				url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
			}

			var filter_date_end = $('input[name=\'filter_date_end\']').val();

			if (filter_date_end) {
				url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
			}

			var filter_group = $('select[name=\'filter_group\']').val();

			if (filter_group) {
				url += '&filter_group=' + encodeURIComponent(filter_group);
			}

			var filter_order_status_id = $('select[name=\'filter_order_status_id\']').val();

			if (filter_order_status_id != 0) {
				url += '&filter_order_status_id=' + encodeURIComponent(filter_order_status_id);
			}

			location = url;
		});
	</script>
	<script type="text/javascript">
		$(document).keypress(function (e) {
			if (e.which == 13) {
				$("#button-filter").click();
			}
		});

		$('.date').datetimepicker({
			pickTime: false
		});
	</script>
</div>
<?= $footer; ?>
<?=$header; ?>
<?=$column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<h1>
				<?=$heading_title; ?>
			</h1>
			<ul class="breadcrumb">
				<?php foreach ($breadcrumbs as $breadcrumb) { ?>
				<li><a href="<?=$breadcrumb['href']; ?>">
						<?=$breadcrumb['text']; ?>
					</a></li>
				<?php } ?>
			</ul>
		</div>
	</div>
	<div class="container-fluid">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-bar-chart"></i>
					<?=$text_list; ?>
				</h3>
				<h3 class="panel-title pull-right">
					<?=$date_start . ' - ' . $date_end; ?>
				</h3>
			</div>
			<div class="panel-body">
				<div class="well">
					<div class="row">
						<div class="col-sm-12 col-md-6">
							<div class="form-group">
								<label class="control-label" for="input-date-start">
									<?=$entry_date_start; ?>
								</label>
								<div class="input-group date">
									<input type="text" name="filter[date_start]" value="<?=$filter['date_start']; ?>"
										placeholder="<?=$entry_date_start; ?>" data-date-format="YYYY-MM-DD" id="input-date-start"
										class="form-control" />
									<span class="input-group-btn">
										<button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
									</span>
								</div>
							</div>
						</div>
						<div class="col-sm-12 col-md-6">
							<div class="form-group">
								<label class="control-label" for="input-date-end">
									<?=$entry_date_end; ?>
								</label>
								<div class="input-group date">
									<input type="text" name="filter[date_end]" value="<?=$filter['date_end']; ?>"
										placeholder="<?=$entry_date_end; ?>" data-date-format="YYYY-MM-DD" id="input-date-end"
										class="form-control" />
									<span class="input-group-btn">
										<button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
									</span>
								</div>
							</div>
							<button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i>
								<?=$button_filter; ?>
							</button>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6 table-responsive">
						<table class="table table-bordered table-striped table-hover">
							<thead>
								<tr>
									<th class="text-left" colspan="3">
										<?= strtoupper($text_revenue); ?>
									</th>
								</tr>
							</thead>
							<tbody>
								<?php if (isset($accounts['revenue'])) { ?>
								<?php foreach ($accounts['revenue'] as $type => $revenue_accounts) { ?>
								<tr>
									<th class="text-left" colspan="3">
										<?= $text_type[$type]; ?>
									</th>
								</tr>
								<?php foreach ($revenue_accounts as $account) { ?>
								<tr>
									<td class="text-left pl-1">
										<?= $account['account_id']; ?>
									</td>
									<td class="text-left">
										<a href="<?php echo $account['href']; ?>" target="_blank">
											<?= $account['name']; ?>
										</a>
									</td>
									<td class="text-right">
										<?= $account['balance']; ?>
									</td>
								</tr>
								<?php } ?>
								<?php } ?>
								<?php } ?>
							</tbody>
							<tfoot>
								<tr>
									<td class="text-right" colspan="2">
										<?= $text_total; ?>
									</td>
									<td class="text-right">
										<?= $total_revenue; ?>
									</td>
								</tr>
							</tfoot>
						</table>
					</div>
					<div class="col-md-6 table-responsive">
						<table class="table table-bordered table-striped table-hover">
							<thead>
								<tr>
									<th class="text-left" colspan="3">
										<?= strtoupper($text_expense); ?>
									</th>
								</tr>
							</thead>
							<tbody>
								<?php if (isset($accounts['expense'])) { ?>
								<?php foreach ($accounts['expense'] as $type => $expense_accounts) { ?>
								<tr>
									<th class="text-left" colspan="3">
										<?= $text_type[$type]; ?>
									</th>
								</tr>
								<?php foreach ($expense_accounts as $account) { ?>
								<tr>
									<td class="text-left pl-1">
										<?= $account['account_id']; ?>
									</td>
									<td class="text-left">
										<a href="<?php echo $account['href']; ?>" target="_blank">
											<?= $account['name']; ?>
										</a>
									</td>
									<td class="text-right">
										<?= $account['balance']; ?>
									</td>
								</tr>
								<?php } ?>
								<?php } ?>
								<?php } ?>
							</tbody>
							<tfoot>
								<tr>
									<td class="text-right" colspan="2">
										<?= $text_total; ?>
									</td>
									<td class="text-right">
										<?= $total_expense; ?>
									</td>
								</tr>
								<tr>
									<td class="text-right" colspan="2">
										<?= $text_net_profit; ?>
									</td>
									<td class="text-right">
										<?= $net_profit; ?>
									</td>
								</tr>
							</tfoot>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		$('#button-filter').on('click', function () {
			url = 'index.php?route=report/sci&token=<?=$token; ?>';

			let filter_items = [
				'date_start',
				'date_end'
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
<?=$footer; ?>
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
						<div class="col-sm-4">
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
						</div>
						<div class="col-sm-4">
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
						</div>
						<div class="col-sm-4">
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
				<ul class="nav nav-tabs">
					<li class="active"><a href="#tab-commission" data-toggle="tab">
							<?= $tab_summary; ?>
						</a></li>
					<li><a href="#tab-commission1" data-toggle="tab">
							<?= $tab_commission1; ?>
						</a></li>
					<li><a href="#tab-commission2" data-toggle="tab">
							<?= $tab_commission2; ?>
						</a></li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane active" id="tab-commission">
						<legend><?= $text_summary; ?></legend>
						<div class="table-responsive">
							<table class="table table-bordered table-hover">
								<thead>
									<tr>
										<td rowspan="2">
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
										<td colspan="2" class="text-center">
											<?= $column_commission1; ?>
										</td>
										<td colspan="2" class="text-center">
											<?= $column_commission2; ?>
										</td>
										<td class="text-right" rowspan="2">
											<?= $column_total; ?>
										</td>
									</tr>
									<tr class="text-right">
										<td>
											<?= $column_order_count; ?>
										</td>
										<td>
											<?= $column_commission; ?>
										</td>
										<td>
											<?= $column_order_count; ?>
										</td>
										<td>
											<?= $column_commission; ?>
										</td>
									</tr>
								</thead>
								<tbody>
									<?php if ($commissions) { ?>
									<?php foreach ($commissions as $commission) { ?>
									<tr class="text-right">
										<td class="text-left">
											<?= $commission['username']; ?>
										</td>
										<td>
											<?= $commission['commission1_count']; ?>
										</td>
										<td>
											<?= $commission['commission1_total']; ?>
										</td>
										<td>
											<?= $commission['commission2_count']; ?>
										</td>
										<td>
											<?= $commission['commission2_total']; ?>
										</td>
										<td>
											<?= $commission['commission_total']; ?>
										</td>
									</tr>
									<?php } ?>
									<?php } else { ?>
									<tr>
										<td class="text-center" colspan="6">
											<?= $text_no_results; ?>
										</td>
									</tr>
									<?php } ?>
								</tbody>
								<tfoot>
									<tr class="text-right">
										<td colspan="5">
											<?= $text_total; ?>
										</td>
										<td>
											<?= $total; ?>
										</td>
									</tr>
								</tfoot>
							</table>
						</div>
						<div class="row">
							<div class="col-sm-12 text-right">
								<?= $results; ?>
							</div>
						</div>
					</div>
					<div class="tab-pane" id="tab-commission1">
						<div id="order-commission1"></div>
					</div>
					<div class="tab-pane" id="tab-commission2">
						<div id="order-commission2"></div>
					</div>
				</div>

			</div>
		</div>
	</div>
	<script type="text/javascript">
		$('#button-filter').on('click', function () {
			url = 'index.php?route=report/sale_commission&token=<?= $token; ?>';

			let filter_items = JSON.parse('<?= $filter_items; ?>');

			let filter = [];

			for (let i = 0; i < filter_items.length; i++) {
				filter[filter_items[i]] = $('[name=\'filter[' + filter_items[i] + ']\']').val();

				if (filter[filter_items[i]]) {
					url += '&filter_' + filter_items[i] + '=' + encodeURIComponent(filter[filter_items[i]]);
				}
			}

			location = url;
		});

		$('.nav-tabs a[href="#tab-commission1"]').on('click', function () {
			$('#order-commission1').load('index.php?route=report/sale_commission/commissionOrder&token=<?= $token . $url; ?>');
		});

		$('#order-commission1').on('click', 'td a, .pagination a', function (e) {
			e.preventDefault();

			$('#order-commission1').load(this.href);
		});

		$('.nav-tabs a[href="#tab-commission2"]').on('click', function () {
			$('#order-commission2').load('index.php?route=report/sale_commission/commissionProduct&token=<?= $token . $url; ?>');
		});

		$('#order-commission2').on('click', 'td a, .pagination a', function (e) {
			e.preventDefault();

			$('#order-commission2').load(this.href);
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
<?= $footer; ?>
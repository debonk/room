<?php echo $header; ?>
<?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
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
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-bar-chart"></i>
					<?php echo $text_list; ?>
				</h3>
			</div>
			<div class="panel-body">
				<div class="well">
					<div class="row">
						<div class="col-sm-12 col-md-6">
							<div class="form-group">
								<label class="control-label" for="input-account">
									<?php echo $entry_component; ?>
								</label>
								<select name="filter[component]" id="input-component" class="form-control">
									<option value="*">
										<?php echo $text_all; ?>
									</option>
									<?php foreach ($components as $component) { ?>
									<?php if ($component['value'] == $filter['component']) { ?>
									<option value="<?php echo $component['value']; ?>" selected="selected">
										<?php echo $component['text']; ?>
									</option>
									<?php } else { ?>
									<option value="<?php echo $component['value']; ?>">
										<?php echo $component['text']; ?>
									</option>
									<?php } ?>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="col-sm-6 col-md-6">
							<div class="form-group">
								<label class="control-label" for="input-year">
									<?php echo $entry_year; ?>
								</label>
								<div class="input-group date">
									<input type="text" name="filter[year]" value="<?php echo $filter['year']; ?>"
										placeholder="<?php echo $entry_year; ?>" data-date-format="YYYY" id="input-year"
										class="form-control" />
									<span class="input-group-btn">
										<button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
									</span>
								</div>
							</div>
						</div>
						<div class="col-sm-6 col-md-3 pull-right">
							<button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i>
								<?php echo $button_filter; ?>
							</button>
						</div>
					</div>
				</div>
				<div id="report">
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		$('#button-filter').on('click', function () {
			url = 'index.php?route=report/coa&token=<?php echo $token; ?>';

			let filter_items = [
				'account',
				'year'
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
		$('#report').on('click', '.pagination a', function (e) {
			e.preventDefault();

			$('#report').load(this.href);
			$('html').animate({ scrollTop: 150 }, 500);
		});

		var filter_component = encodeURIComponent($('select[name=\'filter[component]\']').val());

		$('#report').load('index.php?route=report/coa/report&token=<?php echo $token; ?>&filter_component=' + filter_component + '&filter_year=<?php echo $filter["year"]; ?>');

		// $('#input-account').on('change', function () {
		// 	var filter_account_id = encodeURIComponent($('select[name=\'filter[account_id]\']').val());
		// 	var filter_date_start = encodeURIComponent($('input[name=\'filter[date_start]\']').val());
		// 	var filter_date_end = encodeURIComponent($('input[name=\'filter[date_end]\']').val());

		// 	$('#report').load('index.php?route=report/coa/report&token=<?php echo $token; ?>&filter_account_id=' + filter_account_id + '&filter_date_start=' + filter_date_start + '&filter_date_end=' + filter_date_end);
		// });
	</script>
	<script type="text/javascript">
		$('.date').datetimepicker({
			minViewMode: 'years',
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
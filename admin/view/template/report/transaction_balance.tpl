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
						<div class="col-sm-12 col-md-4">
							<div class="form-group">
								<label class="control-label" for="input-account">
									<?php echo $entry_account; ?>
								</label>
								<select name="filter_account_id" id="input-account" class="form-control">
									<?php foreach ($accounts as $account) { ?>
									<optgroup label="<?php echo $account['text']; ?>">
										<?php if ($account['child']) { ?>
										<?php foreach ($account['child'] as $child) { ?>
										<?php if ($child['account_id'] == $filter_account_id) { ?>
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
									</optgroup>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="col-sm-6 col-md-4">
							<div class="form-group">
								<label class="control-label" for="input-date-start">
									<?php echo $entry_date_start; ?>
								</label>
								<div class="input-group date">
									<input type="text" name="filter_date_start" value="<?php echo $filter_date_start; ?>"
										placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" id="input-date-start"
										class="form-control" />
									<span class="input-group-btn">
										<button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
									</span>
								</div>
							</div>
						</div>
						<div class="col-sm-6 col-md-4">
							<div class="form-group">
								<label class="control-label" for="input-date-end">
									<?php echo $entry_date_end; ?>
								</label>
								<div class="input-group date">
									<input type="text" name="filter_date_end" value="<?php echo $filter_date_end; ?>"
										placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" id="input-date-end"
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
			url = 'index.php?route=report/transaction_balance&token=<?php echo $token; ?>';

			var filter_date_start = $('input[name=\'filter_date_start\']').val();
			if (filter_date_start) {
				url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
			}

			var filter_date_end = $('input[name=\'filter_date_end\']').val();
			if (filter_date_end) {
				url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
			}

			var filter_account_id = $('select[name=\'filter_account_id\']').val();
			if (filter_account_id) {
				url += '&filter_account_id=' + encodeURIComponent(filter_account_id);
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

		var filter_account_id = encodeURIComponent($('select[name=\'filter_account_id\']').val());

		$('#report').load('index.php?route=report/transaction_balance/report&token=<?php echo $token; ?>&filter_account_id=' + filter_account_id + '&filter_date_start=<?php echo $filter_date_start; ?>&filter_date_end=<?php echo $filter_date_end; ?>');

		$('#input-account').on('change', function () {
			var filter_account_id = encodeURIComponent($('select[name=\'filter_account_id\']').val());
			var filter_date_start = encodeURIComponent($('input[name=\'filter_date_start\']').val());
			var filter_date_end = encodeURIComponent($('input[name=\'filter_date_end\']').val());

			$('#report').load('index.php?route=report/transaction_balance/report&token=<?php echo $token; ?>&filter_account_id=' + filter_account_id + '&filter_date_start=' + filter_date_start + '&filter_date_end=' + filter_date_end);
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
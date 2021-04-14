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
			</div>
			<div class="panel-body">
				<div class="well">
					<div class="row">
						<div class="col-sm-12 col-md-6">
							<div class="form-group">
								<label class="control-label" for="input-component">
									<?=$entry_component; ?>
								</label>
								<select name="filter[component]" id="input-component" class="form-control">
									<option value="*">
										<?=$text_all; ?>
									</option>
									<?php foreach ($components as $component) { ?>
									<?php if ($component['value'] == $filter['component']) { ?>
									<option value="<?=$component['value']; ?>" selected="selected">
										<?=$component['text']; ?>
									</option>
									<?php } else { ?>
									<option value="<?=$component['value']; ?>">
										<?=$component['text']; ?>
									</option>
									<?php } ?>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="col-sm-6 col-md-6">
							<div class="form-group">
								<label class="control-label" for="input-year">
									<?=$entry_year; ?>
								</label>
								<div class="input-group date">
									<input type="text" name="filter[year]" value="<?=$filter['year']; ?>"
										placeholder="<?=$entry_year; ?>" data-date-format="YYYY" id="input-year"
										class="form-control" />
									<span class="input-group-btn">
										<button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
									</span>
								</div>
							</div>
						</div>
						<div class="col-sm-6 col-md-3 pull-right">
							<button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i>
								<?=$button_filter; ?>
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
			url = 'index.php?route=report/coa&token=<?=$token; ?>';

			let filter_items = [
				'component',
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

		$('#report').on('click', '.pagination a', function (e) {
			e.preventDefault();

			$('#report').load(this.href);
			$('html').animate({ scrollTop: 150 }, 500);
		});

		var filter_component = encodeURIComponent($('select[name=\'filter[component]\']').val());

		$('#report').load('index.php?route=report/coa/report&token=<?=$token; ?>&filter_component=' + filter_component + '&filter_year=<?=$filter["year"]; ?>');

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
<?=$footer; ?>
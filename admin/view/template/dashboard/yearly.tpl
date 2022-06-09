<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><i class="fa fa-calendar-o"></i>
			<?php echo $heading_title; ?>
		</h3>
	</div>
	<div class="panel-body">
		<div id="calendar">
		</div>
	</div>
</div>
<script type="text/javascript">
	const calendar = new Calendar('#calendar', {
		style: 'background',
		language: 'id',
		preventRendering: true
	});

	updateCalendar(new Date().getFullYear());
	
	document.querySelector('#calendar').addEventListener('yearChanged', function(e) {
		updateCalendar(e.currentYear);
	});
	
	function updateCalendar(year)	{
		var dataSource = [];
	
		$.ajax({
			url: 'index.php?route=dashboard/yearly/yearlyData&token=<?php echo $token; ?>',
			type: 'get',
			data: 'filter_year=' + year,
			dataType: 'json',
			success: function(json) {
				if (json['results']) {
					let eventDates = json['results'];
	
					if (eventDates.length) {
						for (i = 0; i < eventDates.length; i++) {
							dataSource[i] = {startDate: new Date(eventDates[i]), endDate: new Date(eventDates[i])};
						}
						
						calendar.setDataSource(dataSource);
					}
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	};
	
	document.querySelector('#calendar').addEventListener('clickDay', function(e) {
		open('index.php?route=sale/order&token=<?php echo $token; ?>&filter_month=' + e.date.toLocaleDateString(), '_self');
	});
	</script> 
	
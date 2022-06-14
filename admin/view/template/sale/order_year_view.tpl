<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <a href="<?php echo $list; ?>" data-toggle="tooltip" title="<?php echo $button_list; ?>" class="btn btn-info"><i class="fa fa-list"></i></a>
        <a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
      </div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-calendar-o"></i> <?php echo $text_year_view; ?></h3>
      </div>
      <div class="panel-body">
		<!-- Calendar -->
        <div id="calendar">
        </div>
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
		url: 'index.php?route=sale/order/yearViewData&token=<?php echo $token; ?>',
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
<?php echo $footer; ?> 

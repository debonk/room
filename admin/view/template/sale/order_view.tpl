<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <a href="<?php echo $year_view; ?>" data-toggle="tooltip" title="<?php echo $button_year_view; ?>" class="btn btn-info"><i class="fa fa-calendar-o"></i></a>
        <a href="<?php echo $list; ?>" data-toggle="tooltip" title="<?php echo $button_list; ?>" class="btn btn-info"><i class="fa fa-list"></i></a>
        <a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a></div>
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
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_view; ?></h3>
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">
            <div class="col-sm-3">
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-date-added"><?php echo $entry_month; ?></label>
                <div class="input-group month">
                  <input type="text" name="filter_month" value="<?php echo $filter_month; ?>" placeholder="<?php echo $entry_month; ?>" data-date-format="MMM YYYY" id="input-month" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
            </div>
          </div>
        </div>
		<!-- Calendar -->
		<legend><?php echo $title; ?></legend>
		<div class="table-responsive">
		  <table class="table table-bordered">
		    <thead>
		  	<tr>
			<?php foreach ($weekdays as $weekday) { ?>
		  	  <td class="text-center calendar-day-head"><?php echo $weekday; ?></td>
			<?php } ?>
		  	</tr>
		    </thead>
		    <tbody>
			  <?php $day = 0; ?>
			  <?php foreach ($calendars as $calendar) { ?>
			  <?php if ($day == 0) { ?>
		  	  <tr>
			  <?php } ?>
			    <?php if ($calendar['date']) { ?>
		  		<td class="text-right calendar-day"><h3><?php echo $calendar['text']; ?></h3>
				  <div>
				  <?php foreach ($calendar['slot_data'] as $key => $value) { ?>
				    <?php if ($value) { ?>
				    <a href="<?php echo $calendar['url'] ?>" type="button" id="slot-<?php echo $calendar['date'] . $key; ?>" class="btn btn-default slot-<?php echo $key; ?>"><?php echo strtoupper($key); ?></a>
				    <?php } else { ?>
				    <a id="slot-<?php echo $calendar['date'] . $key; ?>"></a>
					<?php } ?>
				  <?php } ?>
				  </div>
						
				</td>
				<?php } else { ?>
		  		<td class="calendar-day-np"></td>
			    <?php } ?>
			    <?php $day++; ?>
			  <?php if ($day == 7) { ?>
		  	  </tr>
			  <?php $day = 0; ?>
			  <?php } ?>
			  <?php } ?>
		    </tbody>
		  </table>
		</div>
		<div>
		  <?php foreach($order_statuses as $order_status) { ?>
		  <p class="btn btn-xs <?php echo $order_status['class']; ?>"><?php echo $order_status['name']; ?></p>
		  <?php } ?>
		</div>
      </div>
    </div>
  </div>
  <script type="text/javascript">
$(document).ready(function(){
	$.ajax({
		url: 'index.php?route=sale/order/viewData&token=<?php echo $token; ?>&filter_month=<?php echo $filter_month; ?>',
		dataType: 'json',
		success: function(json) {
			$('.alert').remove();

			if (json['orders'].length) {
				for (i = 0; i < json['orders'].length; i++) {
					let order = json['orders'][i];
					let date_slot_idx = order['event_date'] + order['slot_idx'];
					
					console.log(order);
					for	 (j in order['slot_remove']) {
						$('#slot-' + order['event_date'] + order['slot_remove'][j]).replaceWith('<a id="slot-' + order['event_date'] + order['slot_remove'][j] + '"></a>');
					}
					
					html = '<a href="' + order['url'] + '" type="button" id="slot-'+ date_slot_idx + '" data-toggle="tooltip" title="' + order['order_summary'] + '" class="btn ' + order['order_status_class'] + ' slot-' + order['slot_idx'] + '">' + order['slot_name'].toUpperCase();
					
					if (json['orders'][i]['auto_expired']) {
						html += ' <span class="badge badge-danger"><i class="fa fa-exclamation"></i></span>';
					} else if (json['orders'][i]['payment_status']) {
						html += ' <span class="badge badge-info"><i class="fa fa-exclamation"></i></span>';
					}
					
					html += '</a>';

					$('#slot-' + date_slot_idx).replaceWith(html);
				}
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});  
  
  
$('#button-filter').on('click', function() {
	url = 'index.php?route=sale/order&token=<?php echo $token; ?>';

	var filter_month = $('input[name=\'filter_month\']').val();

	if (filter_month) {
		url += '&filter_month=' + encodeURIComponent(filter_month);
	}

	location = url;
});
</script> 
  <script type="text/javascript">
$(document).delegate('#button-ip-add', 'click', function() {
	$.ajax({
		url: 'index.php?route=user/api/addip&token=<?php echo $token; ?>&api_id=<?php echo $api_id; ?>',
		type: 'post',
		data: 'ip=<?php echo $api_ip; ?>',
		dataType: 'json',
		beforeSend: function() {
			$('#button-ip-add').button('loading');
		},
		complete: function() {
			$('#button-ip-add').button('reset');
		},
		success: function(json) {
			$('.alert').remove();

			if (json['error']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
			}

			if (json['success']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				$(node).parents("tr").remove();
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

// Login to the API
var token = '';

$.ajax({
	url: '<?php echo $store_url; ?>index.php?route=api/login',
	type: 'post',
	data: 'key=<?php echo $api_key; ?>',
	dataType: 'json',
	crossDomain: true,
	success: function(json) {
        $('.alert').remove();

        if (json['error']) {
    		if (json['error']['key']) {
    			$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['key'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
    		}

            if (json['error']['ip']) {
    			$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['ip'] + ' <button type="button" id="button-ip-add" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-danger btn-xs pull-right"><i class="fa fa-plus"></i> <?php echo $button_ip_add; ?></button></div>');
    		}
        }

		if (json['token']) {
			token = json['token'];
		}
	},
	error: function(xhr, ajaxOptions, thrownError) {
		alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
	}
});
</script> 
  <script src="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
  <link href="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css" type="text/css" rel="stylesheet" media="screen" />
  <script type="text/javascript">
$('.month').datetimepicker({
	minViewMode: 'months',
	pickTime: false
});
</script></div>
<?php echo $footer; ?> 

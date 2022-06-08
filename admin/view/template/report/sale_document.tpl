<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
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
        <h3 class="panel-title"><i class="fa fa-bar-chart"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-order-id"><?php echo $entry_order_id; ?></label>
                <input type="text" name="filter_order_id" value="<?php echo $filter_order_id; ?>" placeholder="<?php echo $entry_order_id; ?>" id="input-description" class="form-control" />
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-reference"><?php echo $entry_reference; ?></label>
                <input type="text" name="filter_reference" value="<?php echo $filter_reference; ?>" placeholder="<?php echo $entry_reference; ?>" id="input-reference" class="form-control" />
              </div>
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
            </div>
          </div>
        </div>
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td class="text-left"><?php if ($sort == 'order_id') { ?>
                    <a href="<?php echo $sort_order_id; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_order_id; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_order_id; ?>"><?php echo $column_order_id; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'type') { ?>
                    <a href="<?php echo $sort_type; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_type; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_type; ?>"><?php echo $column_type; ?></a>
                    <?php } ?>
                  <td class="text-left"><?php if ($sort == 'reference') { ?>
                    <a href="<?php echo $sort_reference; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_reference; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_reference; ?>"><?php echo $column_reference; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'date') { ?>
                    <a href="<?php echo $sort_date; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_date; ?>"><?php echo $column_date; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'customer_name') { ?>
                    <a href="<?php echo $sort_customer; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_customer; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_customer; ?>"><?php echo $column_customer; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'u.username') { ?>
                    <a href="<?php echo $sort_username; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_username; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_username; ?>"><?php echo $column_username; ?></a>
                    <?php } ?></td>
                   <td class="text-right"><?php echo $column_action; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php if ($documents) { ?>
                <?php foreach ($documents as $document) { ?>
                <tr>
                  <td class="text-left"><a href="<?php echo $document['url']; ?>" target="_blank" rel="noopener noreferrer"><?php echo $document['order_id']; ?></a></td>
                  <td class="text-left"><?php echo $document['document_type']; ?></td>
                  <td class="text-left"><?php echo $document['reference']; ?></td>
                  <td class="text-left"><?php echo $document['date']; ?></td>
                  <td class="text-left"><?php echo $document['customer']; ?></td>
				          <td class="text-left"><?php echo $document['username']; ?></td>
                  <td class="text-right nowrap">
				            <?php if ($document['printed']) { ?>
				            <button type="button" value="<?php echo $document['code']; ?>" class="btn btn-sm btn-warning btn-print" data-toggle="tooltip" title="<?php echo $document['text_printed']; ?>" <?php echo $document['modify']; ?> ><i class="fa fa-print"></i></button>
                    <?php } else { ?>
                    <button type="button" value="<?php echo $document['code']; ?>" class="btn btn-sm btn-success btn-print" data-toggle="tooltip" title="<?php echo $document['text_not_printed']; ?>" <?php echo $document['modify']; ?> ><i class="fa fa-print"></i></button>
                    <?php } ?>
        				  </td>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="9"><?php echo $text_no_results; ?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript">
$('#button-filter').on('click', function() {
	url = 'index.php?route=report/sale_document&token=<?php echo $token; ?>';
	
	var filter_order_id = $('input[name=\'filter_order_id\']').val();
	if (filter_order_id) {
		url += '&filter_order_id=' + encodeURIComponent(filter_order_id);
	}

	var filter_reference = $('input[name=\'filter_reference\']').val();
	if (filter_reference) {
		url += '&filter_reference=' + encodeURIComponent(filter_reference);
	}

	location = url;
});

$('.btn-print').on('click', function(e) {
  var node = this;

  if (confirm('<?php echo $text_confirm; ?>')) {
    $.ajax({
      url: 'index.php?route=report/sale_document/togglePrintStatus&token=<?php echo $token; ?>',
      type: 'get',
      dataType: 'json',
      data: 'document_code=' + $(node).val(),
      crossDomain: false,
      beforeSend: function() {
        $(node).button('loading');
      },
      complete: function() {
        $(node).button('reset');
      },
      success: function(json) {
        $('.alert').remove();

        if (json['error']) {
          $('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
        }

        if (json['success']) {
          $('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

          if (json['printed']) {
						$(node).removeClass('btn-success').addClass('btn-warning');
          } else {
            $(node).removeClass('btn-warning').addClass('btn-success');
          }
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
      alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
      }
    });
  }
});
  </script> 
  <script type="text/javascript">
$('.date').datetimepicker({
	pickTime: false
});

$(document).keypress(function(e) {
	if(e.which == 13) {
		$("#button-filter").click();
	}
});
  </script></div>
<?php echo $footer; ?>
<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right"><a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-transaction').submit() : false;"><i class="fa fa-trash-o"></i></button>
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
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-bar-chart"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">
            <div class="col-sm-3">
              <div class="form-group">
                <label class="control-label" for="input-date-start"><?php echo $entry_date_start; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_date_start" value="<?php echo $filter_date_start; ?>" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" id="input-date-start" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
              <div class="form-group">
                <label class="control-label" for="input-date-end"><?php echo $entry_date_end; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_date_end" value="<?php echo $filter_date_end; ?>" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" id="input-date-end" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group">
                <label class="control-label" for="input-account-from"><?php echo $entry_asset_from; ?></label>
                <select name="filter_account_from_id" id="input-account-from" class="form-control">
                  <option value="*"><?php echo $text_all; ?></option>
                  <?php foreach ($accounts_from as $account) { ?>
                    <?php if ($account['account_id'] == $filter_account_from_id) { ?>
                    <option value="<?php echo $account['account_id']; ?>" selected="selected"><?php echo $account['text']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $account['account_id']; ?>"><?php echo $account['text']; ?></option>
                    <?php } ?>
                    <?php if ($account['child']) { ?>
                    <?php foreach ($account['child'] as $child) { ?>
                      <?php if ($child['account_id'] == $filter_account_from_id) { ?>
                      <option value="<?php echo $child['account_id']; ?>" selected="selected">&nbsp;&nbsp;&nbsp;<?php echo $child['text']; ?></option>
                      <?php } else { ?>
                      <option value="<?php echo $child['account_id']; ?>">&nbsp;&nbsp;&nbsp;<?php echo $child['text']; ?></option>
                      <?php } ?>
                    <?php } ?>
                    <?php } ?>
                  <?php } ?>
                </select>
              </div>
              <div class="form-group">
                <label class="control-label" for="input-account-to"><?php echo $entry_asset_to; ?></label>
                <select name="filter_account_to_id" id="input-account-to" class="form-control">
                  <option value="*"><?php echo $text_all; ?></option>
                  <?php foreach ($accounts_to as $account) { ?>
                    <?php if ($account['account_id'] == $filter_account_to_id) { ?>
                    <option value="<?php echo $account['account_id']; ?>" selected="selected"><?php echo $account['text']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $account['account_id']; ?>"><?php echo $account['text']; ?></option>
                    <?php } ?>
                    <?php if ($account['child']) { ?>
                    <?php foreach ($account['child'] as $child) { ?>
                      <?php if ($child['account_id'] == $filter_account_to_id) { ?>
                      <option value="<?php echo $child['account_id']; ?>" selected="selected">&nbsp;&nbsp;&nbsp;<?php echo $child['text']; ?></option>
                      <?php } else { ?>
                      <option value="<?php echo $child['account_id']; ?>">&nbsp;&nbsp;&nbsp;<?php echo $child['text']; ?></option>
                      <?php } ?>
                    <?php } ?>
                    <?php } ?>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group">
                <label class="control-label" for="input-reference-no"><?php echo $entry_reference_no; ?></label>
                <input type="text" name="filter_reference_no" value="<?php echo $filter_reference_no; ?>" placeholder="<?php echo $entry_reference_no; ?>" id="input-reference-no" class="form-control" />
              </div>
              <div class="form-group">
                <label class="control-label" for="input-description"><?php echo $entry_description; ?></label>
                <input type="text" name="filter_description" value="<?php echo $filter_description; ?>" placeholder="<?php echo $entry_description; ?>" id="input-description" class="form-control" />
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group">
                <label class="control-label" for="input-customer-name"><?php echo $entry_customer_name; ?></label>
                <input type="text" name="filter_customer_name" value="<?php echo $filter_customer_name; ?>" placeholder="<?php echo $entry_customer_name; ?>" id="input-customer-name" class="form-control" />
              </div>
              <div class="form-group">
                <label class="control-label" for="input-username"><?php echo $entry_username; ?></label>
                <input type="text" name="filter_username" value="<?php echo $filter_username; ?>" placeholder="<?php echo $entry_username; ?>" id="input-username" class="form-control" />
              </div>
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
            </div>
          </div>
        </div>
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-transaction">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                  <td class="text-left"><?php if ($sort == 'reference') { ?>
                    <a href="<?php echo $sort_reference; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_reference_no; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_reference; ?>"><?php echo $column_reference_no; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 't.date') { ?>
                    <a href="<?php echo $sort_date; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_date; ?>"><?php echo $column_date; ?></a>
                    <?php } ?></td>
                  <td class="text-center">
                    <a href="<?php echo $sort_account_from; ?>" class="<?php echo $sort == 'account_from' ? strtolower($order) : ''; ?>"><?php echo $column_asset_from; ?></a>
                    <i class="fa fa-long-arrow-right"></i>
					<a href="<?php echo $sort_account_to; ?>" class="<?php echo $sort == 'account_to' ? strtolower($order) : ''; ?>"><?php echo $column_asset_to; ?></a>
				  </td>
                  <td class="text-left"><?php if ($sort == 't.description') { ?>
                    <a href="<?php echo $sort_description; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_description; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_description; ?>"><?php echo $column_description; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 't.customer_name') { ?>
                    <a href="<?php echo $sort_customer_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_customer_name; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_customer_name; ?>"><?php echo $column_customer_name; ?></a>
                    <?php } ?></td>
                  <td class="text-right"><?php if ($sort == 't.amount') { ?>
                    <a href="<?php echo $sort_amount; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_amount; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_amount; ?>"><?php echo $column_amount; ?></a>
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
                <?php if ($transactions) { ?>
                <?php foreach ($transactions as $transaction) { ?>
                <tr>
                  <td class="text-center"><?php if (in_array($transaction['transaction_id'], $selected)) { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $transaction['transaction_id']; ?>" checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $transaction['transaction_id']; ?>" />
                    <?php } ?></td>
				  <?php if ($transaction['order_url']) { ?>
                  <td class="text-left"><a href="<?php echo $transaction['order_url']; ?>" target="_blank"><?php echo $transaction['reference_no']; ?></a></td>
                  <?php } else { ?>
                  <td class="text-left"><?php echo $transaction['reference_no']; ?></td>
                  <?php } ?>
                  <td class="text-left"><?php echo $transaction['date']; ?></td>
                  <td class="text-left"><?php echo $transaction['account_from']; ?> <i class="fa fa-long-arrow-right"></i> <?php echo $transaction['account_to']; ?></td>
                  <td class="text-left"><?php echo $transaction['description']; ?></td>
				  <td class="text-left"><?php echo $transaction['customer_name']; ?></td>
                  <td class="text-right"><?php echo $transaction['amount']; ?></td>
                  <td class="text-left"><?php echo $transaction['username']; ?></td>
                  <td class="text-right"><a href="<?php echo $transaction['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-sm btn-primary"><i class="fa fa-pencil"></i></a></td>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="9"><?php echo $text_no_results; ?></td>
                </tr>
                <?php } ?>
              </tbody>
              <tfoot>
                <tr>
                  <td class="text-right" colspan="6"><?php echo $text_total; ?></td>
                  <td class="text-right"><?php echo $total; ?></td>
                </tr>
              </tfoot>
            </table>
          </div>
        </form>
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
$('#button-filter').on('click', function() {
	url = 'index.php?route=accounting/balance&token=<?php echo $token; ?>';
	
	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').val();
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
		
	var filter_account_from_id = $('select[name=\'filter_account_from_id\']').val();
	if (filter_account_from_id != '*') {
		url += '&filter_account_from_id=' + encodeURIComponent(filter_account_from_id);
	}

	var filter_account_to_id = $('select[name=\'filter_account_to_id\']').val();
	if (filter_account_to_id != '*') {
		url += '&filter_account_to_id=' + encodeURIComponent(filter_account_to_id);
	}

	var filter_description = $('input[name=\'filter_description\']').val();
	if (filter_description) {
		url += '&filter_description=' + encodeURIComponent(filter_description);
	}

	var filter_reference_no = $('input[name=\'filter_reference_no\']').val();
	if (filter_reference_no) {
		url += '&filter_reference_no=' + encodeURIComponent(filter_reference_no);
	}

	var filter_customer_name = $('input[name=\'filter_customer_name\']').val();
	if (filter_customer_name) {
		url += '&filter_customer_name=' + encodeURIComponent(filter_customer_name);
	}

	var filter_username = $('input[name=\'filter_username\']').val();
	if (filter_username) {
		url += '&filter_username=' + encodeURIComponent(filter_username);
	}

	location = url;
});
//--></script> 
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});

$(document).keypress(function(e) {
	if(e.which == 13) {
		$("#button-filter").click();
	}
});
//--></script></div>
<?php echo $footer; ?>
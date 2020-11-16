<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right"><a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-vendor').submit() : false;"><i class="fa fa-trash-o"></i></button>
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
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-vendor-name"><?php echo $entry_vendor_name; ?></label>
                <input type="text" name="filter_vendor_name" value="<?php echo $filter_vendor_name; ?>" placeholder="<?php echo $entry_vendor_name; ?>" id="input-vendor-name" class="form-control" />
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-vendor-type"><?php echo $entry_vendor_type; ?></label>
                <select name="filter_vendor_type_id" id="input-vendor-type" class="form-control">
                  <option value="*"><?php echo $text_all; ?></option>
                  <?php foreach ($vendor_types as $vendor_type) { ?>
                  <?php if ($vendor_type['vendor_type_id'] == $filter_vendor_type_id) { ?>
                  <option value="<?php echo $vendor_type['vendor_type_id']; ?>" selected="selected"><?php echo $vendor_type['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $vendor_type['vendor_type_id']; ?>"><?php echo $vendor_type['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-status"><?php echo $entry_status; ?></label>
                <select name="filter_status" id="input-status" class="form-control">
                  <option value="*"><?php echo $text_all; ?></option>
                  <?php if ($filter_status) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <?php } ?>
                  <?php if (!$filter_status && !is_null($filter_status)) { ?>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select>
              </div>
              <div class="form-group">
                <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
              </div>
            </div>
          </div>
        </div>
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-vendor">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                  <td class="text-left"><?php if ($sort == 'v.vendor_name') { ?>
                    <a href="<?php echo $sort_vendor_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_vendor_name; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_vendor_name; ?>"><?php echo $column_vendor_name; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'vendor_type') { ?>
                    <a href="<?php echo $sort_vendor_type; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_vendor_type; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_vendor_type; ?>"><?php echo $column_vendor_type; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'v.telephone') { ?>
                    <a href="<?php echo $sort_telephone; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_telephone; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_telephone; ?>"><?php echo $column_telephone; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'v.email') { ?>
                    <a href="<?php echo $sort_email; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_email; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_email; ?>"><?php echo $column_email; ?></a>
                    <?php } ?></td>
                  <td class="text-right"><?php echo $column_balance; ?></td>
                  <td class="text-left"><?php if ($sort == 'v.status') { ?>
                    <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'v.date_added') { ?>
                    <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_added; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>
                    <?php } ?></td>
                  <td class="text-right"><?php echo $column_action; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php if ($vendors) { ?>
                <?php foreach ($vendors as $vendor) { ?>
                <tr>
                  <td class="text-center"><?php if (in_array($vendor['vendor_id'], $selected)) { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $vendor['vendor_id']; ?>" checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $vendor['vendor_id']; ?>" />
                    <?php } ?></td>
                  <td class="text-left"><?php echo $vendor['vendor_name']; ?></td>
                  <td class="text-left"><?php echo $vendor['vendor_type']; ?></td>
                  <td class="text-left"><?php echo $vendor['telephone']; ?></td>
                  <td class="text-left"><?php echo $vendor['email']; ?></td>
                  <td class="text-right"><?php echo $vendor['balance']; ?></td>
                  <td class="text-left"><?php echo $vendor['status']; ?></td>
                  <td class="text-left"><?php echo $vendor['date_added']; ?></td>
                  <td class="text-right"><a href="<?php echo $vendor['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a></td>
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
	url = 'index.php?route=catalog/vendor&token=<?php echo $token; ?>';
	
	var filter_vendor_name = $('input[name=\'filter_vendor_name\']').val();
	
	if (filter_vendor_name) {
		url += '&filter_vendor_name=' + encodeURIComponent(filter_vendor_name);
	}
	
	var filter_vendor_type_id = $('select[name=\'filter_vendor_type_id\']').val();
	
	if (filter_vendor_type_id != '*') {
		url += '&filter_vendor_type_id=' + encodeURIComponent(filter_vendor_type_id);
	}	
	
	var filter_status = $('select[name=\'filter_status\']').val();
	
	if (filter_status != '*') {
		url += '&filter_status=' + encodeURIComponent(filter_status); 
	}	
	
	location = url;
});
//--></script> 
</div>
<?php echo $footer; ?>

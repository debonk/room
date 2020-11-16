<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-transaction" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
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
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-transaction" class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $text_reference_no; ?></label>
            <div class="col-sm-10">
              <h4 class="form-control-static"><?php echo $reference_no; ?></h4>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-account-from"><?php echo $entry_asset_from; ?></label>
            <div class="col-sm-10">
              <select name="account_from_id" id="input-account-from" class="form-control">
		        <option value=""><?php echo $text_select; ?></option>
                  <?php foreach ($accounts_from as $account) { ?>
			      <optgroup label="<?php echo $account['text']; ?>">
                  <?php if ($account['child']) { ?>
                  <?php foreach ($account['child'] as $child) { ?>
                    <?php if ($child['account_id'] == $account_from_id) { ?>
                    <option value="<?php echo $child['account_id']; ?>" selected="selected"><?php echo $child['text']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $child['account_id']; ?>"><?php echo $child['text']; ?></option>
                    <?php } ?>
                  <?php } ?>
                  <?php } ?>
				  </optgroup>
                  <?php } ?>
              </select>
              <?php if ($error_account_from) { ?>
              <div class="text-danger"><?php echo $error_account_from; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-account-to"><?php echo $entry_asset_to; ?></label>
            <div class="col-sm-10">
              <select name="account_to_id" id="input-account-to" class="form-control">
		        <option value=""><?php echo $text_select; ?></option>
                  <?php foreach ($accounts_to as $account) { ?>
			      <optgroup label="<?php echo $account['text']; ?>">
                  <?php if ($account['child']) { ?>
				  <?php foreach ($account['child'] as $child) { ?>
                    <?php if ($child['account_id'] == $account_to_id) { ?>
                    <option value="<?php echo $child['account_id']; ?>" selected="selected"><?php echo $child['text']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $child['account_id']; ?>"><?php echo $child['text']; ?></option>
                    <?php } ?>
                  <?php } ?>
                  <?php } ?>
				  </optgroup>
                  <?php } ?>
              </select>
              <?php if ($error_account_to) { ?>
              <div class="text-danger"><?php echo $error_account_to; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-date"><?php echo $entry_date; ?></label>
            <div class="col-sm-10">
              <div class="input-group date">
                <input type="text" name="date" value="<?php echo $date; ?>" placeholder="<?php echo $entry_date; ?>" data-date-format="YYYY-MM-DD" id="input-date" class="form-control" />
                <span class="input-group-btn">
                <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                </span>
			  </div>
              <?php if ($error_date) { ?>
              <div class="text-danger"><?php echo $error_date; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-description"><?php echo $entry_description; ?></label>
            <div class="col-sm-10">
              <input type="text" name="description" value="<?php echo $description; ?>"  placeholder="<?php echo $entry_description; ?>" id="input-description" class="form-control" />
              <?php if ($error_description) { ?>
              <div class="text-danger"><?php echo $error_description; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-amount"><?php echo $entry_amount; ?></label>
            <div class="col-sm-10">
              <input type="text" name="amount" value="<?php echo $amount; ?>" placeholder="<?php echo $entry_amount; ?>" id="input-amount" class="form-control" />
              <?php if ($error_amount) { ?>
              <div class="text-danger"><?php echo $error_amount; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-customer-name"><?php echo $entry_customer_name; ?></label>
            <div class="col-sm-10">
              <input type="text" name="customer_name" value="<?php echo $customer_name; ?>" placeholder="<?php echo $entry_customer_name; ?>" id="input-customer-name" class="form-control" />
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script></div>
<?php echo $footer; ?>
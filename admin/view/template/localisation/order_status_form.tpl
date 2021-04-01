<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-order-status" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
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
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-order-status" class="form-horizontal">
          <div class="form-group required">
            <label class="col-sm-2 control-label"><?php echo $entry_name; ?></label>
            <div class="col-sm-10">
              <?php foreach ($languages as $language) { ?>
              <div class="input-group"><span class="input-group-addon"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /></span>
                <input type="text" name="order_status[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($order_status[$language['language_id']]) ? $order_status[$language['language_id']]['name'] : ''; ?>" placeholder="<?php echo $entry_name; ?>" class="form-control" />
              </div>
              <?php if (isset($error_name[$language['language_id']])) { ?>
              <div class="text-danger"><?php echo $error_name[$language['language_id']]; ?></div>
              <?php } ?>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-class"><?php echo $entry_class; ?></label>
            <div class="col-sm-10">
              <input type="text" name="class" value="<?php echo $class; ?>" placeholder="<?php echo $entry_class; ?>" class="form-control" id="input-class" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_parent_status; ?></label>
            <div class="col-sm-10">
              <div class="well well-sm" style="height: 150px; overflow: auto;">
                <?php foreach ($order_statuses as $order_status) { ?>
                <div class="checkbox col-sm-4">
                  <label>
                    <?php if (in_array($order_status['order_status_id'], $parent_status)) { ?>
                    <input type="checkbox" name="parent_status[]" value="<?php echo $order_status['order_status_id']; ?>" checked="checked" />
                    <?php echo $order_status['name']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="parent_status[]" value="<?php echo $order_status['order_status_id']; ?>" />
                    <?php echo $order_status['name']; ?>
                    <?php } ?>
                  </label>
                </div>
                <?php } ?>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_user_group; ?></label>
            <div class="col-sm-10">
              <div class="well well-sm" style="height: 150px; overflow: auto;">
                <?php foreach ($user_groups as $user_group) { ?>
                <div class="checkbox col-sm-4">
                  <label>
                    <?php if (in_array($user_group['user_group_id'], $user_group_modify)) { ?>
                    <input type="checkbox" name="user_group_modify[]" value="<?php echo $user_group['user_group_id']; ?>" checked="checked" />
                    <?php echo $user_group['name']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="user_group_modify[]" value="<?php echo $user_group['user_group_id']; ?>" />
                    <?php echo $user_group['name']; ?>
                    <?php } ?>
                  </label>
                </div>
                <?php } ?>
              </div>
            </div>
          </div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-transaction-type">
							<?php echo $entry_transaction_type; ?>
						</label>
						<div class="col-sm-10">
							<select name="transaction_type_id" id="input-transaction-type" class="form-control">
								<option value="0">
									<?php echo $text_none; ?>
								</option>
								<?php foreach ($transaction_types as $transaction_type) { ?>
								<?php if ($transaction_type['transaction_type_id'] == $transaction_type_id) { ?>
								<option value="<?php echo $transaction_type['transaction_type_id']; ?>" selected="selected">
									<?php echo $transaction_type['name']; ?>
								</option>
								<?php } else { ?>
								<option value="<?php echo $transaction_type['transaction_type_id']; ?>">
									<?php echo $transaction_type['name']; ?>
								</option>
								<?php } ?>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="form-group">
            <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
            <div class="col-sm-10">
              <input type="text" name="sort_order" value="<?php echo $sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" class="form-control" id="input-sort-order" />
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>
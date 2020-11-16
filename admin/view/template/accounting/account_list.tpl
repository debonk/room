<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right"><a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-account').submit() : false;"><i class="fa fa-trash-o"></i></button>
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
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-account">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></th>
                  <th class="text-left"><?php if ($sort == 'account_id') { ?>
                    <a href="<?php echo $sort_account_id; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_account_id; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_account_id; ?>"><?php echo $column_account_id; ?></a>
                    <?php } ?></th>
                  <th class="text-left"><?php if ($sort == 'name') { ?>
                    <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
                    <?php } ?></th>
				  <th class="text-left"><?php if ($sort == 'description') { ?>
				    <a href="<?php echo $sort_description; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_description; ?></a>
				    <?php } else { ?>
				    <a href="<?php echo $sort_description; ?>"><?php echo $column_description; ?></a>
				    <?php } ?></th>
                  <th class="text-left"><?php if ($sort == 'type') { ?>
                    <a href="<?php echo $sort_type; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_type; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_type; ?>"><?php echo $column_type; ?></a>
                    <?php } ?></th>
                  <th class="text-left"><?php if ($sort == 'parent_id') { ?>
                    <a href="<?php echo $sort_parent_id; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_parent; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_parent_id; ?>"><?php echo $column_parent; ?></a>
                    <?php } ?></th>
                  <th class="text-right"><?php if ($sort == 'status') { ?>
                    <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                    <?php } ?></th>
                  <th class="text-right"><?php echo $column_action; ?></th>
                </tr>
              </thead>
              <tbody>
                <?php if ($accounts) { ?>
				<?php $header = ''; ?>
                <?php foreach ($accounts as $account) { ?>
                <?php foreach ($type_groups as $type_group) { ?>
                <?php if (in_array($account['type'], $type_group['list']) && $header != $type_group['code']) { ?>
                <tr>
                  <th class="text-left" colspan="8"><?php echo $type_group['text']; ?></th>
                </tr>
                <?php $header = $type_group['code']; ?>
                <?php } ?>
                <?php } ?>
                <tr>
                  <td class="text-center"><?php if (in_array($account['account_id'], $selected)) { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $account['account_id']; ?>" checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $account['account_id']; ?>" />
                    <?php } ?></td>
                  <td class="text-left"><?php echo $account['account_id']; ?></td>
                  <td class="text-left"><?php echo $account['name']; ?></td>
                  <td class="text-left"><?php echo $account['description']; ?></td>
                  <td class="text-left"><?php echo $account['text_type']; ?></td>
                  <td class="text-left"><?php echo $account['parent']; ?></td>
                  <td class="text-right"><?php echo $account['status']; ?></td>
                  <td class="text-right"><a href="<?php echo $account['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-sm btn-primary"><i class="fa fa-pencil"></i></a></td>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="8"><?php echo $text_no_results; ?></td>
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
</div>
<?php echo $footer; ?>
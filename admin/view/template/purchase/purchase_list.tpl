<?php echo $header; ?>
<?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right"><a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>"
          class="btn btn-primary"><i class="fa fa-plus"></i></a>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger"
          onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-purchase').submit() : false;"><i
            class="fa fa-trash-o"></i></button>
      </div>
      <h1>
        <?php echo $heading_title; ?>
      </h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>">
            <?php echo $breadcrumb['text']; ?>
          </a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i>
      <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i>
      <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-bar-chart"></i>
          <?php echo $text_list; ?>
        </h3>
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">
            <div class="col-sm-6 col-md-4">
              <div class="form-group">
                <label class="control-label" for="input-date-start">
                  <?php echo $entry_date_start; ?>
                </label>
                <div class="input-group date">
                  <input type="text" name="filter[date_start]" value="<?php echo $filter['date_start']; ?>"
                    placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" id="input-date-start"
                    class="form-control" />
                  <span class="input-group-btn">
                    <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label" for="input-date-end">
                  <?php echo $entry_date_end; ?>
                </label>
                <div class="input-group date">
                  <input type="text" name="filter[date_end]" value="<?php echo $filter['date_end']; ?>"
                    placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" id="input-date-end"
                    class="form-control" />
                  <span class="input-group-btn">
                    <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span>
                </div>
              </div>
            </div>
            <div class="col-sm-6 col-md-4">
              <div class="form-group">
                <label class="control-label" for="input-invoice">
                  <?php echo $entry_invoice; ?>
                </label>
                <input type="text" name="filter[invoice]" value="<?php echo $filter['invoice']; ?>"
                  placeholder="<?php echo $entry_invoice; ?>" id="input-invoice" class="form-control" />
              </div>
              <div class="form-group">
                <label class="control-label" for="input-supplier-name">
                  <?php echo $entry_supplier_name; ?>
                </label>
                <input type="text" name="filter[supplier_name]" value="<?php echo $filter['supplier_name']; ?>"
                  placeholder="<?php echo $entry_supplier_name; ?>" id="input-supplier-name" class="form-control" />
              </div>
            </div>
            <div class="col-sm-6 col-md-4">
              <div class="form-group">
                <label class="control-label" for="input-order-id">
                  <?php echo $entry_order_id; ?>
                </label>
                <input type="text" name="filter[order_id]" value="<?php echo $filter['order_id']; ?>"
                  placeholder="<?php echo $entry_order_id; ?>" id="input-description" class="form-control" />
              </div>
              <div class="form-group">
                <label class="control-label" for="input-username">
                  <?php echo $entry_username; ?>
                </label>
                <input type="text" name="filter[username]" value="<?php echo $filter['username']; ?>"
                  placeholder="<?php echo $entry_username; ?>" id="input-username" class="form-control" />
              </div>
              <div class="pull-right">
                <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i>
                  <?php echo $button_filter; ?>
                </button>
              </div>
            </div>
          </div>
        </div>
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-purchase">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox"
                      onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                  <td class="text-left">
                    <?php if ($sort == 'p.date_added') { ?>
                    <a href="<?php echo $sort_date; ?>" class="<?php echo strtolower($order); ?>">
                      <?php echo $column_date_added; ?>
                    </a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_date; ?>">
                      <?php echo $column_date_added; ?>
                    </a>
                    <?php } ?>
                  </td>
                  <td class="text-left">
                    <?php if ($sort == 'p.supplier_name') { ?>
                    <a href="<?php echo $sort_supplier_name; ?>" class="<?php echo strtolower($order); ?>">
                      <?php echo $column_supplier_name; ?>
                    </a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_supplier_name; ?>">
                      <?php echo $column_supplier_name; ?>
                    </a>
                    <?php } ?>
                  </td>
                  <td class="text-left">
                    <?php if ($sort == 'invoice') { ?>
                    <a href="<?php echo $sort_invoice; ?>" class="<?php echo strtolower($order); ?>">
                      <?php echo $column_invoice; ?>
                    </a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_invoice; ?>">
                      <?php echo $column_invoice; ?>
                    </a>
                    <?php } ?>
                  </td>
                  <td class="text-left">
                      <?php echo $column_description; ?>
                 </td>
                  <td class="text-right">
                    <?php if ($sort == 'p.total') { ?>
                    <a href="<?php echo $sort_total; ?>" class="<?php echo strtolower($order); ?>">
                      <?php echo $column_total; ?>
                    </a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_total; ?>">
                      <?php echo $column_total; ?>
                    </a>
                    <?php } ?>
                  </td>
                  <td class="text-left">
                    <?php if ($sort == 'u.username') { ?>
                    <a href="<?php echo $sort_username; ?>" class="<?php echo strtolower($order); ?>">
                      <?php echo $column_username; ?>
                    </a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_username; ?>">
                      <?php echo $column_username; ?>
                    </a>
                    <?php } ?>
                  </td>
                  <td class="text-right">
                    <?php echo $column_action; ?>
                  </td>
                </tr>
              </thead>
              <tbody>
                <?php if ($purchases) { ?>
                <?php foreach ($purchases as $purchase) { ?>
                <tr <?php echo $purchase['completed'] ? '' : 'class="danger"' ; ?>>
                  <td class="text-center">
                    <?php if (in_array($purchase['purchase_id'], $selected)) { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $purchase['purchase_id']; ?>"
                      checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $purchase['purchase_id']; ?>" />
                    <?php } ?>
                  </td>
                  <td class="text-left">
                    <?php echo $purchase['date']; ?>
                  </td>
                  <td class="text-left">
                    <?php echo $purchase['supplier_name']; ?>
                  </td>
                  <?php if ($purchase['order_url']) { ?>
                  <td class="text-left"><a href="<?php echo $purchase['order_url']; ?>" target="_blank">
                      <?php echo $purchase['invoice']; ?>
                    </a></td>
                  <?php } else { ?>
                  <td class="text-left">
                    <?php echo $purchase['invoice']; ?>
                  </td>
                  <?php } ?>
                  <td class="text-left">
                    <?php echo $purchase['description']; ?>
                  </td>
                  <td class="text-right">
                    <?php echo $purchase['total']; ?>
                  </td>
                  <td class="text-left">
                    <?php echo $purchase['username']; ?>
                  </td>
                  <td class="text-right nowrap">
                    <a href="<?php echo $purchase['edit']; ?>" data-toggle="tooltip"
                      title="<?php echo $button_edit; ?>" class="btn btn-sm btn-primary"><i
                        class="fa fa-pencil"></i></a>
                  </td>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="9">
                    <?php echo $text_no_results; ?>
                  </td>
                </tr>
                <?php } ?>
              </tbody>
              <tfoot>
                <tr>
                  <td class="text-right" colspan="5">
                    <?php echo $text_total; ?>
                  </td>
                  <td class="text-right">
                    <?php echo $total; ?>
                  </td>
                </tr>
              </tfoot>
            </table>
          </div>
        </form>
        <div class="row">
          <div class="col-sm-6 text-left">
            <?php echo $pagination; ?>
          </div>
          <div class="col-sm-6 text-right">
            <?php echo $results; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript">

    $('#button-filter').on('click', function () {
      url = 'index.php?route=purchase/purchase&token=<?php echo $token; ?>';

      let filter_items = [
        'date_start',
        'date_end',
        'supplier_name',
        'invoice',
        'order_id',
        'username'
      ];

      let filter = [];

      for (let i = 0; i < filter_items.length; i++) {
        filter[filter_items[i]] = $('input[name=\'filter[' + filter_items[i] + ']\']').val();

        if (filter[filter_items[i]]) {
          url += '&filter_' + filter_items[i] + '=' + encodeURIComponent(filter[filter_items[i]]);
        }
      }

      location = url;
    });

  </script>
  <script type="text/javascript">
    $('#form-purchase').on('click', 'button[id^=\'btn-lock-toggle\']', function (e) {
      var node = this;

      $.ajax({
        url: 'index.php?route=accounting/transaction/editPermission&token=<?php echo $token; ?>',
        type: 'post',
        dataType: 'json',
        data: 'transaction_id=' + $(node).val(),
        crossDomain: false,
        beforeSend: function () {
          $(node).button('loading');
        },
        complete: function () {
          $(node).button('reset');
        },
        success: function (json) {
          $('.alert').remove();

          if (json['error']) {
            $('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
          }

          if (json['success']) {
            $('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

            if (json['unlock_status']) {
              $(node).replaceWith('<button type="button" id="btn-lock-toggle' + $(node).val() + '" value="' + $(node).val() + '" class="btn btn-sm btn-warning" data-toggle="tooltip" title="<?php echo $button_edit_lock; ?>"><i class="fa fa-unlock-alt"></i></button>');
            } else {
              $(node).replaceWith('<button type="button" id="btn-lock-toggle' + $(node).val() + '" value="' + $(node).val() + '" class="btn btn-sm btn-primary" data-toggle="tooltip" title="<?php echo $button_edit_unlock; ?>"><i class="fa fa-lock"></i></button>');
            }
          }
        },
        error: function (xhr, ajaxOptions, thrownError) {
          alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
      });
    });
  </script>
  <script type="text/javascript">
    $('.date').datetimepicker({
      pickTime: false
    });

    $(document).keypress(function (e) {
      if (e.which == 13) {
        $("#button-filter").click();
      }
    });
  </script>
</div>
<?php echo $footer; ?>
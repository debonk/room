<!DOCTYPE html>
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">

<head>
  <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <title>
    <?php echo $title_purchase; ?>
  </title>
  <base href="<?php echo $base; ?>" />
  <link href="view/javascript/bootstrap/css/bootstrap.css" rel="stylesheet" media="all" />
  <script type="text/javascript" src="view/javascript/jquery/jquery-2.1.1.min.js"></script>
  <script type="text/javascript" src="view/javascript/bootstrap/js/bootstrap.min.js"></script>
  <link href="view/javascript/font-awesome/css/font-awesome.min.css" type="text/css" rel="stylesheet" />
  <link type="text/css" href="view/stylesheet/stylesheet.css" rel="stylesheet" media="all" />
  <link type="text/css" href="view/stylesheet/print.css" rel="stylesheet" media="all" />
  <style>
    @page {
      margin-top: 7mm;
    }
  </style>
</head>

<body>
  <div class="container">
    <?php if ($preview) { ?>
    <div id="background">
      <p class="bg-text" id="bg-purchase">
        <?php echo $text_mark; ?>
      </p>
    </div>
    <?php } ?>
    <?php for ($x = 0; $x < 2; $x++) { ?>
    <div style="page-break-after: always;" class="<?php echo $letter_content; ?> <?php echo $x ? 'screen-hide' : ''; ?>">
      <div class="clearfix">
        <div class="col-xs-3">
          <div class="text-center"><img src="<?php echo $store_logo; ?>" />
            <h3>
              <?php echo $store_name; ?>
            </h3>
            <small>
              <?php echo $store_slogan; ?>
            </small>
          </div><br />
          <div>
            <?php echo $store_owner; ?><br />
            <?php echo $store_address; ?><br />
            <?php echo 't.&nbsp;' . $store_telephone; ?><br />
            <?php echo 'e.&nbsp;' . $store_email; ?><br /><br />
            <?php if ($store_fax) { ?>
            <?php echo 'f. ' . $store_fax; ?><br />
            <?php } ?>
            <?php echo $store_url; ?>
          </div>
        </div>
        <div class="col-xs-9">
          <h2>
            <?php echo $title_purchase; ?>
            <?php if ($reference) { ?>
            <div class="small">
              <?php echo $text_reference_no; ?>
              <?php echo $reference; ?>
            </div>
            <?php } ?>
          </h2>
          <hr>
          <table class="table table-application">
            <tbody>
              <tr>
                <td style="width: 27%;">
                  <?php echo $text_day_date; ?>
                </td>
                <td style="width: 1%;">:</td>
                <td style="width: 72%;">
                  <?php echo $day_date; ?>
                </td>
              </tr>
              <tr>
                <td>
                  <?php echo $text_to; ?>
                </td>
                <td>:</td>
                <td>
                  <?php echo $name; ?>
                </td>
              </tr>
              <tr>
                <td>
                  <?php echo $text_address; ?>
                </td>
                <td>:</td>
                <td>
                  <?php echo $address; ?>
                </td>
              </tr>
              <tr>
                <td>
                  <?php echo $text_contact_person; ?>
                </td>
                <td>:</td>
                <td>
                  <?php echo $contact_person; ?>
                </td>
              </tr>
            </tbody>
          </table>
          <hr>
          <table class="table table-application">
            <tbody>
              <tr>
                <td colspan="4">
                  <h5>
                    <?php echo $text_order_detail; ?>
                  </h5>
                </td>
              </tr>
              <tr>
                <td style="width: 27%;">
                  <?php echo $text_event_title; ?>
                </td>
                <td style="width: 1%;">:</td>
                <td style="width: 72%;">
                  <?php echo $event_title; ?>
                </td>
              </tr>
              <tr>
                <td>
                  <?php echo $text_package; ?>
                </td>
                <td>:</td>
                <td>
                  <?php echo $package; ?>
                </td>
              </tr>
              <tr>
                <td>
                  <?php echo $text_venue; ?>
                </td>
                <td>:</td>
                <td>
                  <?php echo $venue; ?>
                </td>
              </tr>
              <tr>
                <td>
                  <?php echo $text_customer; ?>
                </td>
                <td>:</td>
                <td>
                  <?php echo $customer; ?>
                </td>
              </tr>
              <tr>
                <td>
                  <?php echo $text_event_date; ?>
                </td>
                <td>:</td>
                <td>
                  <?php echo $event_date; ?>
                </td>
              </tr>
              <tr>
                <td>
                  <?php echo $text_slot; ?>
                </td>
                <td>:</td>
                <td>
                  <?php echo $slot; ?>
                </td>
              </tr>
              <tr>
                <td>
                  <?php echo $text_sales; ?>
                </td>
                <td>:</td>
                <td>
                  <?php echo $sales; ?>
                </td>
              </tr>
            </tbody>
          </table>
          <hr>
        </div>
      </div>
      <div class="table-responsive">
        <legend>
          <?php echo $text_product_detail; ?>
        </legend>
        <table class="table table-bordered table-receipt text-right">
          <thead>
            <tr>
              <td>
                <?php echo $column_no; ?>
              </td>
              <td class="text-left">
                <?php echo $column_description; ?>
              </td>
              <td>
                <?php echo $column_quantity; ?>
              </td>
              <td>
                <?php echo $column_price; ?>
              </td>
              <td>
                <?php echo $column_subtotal; ?>
              </td>
            </tr>
          </thead>
          <?php if ($products) { ?>
          <tbody>
            <?php foreach ($products as $product) { ?>
            <tr>
              <td>
                <?php echo $product['no']; ?>
              </td>
              <td class="text-left">
                <?= $product['name']; ?>
                <?php foreach ($product['option'] as $option) { ?>
                <br />
                <?php if ($option['type'] != 'file') { ?>
                &nbsp;<small>&nbsp;-&nbsp;
                  <?= $option['name']; ?>:
                  <?= $option['value']; ?>
                </small>
                <?php } else { ?>
                &nbsp;<small>&nbsp;-&nbsp;
                  <?= $option['name']; ?>: <a href="<?= $option['href']; ?>">
                    <?= $option['value']; ?>
                  </a>
                </small>
                <?php } ?>
                <?php } ?>
                <?php foreach ($product['attribute'] as $attribute_group => $attributes) { ?>
                <br />
                <small>
                  <?= $attribute_group; ?>
                </small>
                <?php foreach ($attributes as $attribute) { ?>
                <br />
                &nbsp;<small>&nbsp;-&nbsp;
                  <?= $attribute['name']; ?>:
                  <?= $attribute['value']; ?>
                </small>
                <?php } ?>
                <?php } ?>
              </td>
              <td>
                <?php echo $product['quantity']; ?>
              </td>
              <td>
                <?php echo $product['price']; ?>
              </td>
              <td>
                <?php echo $product['subtotal']; ?>
              </td>
            </tr>
            <?php } ?>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="4">
                <?php echo $text_total; ?>
              </td>
              <td>
                <?php echo $total; ?>
              </td>
            </tr>
          </tfoot>
          <?php } else { ?>
          <tbody>
            <tr>
              <td class="text-center" colspan="5">
                <?php echo $text_no_results; ?>
              </td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
        <br /><br />
        <table class="table table-application text-center">
          <tbody>
            <tr>
              <td style="width: 25%;">
                <?php echo $text_hormat_kami; ?>
              </td>
              <td style="width: 25%;"></td>
              <td style="width: 25%;"></td>
              <td style="width: 25%;"></td>
            </tr>
            <tr>
              <td>
                <?php echo $text_sales; ?>
              </td>
              <td>
                <?php echo $text_admin; ?>
              </td>
              <td>
                <?php echo $text_finance; ?>
              </td>
              <td>
                <?php echo $text_contact_person; ?><br /><br /><br /><br /><br /><br />
              </td>
            </tr>
            <tr>
              <td><u>
                <?php echo '( ' . $sales . ' )'; ?>
                </u>
              </td>
              <td><u>
                  <?php echo $text_tanda_tangan; ?>
                </u>
              </td>
              <td><u>
                  <?php echo $text_tanda_tangan; ?>
                </u>
              </td>
              <td><u>
                  <?php echo $text_tanda_tangan; ?>
                </u>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="div-comment">
        <?php echo $text_comment; ?><br />
        <?php echo $comment; ?>
      </div>
      <br>
    </div>
    <?php } ?>
  </div>
</body>

</html>
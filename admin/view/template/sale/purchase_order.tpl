<!DOCTYPE html>
<html dir="<?= $direction; ?>" lang="<?= $lang; ?>">

<head>
  <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <title>
    <?= $title_purchase; ?>
  </title>
  <base href="<?= $base; ?>" />
  <link href="view/javascript/bootstrap/css/bootstrap.css" rel="stylesheet" media="all" />
  <script type="text/javascript" src="view/javascript/jquery/jquery-2.1.1.min.js"></script>
  <script type="text/javascript" src="view/javascript/bootstrap/js/bootstrap.min.js"></script>
  <link href="view/javascript/font-awesome/css/font-awesome.min.css" type="text/css" rel="stylesheet" />
  <link type="text/css" href="view/stylesheet/stylesheet.css" rel="stylesheet" media="all" />
  <link type="text/css" href="view/stylesheet/print.css" rel="stylesheet" media="all" />
</head>

<body>
  <div class="container">
    <?php if ($preview) { ?>
    <div id="background">
      <p class="bg-text" id="bg-purchase">
        <?= $text_mark; ?>
      </p>
    </div>
    <?php } ?>
    <?php for ($x = 0; $x < 2; $x++) { ?>
    <div style="page-break-after: always;"
      class="<?= $letter_content; ?> <?= $x ? 'visible-print' : ''; ?>">
      <div class="clearfix">
        <div class="col-xs-4">
          <div class="text-center"><img src="<?= $store_logo; ?>" />
            <h3>
              <?= $store_name; ?>
            </h3>
            <small>
              <?= $store_slogan; ?>
            </small>
          </div><br />
          <div>
            <?= $store_owner; ?><br />
            <?= $store_address; ?><br />
            <?= 't.&nbsp;' . $store_telephone; ?><br />
            <?= 'e.&nbsp;' . $store_email; ?><br /><br />
            <?php if ($store_fax) { ?>
            <?= 'f. ' . $store_fax; ?><br />
            <?php } ?>
            <?= $store_url; ?>
          </div>
        </div>
        <div class="col-xs-8 mt-2">
          <h2>
            <?= $title_purchase; ?>
            <?php if ($reference) { ?>
            <div class="small">
              <?= $text_reference_no; ?>
              <?= $reference; ?>
            </div>
            <?php } ?>
          </h2>
          <hr>
          <table class="table table-application">
            <tbody>
              <tr>
                <td style="width: 40%;" rowspan="4">
                </td>
                <td style="width: 24%;">
                  <?= $text_day_date; ?>
                </td>
                <td style="width: 1%;">:</td>
                <td style="width: 35%;">
                  <?= $day_date; ?>
                </td>
              </tr>
              <tr>
                <td>
                  <?= $text_to; ?>
                </td>
                <td>:</td>
                <td>
                  <?= $name; ?>
                </td>
              </tr>
              <tr>
                <td>
                  <?= $text_address; ?>
                </td>
                <td>:</td>
                <td>
                  <?= $address; ?>
                </td>
              </tr>
              <tr>
                <td>
                  <?= $text_contact_person; ?>
                </td>
                <td>:</td>
                <td>
                  <?= $contact_person; ?>
                </td>
              </tr>
            </tbody>
          </table>
          <hr>
        </div>
      </div>
      <div>
        <table class="table table-application mt-1 ml-2">
          <tbody>
            <tr>
              <td colspan="4">
                <h5>
                  <?= $text_order_detail; ?>
                </h5>
              </td>
            </tr>
            <tr>
              <td style="width: 20%;">
                <?= $text_event_title; ?>
              </td>
              <td style="width: 1%;">:</td>
              <td style="width: 79%;">
                <?= $event_title; ?>
              </td>
            </tr>
            <tr>
              <td>
                <?= $text_package; ?>
              </td>
              <td>:</td>
              <td>
                <?= $package; ?>
              </td>
            </tr>
            <tr>
              <td>
                <?= $text_venue; ?>
              </td>
              <td>:</td>
              <td>
                <?= $venue; ?>
              </td>
            </tr>
            <tr>
              <td>
                <?= $text_customer; ?>
              </td>
              <td>:</td>
              <td>
                <?= $customer; ?>
              </td>
            </tr>
            <tr>
              <td>
                <?= $text_event_date; ?>
              </td>
              <td>:</td>
              <td>
                <?= $event_date; ?>
              </td>
            </tr>
            <tr>
              <td>
                <?= $text_slot; ?>
              </td>
              <td>:</td>
              <td>
                <?= $slot; ?>
              </td>
            </tr>
            <tr>
              <td>
                <?= $text_sales; ?>
              </td>
              <td>:</td>
              <td>
                <?= $sales; ?>
              </td>
            </tr>
          </tbody>
        </table>
      </div><br>
      <div class="table-responsive">
        <legend>
          <?= $text_product_detail; ?>
        </legend>
        <table class="table table-bordered table-receipt text-right">
          <thead>
            <tr>
              <td>
                <?= $column_no; ?>
              </td>
              <td class="text-left">
                <?= $column_description; ?>
              </td>
              <td>
                <?= $column_quantity; ?>
              </td>
              <td>
                <?= $column_price; ?>
              </td>
              <td>
                <?= $column_subtotal; ?>
              </td>
            </tr>
          </thead>
          <?php if ($products) { ?>
          <tbody>
            <?php foreach ($products as $product) { ?>
            <tr>
              <td>
                <?= $product['no']; ?>
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
                <?= $product['quantity']; ?>
              </td>
              <td>
                <?= $product['price']; ?>
              </td>
              <td>
                <?= $product['subtotal']; ?>
              </td>
            </tr>
            <?php } ?>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="4">
                <?= $text_total; ?>
              </td>
              <td>
                <?= $total; ?>
              </td>
            </tr>
          </tfoot>
          <?php } else { ?>
          <tbody>
            <tr>
              <td class="text-center" colspan="5">
                <?= $text_no_results; ?>
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
                <?= $text_hormat_kami; ?>
              </td>
              <td style="width: 25%;"></td>
              <td style="width: 25%;"></td>
              <td style="width: 25%;"></td>
            </tr>
            <tr>
              <td>
                <?= $text_sales; ?>
              </td>
              <td>
                <?= $text_admin; ?>
              </td>
              <td>
                <?= $text_finance; ?>
              </td>
              <td>
                <?= $text_contact_person; ?><br /><br /><br /><br /><br /><br />
              </td>
            </tr>
            <tr>
              <td><u>
                  <?= '( ' . $sales . ' )'; ?>
                </u>
              </td>
              <td><u>
                  <?= $text_tanda_tangan; ?>
                </u>
              </td>
              <td><u>
                  <?= $text_tanda_tangan; ?>
                </u>
              </td>
              <td><u>
                  <?= $text_tanda_tangan; ?>
                </u>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="div-comment">
        <?= $text_comment; ?><br />
        <?= $comment; ?>
      </div>
      <br>
    </div>
    <?php } ?>
  </div>
</body>

</html>
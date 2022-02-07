<!DOCTYPE html>
<html dir="<?= $direction; ?>" lang="<?= $lang; ?>">

<head>
  <meta charset="UTF-8" />
  <title>
    <?= $title_agreement; ?>
  </title>
  <base href="<?= $base; ?>" />
  <link href="view/javascript/bootstrap/css/bootstrap.css" rel="stylesheet" media="all" />
  <script type="text/javascript" src="view/javascript/jquery/jquery-2.1.1.min.js"></script>
  <script type="text/javascript" src="view/javascript/bootstrap/js/bootstrap.min.js"></script>
  <link href="view/javascript/font-awesome/css/font-awesome.min.css" type="text/css" rel="stylesheet" />
  <link type="text/css" href="view/stylesheet/stylesheet.css" rel="stylesheet" media="all" />
  <link type="text/css" href="view/stylesheet/print.css" rel="stylesheet" media="all" />
  <!-- <style> -->
    <!-- @page { -->
      <!-- margin-top: 75mm; -->
    <!-- } -->
  <!-- </style> -->
</head>

<body>
  <div class="container">
    <?php if ($preview) { ?>
    <div id="background">
      <p class="bg-text" id="bg-agreement">
        <?= $text_mark; ?>
      </p>
    </div>
    <?php } ?>
    <?php for ($x = 0; $x < 2; $x++) { ?>
    <div style="page-break-after: always;" class="<?= $letter_content; ?> <?= $x ? 'visible-print' : ''; ?>">
      <div class="letter-head">
        <img src="<?= $letter_head; ?>" class="img-responsive" />
      </div>
      <div>
        <table class="table table-application">
          <tbody>
            <tr>
              <td colspan="4" class="text-center">
                <h3>
                  <?= $title_agreement; ?>
                </h3>
                <?php if ($invoice_no) { ?>
                <h4>
                  <?= $text_invoice_no; ?>
                  <?= $invoice_no; ?>
                </h4>
                <?php } ?>
              </td>
            </tr>
            <tr>
              <td colspan="4" class="text-justify">
                <b>
                  <?= $text_pada_hari; ?>
                </b>
              </td>
            </tr>
            <tr>
              <td style="width: 1%;"></td>
              <td style="width: 40%;">
                <?= $text_customer; ?>
              </td>
              <td style="width: 1%;">:</td>
              <td style="width: 58%;">
                <?= $customer; ?>
              </td>
            </tr>
            <tr>
              <td></td>
              <td>
                <?= $text_id_no; ?>
              </td>
              <td>:</td>
              <td>
                <?= $id_no; ?>
              </td>
            </tr>
            <tr>
              <td></td>
              <td>
                <?= $text_customer_group; ?>
              </td>
              <td>:</td>
              <td>
                <?= $customer_group; ?>
              </td>
            </tr>
            <tr>
              <td></td>
              <td>
                <?= $text_company; ?>
              </td>
              <td>:</td>
              <td>
                <?= $company; ?>
              </td>
            </tr>
            <tr>
              <td></td>
              <td>
                <?= $text_address; ?>
              </td>
              <td>:</td>
              <td>
                <?= $address; ?>
              </td>
            </tr>
            <tr>
              <td></td>
              <td>
                <?= $text_profession; ?>
              </td>
              <td>:</td>
              <td>
                <?= $profession; ?>
              </td>
            </tr>
            <tr>
              <td></td>
              <td>
                <?= $text_position; ?>
              </td>
              <td>:</td>
              <td>
                <?= $position; ?>
              </td>
            </tr>
            <?php foreach ($custom_fields as $custom_field) { ?>
            <tr>
              <td></td>
              <td>
                <?= $custom_field['name']; ?>
              </td>
              <td>:</td>
              <td>
                <?= $custom_field['value']; ?>
              </td>
            </tr>
            <?php } ?>
            <tr>
              <td></td>
              <td>
                <?= $text_telephone; ?>
              </td>
              <td>:</td>
              <td>
                <?= $telephone; ?>
              </td>
            </tr>
            <tr>
              <td colspan="4">
                <b>
                  <?= $text_sewa_tempat; ?>
                </b>
              </td>
            </tr>
            <tr>
              <td></td>
              <td>
                <?= $text_package; ?>
              </td>
              <td>:</td>
              <td>
                <?= $package; ?>
              </td>
            </tr>
            <tr>
              <td></td>
              <td>
                <?= $text_venue; ?>
              </td>
              <td>:</td>
              <td>
                <?= $venue; ?>
              </td>
            </tr>
            <tr>
              <td></td>
              <td>
                <?= $text_day_date; ?>
              </td>
              <td>:</td>
              <td>
                <?= $event_date; ?>
              </td>
            </tr>
            <tr>
              <td></td>
              <td>
                <?= $text_slot; ?>
              </td>
              <td>:</td>
              <td>
                <?= $slot; ?>
              </td>
            </tr>
            <tr>
              <td></td>
              <td>
                <?= $text_price; ?>
              </td>
              <td>:</td>
              <td>
                <?= $price; ?>
              </td>
            </tr>
            <?php if ($order_vendors) { ?>
            <tr>
              <td colspan="4">
                <b>
                  <?= $text_order_vendor; ?>
                </b>
              </td>
            </tr>
            <?php foreach ($order_vendors as $order_vendor) { ?>
            <tr>
              <td></td>
              <td>
                <?= $order_vendor['type']; ?>
              </td>
              <td>:</td>
              <td>
                <?= $order_vendor['name']; ?>
              </td>
            </tr>
            <?php } ?>
            <?php } ?>
          </tbody>
        </table>
        <table class="table table-application page-content">
          <tbody>
            <?php if (isset($products['included']) || $product_primary['attribute']) { ?>
            <tr>
              <td colspan="4">
                <b>
                  <?= $text_termasuk; ?>
                </b>
              </td>
            </tr>
            <?php if (isset($products['included'])) { ?>
						<?php foreach ($products['included'] as $product) { ?>
            <tr>
              <td style="width: 1%;"></td>
              <td style="width: 40%;">
                <?= $product['name']; ?>
              </td>
              <td style="width: 1%;">:</td>
              <td style="width: 58%;">
                <?= $product['quantity']; ?>
              </td>
            </tr>
            <?php foreach ($product['attribute'] as $attribute_group => $attributes) { ?>
            <?php foreach ($attributes as $attribute) { ?>
            <tr class="text-italic">
              <td></td>
              <td>&nbsp;&nbsp;-&nbsp;
                <?= $attribute['name']; ?>
              </td>
              <td>:</td>
              <td>
                <?= $attribute['value']; ?>
              </td>
            </tr>
            <?php } ?>
            <?php } ?>
            <?php foreach ($product['option'] as $option) { ?>
            <tr>
              <td></td>
              <td>
                <?= $option['name']; ?>
              </td>
              <td>:</td>
              <td>
                <?= $option['value']; ?>
              </td>
            </tr>
            <?php } ?>
            <?php } ?>
            <?php } ?>
            <?php if ($product_primary['attribute']) { ?>
						<?php foreach ($product_primary['attribute'] as $attribute_group => $attributes) { ?>
            <tr>
              <td></td>
              <td>
                <?= $attribute_group; ?>
              </td>
            </tr>
            <?php foreach ($attributes as $attribute) { ?>
            <tr class="text-italic">
              <td style="width: 1%;"></td>
              <td style="width: 40%;">&nbsp;&nbsp;-&nbsp;
                <?= $attribute['name']; ?>
              </td>
              <td style="width: 1%;">:</td>
              <td style="width: 58%;">
                <?= $attribute['value']; ?>
              </td>
            </tr>
            <?php } ?>
            <?php } ?>
            <?php } ?>
            <?php } ?>
            <?php if (isset($products['additional'])) { ?>
            <tr>
              <td colspan="4">
                <b>
                  <?= $text_layanan_tambahan; ?>
                </b>
              </td>
            </tr>
            <?php foreach ($products['additional'] as $product) { ?>
            <tr>
              <td></td>
              <td>
                <?= $product['name'] . ' (' . $product['quantity'] . ')'; ?>
              </td>
              <td>:</td>
              <td>
                <?= $product['total']; ?>
              </td>
            </tr>
            <?php foreach ($product['attribute'] as $attribute_group => $attributes) { ?>
            <?php foreach ($attributes as $attribute) { ?>
            <tr class="text-italic">
              <td></td>
              <td>&nbsp;&nbsp;-&nbsp;
                <?= $attribute['name']; ?>
              </td>
              <td>:</td>
              <td>
                <?= $attribute['value']; ?>
              </td>
            </tr>
            <?php } ?>
            <?php } ?>
            <?php foreach ($product['option'] as $option) { ?>
            <tr>
              <td></td>
              <td>
                <?= $option['name']; ?>
              </td>
              <td>:</td>
              <td>
                <?= $option['value']; ?>
              </td>
            </tr>
            <?php } ?>
            <?php } ?>
            <?php } ?>
          </tbody>
        </table>
        <table class="table table-application page-content">
          <tbody>
            <tr>
              <td colspan="4">
                <b>
                  <?= $text_total; ?>
                </b><br />
              </td>
            </tr>
            <?php foreach ($totals as $total) { ?>
            <tr>
              <td style="width: 1%;"></td>
              <td style="width: 40%;">
                <?= $total['title']; ?>
              </td>
              <td style="width: 1%;">:</td>
              <td style="width: 58%;">
                <?= $total['text'] ?>
              </td>
            </tr>
            <?php } ?>
            <?php if ($transactions) { ?>
            <tr>
              <td colspan="4">
                <b>
                  <?= $text_telah_bayar; ?>
                </b><br />
              </td>
            </tr>
            <?php foreach ($transactions as $transaction) { ?>
            <tr>
              <td></td>
              <td>
                <?= $transaction['title']; ?>
              </td>
              <td>:</td>
              <td>
                <?= $transaction['text'] ?>
              </td>
            </tr>
            <?php } ?>
            <?php } ?>
          </tbody>
        </table>
        <div class="page-content">
          <table class="table table-application page-content">
            <tbody>
              <tr>
                <td>
                  <b>
                    <?= $text_snk; ?>
                  </b><br />
                </td>
              </tr>
              <tr>
                <td>
                  <ol>
                    <?php foreach ($text_transactions as $text_transaction) { ?>
                    <li>
                      <?= $text_transaction; ?>
                    </li>
                    <?php } ?>
                    <?php if ($text_transactions) { ?>
                    <li>
                      <?= $text_transfer_ke; ?>
                      <div style="margin-left: 10px;"><b>
                          <?= $no_rekening; ?>
                        </b></div>
                    </li>
                    <?php } ?>
                    <li>
                      <?= $text_belum_ppn; ?>
                    </li>
                    <li>
                      <?= $text_tukar_bukti; ?>
                    </li>
                    <li>
                      <?= $text_ubah_tanggal; ?>
                    </li>
                    <li>
                      <?= $text_pembatalan_acara; ?>
                      <div style="margin-left: 10px;"><b>
                          <?= $text_pembatalan_2; ?><br />
                          <?= $text_pembatalan_3; ?>
                        </b></div>
                    </li>
                    <li>
                      <?= $text_tambah_layanan; ?>
                    </li>
                 </ol>
                </td>
              </tr>
            </tbody>
          </table>
          <?php if (!$preview) { ?>
          <table class="table table-application text-center page-content">
            <tbody>
              <tr>
                <td colspan="2" class="text-justify">
                  <b>
                    <?= $text_demikian; ?>
                  </b><br /><br />
                </td>
              </tr>
              <tr>
                <td style="width: 50%;">
                  <?= $text_pihak_penyewa; ?>
                </td>
                <td>
                  <?= $store_owner; ?><br /><br /><br /><br /><br /><br /><br />
                </td>
              </tr>
              <tr>
                <td><u>
                    <?= '( ' . $customer . ' )'; ?>
                  </u></td>
                <td><u>
                    <?= $manajemen; ?>
                  </u><br />
                  <?= $text_manajemen; ?>
                </td>
              </tr>
            </tbody>
          </table>
          <?php if ($comment) { ?>
          <table class="table table-application">
            <thead>
              <tr>
                <td><b>
                    <?= $text_comment; ?>
                  </b></td>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>
                  <?= $comment; ?>
                </td>
              </tr>
            </tbody>
          </table>
          <?php } ?>
          <?php } else { ?>
						<?= $text_dst; ?>
          <?php } ?>
        </div>
      </div>
    </div>
    <?php if ($preview) {$x++;} ?>
    <?php } ?>
  </div>
</body>

</html>
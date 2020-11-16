<!DOCTYPE html>
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<head>
<meta charset="UTF-8" />
<title><?php echo $title_agreement; ?></title>
<base href="<?php echo $base; ?>" />
<link href="view/javascript/bootstrap/css/bootstrap.css" rel="stylesheet" media="all" />
<script type="text/javascript" src="view/javascript/jquery/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="view/javascript/bootstrap/js/bootstrap.min.js"></script>
<link href="view/javascript/font-awesome/css/font-awesome.min.css" type="text/css" rel="stylesheet" />
<link type="text/css" href="view/stylesheet/stylesheet.css" rel="stylesheet" media="all" />
<link type="text/css" href="view/stylesheet/print.css" rel="stylesheet" media="all" />
</head>
<body>
<div class="container">
  <div id="background">
    <p id="bg-text">Preview Only!</p>
  </div>
  <div>
    <div id="letter-head">
	  <img src="<?php echo $letter_head; ?>" class="img-responsive" />
    </div>
    <div>
      <table class="table table-application">
        <tbody>
          <tr>
            <td colspan="4" class="text-center">
	  		<h3><?php echo $title_agreement; ?></h3>
              <?php if ($invoice_no) { ?>
              <h4><?php echo $text_invoice_no; ?> <?php echo $invoice_no; ?></h4>
              <?php } ?>
            </td>
          </tr>
          <tr>
            <td colspan="4" class="text-justify">
              <b><?php echo $text_pada_hari; ?></b>
            </td>
          </tr>
          <tr>
            <td style="width: 1%;"></td>
            <td style="width: 40%;"><?php echo $text_customer; ?></td>
            <td style="width: 1%;">:</td>
            <td style="width: 58%;"><?php echo $customer; ?></td>
          </tr>
          <tr>
            <td></td><td><?php echo $text_id_no; ?></td><td>:</td><td><?php echo $id_no; ?></td>
          </tr>
          <tr>
            <td></td><td><?php echo $text_customer_group; ?></td><td>:</td><td><?php echo $customer_group; ?></td>
          </tr>
          <tr>
            <td></td><td><?php echo $text_company; ?></td><td>:</td><td><?php echo $company; ?></td>
          </tr>
          <tr>
            <td></td><td><?php echo $text_address; ?></td><td>:</td><td><?php echo $address; ?></td>
          </tr>
          <tr>
            <td></td><td><?php echo $text_profession; ?></td><td>:</td><td><?php echo $profession; ?></td>
          </tr>
          <tr>
            <td></td><td><?php echo $text_position; ?></td><td>:</td><td><?php echo $position; ?></td>
          </tr>
	  	  <?php foreach ($custom_fields as $custom_field) { ?>
          <tr>
            <td></td><td><?php echo $custom_field['name']; ?></td><td>:</td><td><?php echo $custom_field['value']; ?></td>
          </tr>
	  	  <?php } ?>
          <tr>
            <td></td><td><?php echo $text_telephone; ?></td><td>:</td><td><?php echo $telephone; ?></td>
          </tr>
          <tr>
            <td colspan="4">
              <b><?php echo $text_sewa_tempat; ?></b>
            </td>
          </tr>
          <tr>
            <td></td><td><?php echo $text_day_date; ?></td><td>:</td><td><?php echo $event_date; ?></td>
          </tr>
          <tr>
            <td></td><td><?php echo $text_slot; ?></td><td>:</td><td><?php echo $slot; ?></td>
          </tr>
          <tr>
            <td></td><td><?php echo $text_ceremony; ?></td><td>:</td><td><?php echo $ceremony; ?></td>
          </tr>
	  	  <?php foreach ($products[1] as $product) { ?>
          <tr>
            <td></td><td><?php echo $text_category; ?></td><td>:</td><td><?php echo $product['category']; ?></td>
          </tr>
          <tr>
            <td></td><td><?php echo $text_product_name; ?></td><td>:</td><td><?php echo $product['name']; ?></td>
          </tr>
          <tr>
            <td></td><td><?php echo $text_quantity; ?></td><td>:</td><td><?php echo $product['quantity']; ?></td>
          </tr>
          <tr>
            <td></td><td><?php echo $text_amount; ?></td><td>:</td><td><?php echo $product['total']; ?></td>
          </tr>
	  	  <?php foreach ($product['attribute'] as $attribute_group => $attributes) { ?>
          <tr>
            <td></td><td><b><?php echo $attribute_group; ?></b></td>
          </tr>
	  	  <?php foreach ($attributes as $attribute) { ?>
          <tr>
            <td></td><td>&nbsp;&nbsp;-&nbsp;<?php echo $attribute['name']; ?></td><td>:</td><td><?php echo $attribute['value']; ?></td>
          </tr>
	  	  <?php } ?>
	  	  <?php } ?>
	  	  <?php if ($product['option']) { ?>
          <tr>
            <td colspan="4">
              <b><?php echo $text_info_tambahan; ?></b>
            </td>
          </tr>
	  	  <?php foreach ($product['option'] as $option) { ?>
          <tr>
            <td></td><td><?php echo $option['name']; ?></td><td>:</td><td><?php echo $option['value']; ?></td>
          </tr>
	  	  <?php } ?>
	  	  <?php } ?>
	  	  <?php } ?>
	  	  <?php if ($order_vendors) { ?>
          <tr>
            <td colspan="4">
              <b><?php echo $text_order_vendor; ?></b>
            </td>
          </tr>
	  	  <?php foreach ($order_vendors as $order_vendor) { ?>
          <tr>
            <td></td><td><?php echo $order_vendor['type']; ?></td><td>:</td><td><?php echo $order_vendor['name']; ?></td>
          </tr>
	  	  <?php } ?>
	  	  <?php } ?>
	  	  <?php if (isset($products[0])) { ?>
          <tr>
            <td colspan="4">
              <b><?php echo $text_layanan_tambahan; ?></b>
            </td>
          </tr>
	  	  <?php foreach ($products[0] as $product) { ?>
          <tr>
            <td></td><td><?php echo $product['name'] . ' (' . $product['quantity'] . ')'; ?></td><td>:</td><td><?php echo $product['total']; ?></td>
          </tr>
	  	  <?php foreach ($product['attribute'] as $attribute_group => $attributes) { ?>
	  	  <?php foreach ($attributes as $attribute) { ?>
          <tr class="text-italic">
            <td></td><td>&nbsp;&nbsp;-&nbsp;<?php echo $attribute['name']; ?></td><td>:</td><td><?php echo $attribute['value']; ?></td>
          </tr>
	  	  <?php } ?>
	  	  <?php } ?>
	  	  <?php foreach ($product['option'] as $option) { ?>
          <tr>
            <td></td><td><?php echo $option['name']; ?></td><td>:</td><td><?php echo $option['value']; ?></td>
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
              <b><?php echo $text_total; ?></b><br />
            </td>
          </tr>
	  	  <?php foreach ($totals as $total) { ?>
          <tr>
            <td style="width: 1%;"></td>
            <td style="width: 40%;"><?php echo $total['title']; ?></td>
            <td style="width: 1%;">:</td>
            <td style="width: 58%;"><?php echo $total['text'] ?></td>
          </tr>
	  	  <?php } ?>
        </tbody>
      </table>
      <table class="table table-application page-content">
        <tbody>
	  	  <?php if ($transactions) { ?>
          <tr>
            <td colspan="4">
              <b><?php echo $text_telah_bayar; ?></b><br />
            </td>
          </tr>
	  	  <?php foreach ($transactions as $transaction) { ?>
          <tr>
            <td style="width: 1%;"></td>
            <td style="width: 40%;"><?php echo $transaction['title']; ?></td>
            <td style="width: 1%;">:</td>
            <td style="width: 58%;"><?php echo $transaction['text'] ?></td>
          </tr>
	  	  <?php } ?>
	  	  <?php } ?>
        </tbody>
      </table>
      <table class="table table-application page-content">
        <tbody>
          <tr>
            <td>
              <b><?php echo $text_snk; ?></b><br />
            </td>
          </tr>
          <tr>
            <td>
	  	    <ol>
	  		  <?php foreach ($text_transactions as $text_transaction) { ?>
                <li><?php echo $text_transaction; ?></li>
	  		  <?php } ?>
	  		  <?php if ($text_transactions) { ?>
                <li><?php echo $text_transfer_ke; ?><div style="margin-left: 10px;"><b><?php echo $no_rekening; ?></b></div></li>
	  		  <?php } ?>
                <li><?php echo $text_belum_ppn; ?></li>
                <li><?php echo $text_tukar_bukti; ?></li>
                <li><?php echo $text_ubah_tanggal; ?></li>
                <li><?php echo $text_pembatalan_acara; ?>
	  		    <div style="margin-left: 10px;"><b>
	  			  <?php echo $text_pembatalan_1; ?><br />
	  			  <?php echo $text_pembatalan_2; ?><br />
	  			  <?php echo $text_pembatalan_3; ?>
	  			</b></div>
	  		  </li>
	  		</ol>
            </td>
          </tr>
        </tbody>
      </table>
      <table class="table table-application text-center page-content">
        <tbody>
          <tr>
            <td colspan="2" class="text-justify">
              <b><?php echo $text_demikian; ?></b><br /><br />
            </td>
          </tr>
          <tr>
            <td style="width: 50%;"><?php echo $text_pihak_penyewa; ?></td>
            <td><?php echo $store_owner; ?><br /><br /><br /><br /></td>
          </tr>
          <tr>
            <td><u><?php echo '( ' . $customer . ' )'; ?></u></td>
            <td><u><?php echo $manajemen; ?></u><br /><?php echo $text_manajemen; ?></td>
          </tr>
        </tbody>
      </table>
      <?php if ($comment) { ?>
      <table class="table table-application">
        <thead>
          <tr>
            <td><b><?php echo $text_comment; ?></b></td>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><?php echo $comment; ?></td>
          </tr>
        </tbody>
      </table>
      <?php } ?>
    </div>
  </div>
</div>
</body>
</html>
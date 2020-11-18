<!DOCTYPE html>
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<head>
<meta charset="UTF-8" />
<title><?php echo $title_vendor_agreement; ?></title>
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
  <?php if ($preview) { ?>
  <div id="background">
    <p class="bg-text" id="bg-vendor-agreement"><?php echo $text_mark; ?></p>
  </div>
  <?php } ?>
  <div class="page-content <?php echo $letter_content; ?>">
    <div class="letter-head">
	  <img src="<?php echo $letter_head; ?>" class="img-responsive" />
    </div>
    <div>
      <table class="table table-application">
        <tbody>
          <tr>
            <td colspan="4" class="text-center">
	  		<h3 class="text-center"><?php echo $title_vendor_agreement; ?></h3>
              <?php if ($invoice_no) { ?>
              <h4><?php echo $text_invoice_no; ?> <?php echo $invoice_no; ?></h4>
              <?php } ?>
            </td>
          </tr>
          <tr>
            <td colspan="4">
              <?php echo $text_place_date; ?>
            </td>
          </tr>
          <tr>
            <td style="width: 5%;"></td>
            <td style="width: 30%;"><?php echo $text_customer; ?></td>
            <td style="width: 1%;">:</td>
            <td style="width: 64%;"><?php echo $text_garis; ?></td>
          </tr>
          <tr>
            <td></td><td><?php echo $text_vendor_name; ?></td><td>:</td><td><?php echo $vendor_name; ?></td>
          </tr>
          <tr>
            <td></td><td><?php echo $text_address; ?></td><td>:</td><td><?php echo $address; ?></td>
          </tr>
          <tr>
            <td></td><td><?php echo $text_telephone; ?></td><td>:</td><td><?php echo $telephone; ?></td>
          </tr>
          <tr>
            <td colspan="4" class="text-justify">
              <br /><?php echo $text_ketentuan; ?>
              <br /><br /><?php echo $text_mohon_surat; ?>
              <br /><br /><?php echo $text_apabila_anda; ?>
              <br /><br /><?php echo $text_uang_jaminan; ?>
              <br /><br /><?php echo $text_uang_dikembalikan; ?>
              <br /><br /><?php echo $text_silahkan_transfer; ?>
              <p class="text-center"><b><?php echo $no_rekening; ?></b></p>
              <br />
            </td>
          </tr>
        </tbody>
      </table>
      <table class="table table-application text-center page-content">
        <tbody>
          <tr>
            <td colspan="2" class="text-justify">
			  <?php echo $text_vendor_setuju; ?><br /><br />
              <?php echo $text_lebih_lanjut; ?><br /><br />
            </td>
          </tr>
          <tr>
            <td style="width: 50%;"><?php echo $text_hormat_kami; ?></td>
            <td><?php echo $text_menyetujui; ?><br /><br /><br /><br /></td>
          </tr>
          <tr>
            <td><u><b><?php echo $manajemen; ?></b></u><br /><?php echo $text_manajemen; ?></td>
            <td><?php echo $text_tanda_tangan; ?></td>
          </tr>
        </tbody>
      </table>
	</div>
  </div>
</div>
</body>
</html>
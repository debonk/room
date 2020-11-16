<div class="table-responsive">
  <table class="table table-bordered table-hover">
    <thead>
      <tr>
        <td class="text-left"><?php echo $column_date; ?></td>
        <td class="text-left"><?php echo $column_account; ?></td>
        <td class="text-left"><?php echo $column_reference_no; ?></td>
        <td class="text-left"><?php echo $column_description; ?></td>
        <td class="text-left"><?php echo $column_customer_name; ?></td>
        <td class="text-right"><?php echo $column_debit; ?></td>
        <td class="text-right"><?php echo $column_credit; ?></td>
        <td class="text-right"><?php echo $column_balance; ?></td>
      </tr>
      <tr class="info">
        <td class="text-right" colspan="5"><?php echo $text_balance_start; ?></td>
        <td></td><td></td>
        <td class="text-right"><?php echo $balance_start; ?></td>
      </tr>
    </thead>
    <tbody>
      <?php if ($transactions) { ?>
      <?php foreach ($transactions as $transaction) { ?>
      <tr>
        <td class="text-left"><?php echo $transaction['date']; ?></td>
        <td class="text-left"><?php echo $transaction['account']; ?></td>
        <td class="text-left"><a href="<?php echo $transaction['href']; ?>" target="_blank"><?php echo $transaction['reference_no']; ?></a></td>
        <td class="text-left"><?php echo $transaction['description']; ?></td>
        <td class="text-left"><?php echo $transaction['customer_name']; ?></td>
        <td class="text-right"><?php echo $transaction['debit']; ?></td>
        <td class="text-right"><?php echo $transaction['credit']; ?></td>
        <td class="text-right"><b><?php echo $transaction['balance']; ?></b></td>
      </tr>
      <?php } ?>
      <?php } else { ?>
      <tr>
        <td class="text-center" colspan="8"><?php echo $text_no_results; ?></td>
      </tr>
      <?php } ?>
    </tbody>
    <tfoot>
      <tr class="info">
        <td class="text-right" colspan="5"><?php echo $text_total; ?></td>
        <td class="text-right"><?php echo $total_debit; ?></td>
        <td class="text-right"><?php echo $total_credit; ?></td>
        <td></td>
      </tr>
    </tfoot>
  </table>
</div>
<div class="row">
  <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
  <div class="col-sm-6 text-right"><?php echo $results; ?></div>
</div>

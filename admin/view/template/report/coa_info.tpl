<div class="table-responsive">
  <table class="table table-bordered table-hover">
    <thead>
      <tr>
        <td class="text-left"><?php echo $column_date; ?></td>
        <td class="text-left"><?php echo $column_transaction_type; ?></td>
        <td class="text-left"><?php echo $column_description; ?></td>
        <td class="text-right"><?php echo $column_debit; ?></td>
        <td class="text-right"><?php echo $column_credit; ?></td>
        <td class="text-right"><?php echo $column_balance; ?></td>
        <td class="text-left"><?php echo $column_account; ?></td>
      </tr>
      <tr class="info">
        <td class="text-right" colspan="3"><?php echo $text_balance_end; ?></td>
        <td></td><td></td>
        <td class="text-right"><?php echo $balance_end; ?></td>
        <td></td>
      </tr>
    </thead>
    <tbody>
      <?php if ($transactions) { ?>
      <?php foreach ($transactions as $transaction) { ?>
      <tr>
        <td class="text-left"><?php echo $transaction['date']; ?></td>
        <td class="text-left"><?php echo $transaction['transaction_type']; ?></td>
        <td class="text-left"><a href="<?php echo $transaction['href']; ?>" target="_blank"><?php echo $transaction['reference']; ?></a><br><?php echo $transaction['description']; ?></td>
        <td class="text-right"><?php echo $transaction['debit']; ?></td>
        <td class="text-right"><?php echo $transaction['credit']; ?></td>
        <td class="text-right"><b><?php echo $transaction['balance']; ?></b></td>
        <td class="text-left">
					<?php foreach ($transaction['account'] as $account) { ?>
						<?php echo $account; ?><br>
						<?php } ?>
				</td>
      </tr>
      <?php } ?>
      <?php } else { ?>
      <tr>
        <td class="text-center" colspan="7"><?php echo $text_no_results; ?></td>
      </tr>
      <?php } ?>
    </tbody>
    <tfoot>
      <tr class="info">
        <td class="text-right" colspan="3"><?php echo $text_total . ' / ' . $text_balance_start; ?></td>
        <td class="text-right"><cite><?php echo $total_debit; ?></cite></td>
        <td class="text-right"><cite><?php echo $total_credit; ?></cite></td>
        <td class="text-right"><?php echo $balance_start; ?></td>
        <td></td>
      </tr>
    </tfoot>
  </table>
</div>
<div class="row">
  <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
  <div class="col-sm-6 text-right"><?php echo $results; ?></div>
</div>

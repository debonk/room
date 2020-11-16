<div class="products-filter-panel">
  <div class="row">
    <div class="col-md-1 text-left">
      <label class="control-label" for="input-limit"><?php echo $text_limit; ?></label>
    </div>
    <div class="col-md-2 text-left">
      <div class="select-wrap"><select id="input-limit" class="form-control" onchange="location = this.value;">
        <?php foreach ($limits as $limits) { ?>
        <?php if ($limits['value'] == $limit) { ?>
        <option value="<?php echo $limits['href']; ?>" selected="selected"><?php echo $limits['text']; ?></option>
        <?php } else { ?>
        <option value="<?php echo $limits['href']; ?>"><?php echo $limits['text']; ?></option>
        <?php } ?>
        <?php } ?>
      </select></div>
    </div>
    <div class="col-md-2 text-left">
      <label class="control-label text-sort" for="input-sort"><?php echo $text_sort; ?></label>
    </div>
    <div class="col-md-3 text-left">
      <div class="select-wrap"><select id="input-sort" class="form-control" onchange="location = this.value;">
        <?php foreach ($sorts as $sorts) { ?>
        <?php if ($sorts['value'] == $sort . '-' . $order) { ?>
        <option value="<?php echo $sorts['href']; ?>" selected="selected"><?php echo $sorts['text']; ?></option>
        <?php } else { ?>
        <option value="<?php echo $sorts['href']; ?>"><?php echo $sorts['text']; ?></option>
        <?php } ?>
        <?php } ?>
      </select></div>
    </div>
    <div class="col-md-4 text-right">
      <div class="btn-group hidden-xs display">
        <button type="button" id="grid-view" class="btn btn-switch active" data-toggle="tooltip" title="<?php echo $button_grid; ?>">
          <i class="fa fa-th"></i>
        </button>
        <button type="button" id="list-view" class="btn btn-switch" data-toggle="tooltip" title="<?php echo $button_list; ?>">
          <i class="fa fa-list"></i>
        </button>
      </div>
    </div>
  </div>
</div>   
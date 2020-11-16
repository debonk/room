<div class="row">
	<legend class="text-center"><?php echo $title; ?></legend>
	<div class="table-responsive">
	  <table class="table table-bordered">
		<thead>
		<tr>
		<?php foreach ($weekdays as $weekday) { ?>
		  <td class="text-center calendar-day-head"><?php echo $weekday; ?></td>
		<?php } ?>
		</tr>
		</thead>
		<tbody>
		  <?php $day = 0; ?>
		  <?php foreach ($calendars as $calendar) { ?>
		  <?php if ($day == 0) { ?>
		  <tr>
		  <?php } ?>
			<?php if ($calendar['date']) { ?>
			<td class="text-center calendar-day bg-<?php echo $calendar['class'] ?>">
			  <?php echo $calendar['text']; ?>
			  <div>
			  <?php foreach ($calendar['slot_data'] as $key => $value) { ?>
				<?php if ($value) { ?>
				<a href="<?php echo $calendar['url'] ?>" type="button" id="slot-<?php echo $calendar['date'] . $key; ?>" class="btn btn-default slot-<?php echo $key; ?>"><?php echo strtoupper($key); ?></a>
				<?php } else { ?>
				<a id="slot-<?php echo $calendar['date'] . $key; ?>"></a>
				<?php } ?>
			  <?php } ?>
			  </div>
					
			</td>
			<?php } else { ?>
			<td class="calendar-day-np"></td>
			<?php } ?>
			<?php $day++; ?>
		  <?php if ($day == 7) { ?>
		  </tr>
		  <?php $day = 0; ?>
		  <?php } ?>
		  <?php } ?>
		</tbody>
	  </table>
	</div>
</div>

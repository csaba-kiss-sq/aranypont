<section class="title">
	<h4><?php echo lang('observer:dashboard') ?></h4>
</section>

<section class="item">
	<div class="content">

		<?php echo form_input('created_on', $date, 'maxlength="10" id="datepicker" class="text width-20 inputGridDate"') ?>
		<br />
		<br />

	<?php if ($prices && $products): ?>
		<table border="0" class="table-list" cellspacing="0">
			<thead>
			<tr>
				<th></th>
			<?php foreach ($products as $product): ?>
				<th><?php echo $product['title']; ?></th>
			<?php endforeach; ?>
			</tr>
			</thead>
			<tbody>
			<?php foreach ($prices as $price): ?>
				<tr>
					<?php if(isset($price['title'] )): ?>
					<td><?php echo $price['title']; ?></td>
					<?php else: ?>
					<td>&nbsp;</td>
					<?php endif; ?>
				<?php for($i=0;$i<5;$i++): ?>
					<?php if(isset($price['values'][$i])): ?>
					<td><?php echo $price['values'][$i] ?></td>
					<?php else: ?>
					<td>&nbsp;</td>
					<?php endif; ?>
				<?php endfor; ?>
					<td class="align-center buttons buttons-small">
						<?php echo anchor('admin/observer/merchant/'.$price['id'], lang('global:view'), 'class="button view"') ?>
					</td>
				</tr>
			<?php endforeach ?>
			</tbody>
		</table>
	<?php else: ?>
		<div class="no_data"><?php echo lang('merchants:no_categories') ?></div>
	<?php endif ?>
	</div>
</section>

<script type="text/javascript">
$(document).ready(function() {
	$('.inputGridDate').on("change", function(e) {
		e.preventDefault();
		window.location = "http://localhost/aranypont/index.php/admin/observer/grid/" + $(".inputGridDate").val();
	});
})
</script>
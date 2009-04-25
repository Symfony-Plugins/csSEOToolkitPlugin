<?php if ($meta): ?>

	<?php foreach ($metas as $data): ?>
		<?php echo $data ?>
	<?php endforeach ?>
	<title><?php echo $meta['title'] ?></title>
	<?php if (isset($form)): ?>
		<?php slot('seo_admin_bar') ?>
			<?php include_component('csSEO', 'meta_data_admin_bar', array('form' => $form)) ?>
		<?php end_slot() ?>		
	<?php endif ?>

<?php endif ?>

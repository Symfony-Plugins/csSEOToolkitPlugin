<?php include_http_metas() ?>
<!-- user-set / autogen metas -->
<?php if ($meta = get_component('home', 'metas', array())): ?>
	<?php echo $meta ?>
<?php else: ?>
	<?php MetaData::generateMetaData($sf_content, $sf_request) ?>
	<?php include_component('home', 'metas', array()) ?>
<?php endif ?>
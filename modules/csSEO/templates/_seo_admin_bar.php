<script>
	function toggleMetaData(e)
	{
		var meta_form = document.getElementById('meta_form');
		if(meta_form.style.display == 'block')
		{
			meta_form.style.display = 'none'; 
			e.innerHTML = 'Edit Metadata';
		}
		else
		{
			meta_form.style.display = 'block'; 
			e.innerHTML = 'Hide Metadata';
		}
	}
	function toggleSitemapData(e)
	{
		var sitemap_form = document.getElementById('sitemap_form');
		if(sitemap_form.style.display == 'block')
		{
			sitemap_form.style.display = 'none'; 
			e.innerHTML = 'Edit Sitemap Data';
		}
		else
		{
			sitemap_form.style.display = 'block'; 
			e.innerHTML = 'Edit Sitemap Data';
		}
	}
	
</script>
<ul id='admin_bar'>
	<?php if (isset($metaform)): ?>
	<li>
		<a href="#" onclick="toggleMetaData(this)">Edit Metadata</a>
		<div id='meta_form' style='display:none'>
			<?php use_helper('Form') ?>
			<?php echo form_tag('@meta_data_edit') ?>
			<?php echo $metaform ?>
			<?php echo submit_tag('Submit') ?>	
			</form>
		</div>
	</li>
	<?php endif ?>
	<?php if (isset($sitemapform)): ?>
	<li>
		<a href="#" onclick="toggleSitemapData(this)">Edit Sitemap Data</a>
		<div id='sitemap_form' style='display:none'>
			<?php use_helper('Form') ?>
			<?php echo form_tag('@sitemap_xml_edit') ?>
			Priority:<br />
			<div class="horizontal_track" >
	    	<div class="horizontal_slit" >&nbsp;</div>
	    	<div class="horizontal_slider"
	        id="your_slider_id"
	        style="left: <?php echo (100 * $sitemapform->getObject()->getPriority()) ?>px"
	        onmousedown="slide(event,
	        'horizontal', 100, 0, 1, 101,
	        1, 'priority_slider');" >&nbsp;</div>
			</div>
			<?php echo $sitemapform ?>
			<?php echo submit_tag('Submit') ?>
			</form>
		</div>
	</li>
	<?php endif; ?>
</ul>
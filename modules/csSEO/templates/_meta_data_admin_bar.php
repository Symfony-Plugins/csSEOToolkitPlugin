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
</script>

<div id='admin_bar'>
	<a href="#" onclick="toggleMetaData(this)">Edit Metadata</a>
	<div id='meta_form' style='display:none'>
		<?php use_helper('Form') ?>
		<?php echo form_tag('@meta_data_edit') ?>
		<?php echo $form ?>
		<?php echo submit_tag('Submit') ?>
		</form>
	</div>
</div>
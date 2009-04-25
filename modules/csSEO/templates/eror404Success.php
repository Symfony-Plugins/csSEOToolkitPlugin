<h1>We're Sorry. This page is not found.</h1>
<p>
	The page you are looking for cannot be found. Please use the navigation above, or 
	<?php echo link_to('click here', '@homepage') ?>
	to go to the homepage.
</p>
<?php if ($results->count()): ?>
	<br />
	<div class="searchresults">
	  <h2>Could you possibly have meant one of these pages?</h2>		
	</div>
	<div class='search_container'>
		<?php foreach ($results as $result): ?>
			<div class='joblisting'> 	
				<h2>
					<?php echo link_to($result['title'], $result['route']) ?>
				</h2>
				<div class='textwrap'>
					<?php echo $result['teaser'] ?>
				</div>
			</div>
		<?php endforeach ?>	
	</div>
<?php endif; ?>
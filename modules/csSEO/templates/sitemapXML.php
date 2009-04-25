<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
	<?php foreach ($items as $item): ?>
		<url>
			<loc><?php echo xml_url_for($item->url) ?></loc>
			<changeFreq>monthly</changeFreq>
			<priority>0.8</priority>
			<lastmod>0.8</lastmod>
		</url>
	<?php endforeach ?>
</urlset>
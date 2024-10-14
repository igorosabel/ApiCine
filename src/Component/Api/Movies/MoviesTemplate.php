<?php foreach ($list as $i => $movie): ?>
	{
		"id": <?php echo $movie->get('id') ?>,
		"idCinema": <?php echo $movie->get('id_cinema') ?>,
		"name": "<?php echo urlencode($movie->get('name')) ?>",
		"slug": "<?php echo $movie->get('slug') ?>",
		"cover": "<?php echo urlencode($movie->getCoverUrl()) ?>",
		"ticket": "<?php echo urlencode($movie->getTicketUrl()) ?>",
		"imdbUrl": "<?php echo urlencode($movie->get('imdb_url')) ?>",
		"date": "<?php echo urlencode($movie->get('movie_date', 'd/m/Y')) ?>"
	}<?php if ($i<count($list) - 1): ?>,<?php endif ?>
<?php endforeach ?>

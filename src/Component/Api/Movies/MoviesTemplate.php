<?php foreach ($list as $i => $movie): ?>
	{
		"id": <?php echo $movie->id ?>,
		"idCinema": <?php echo $movie->id_cinema ?>,
		"name": "<?php echo urlencode($movie->name) ?>",
		"slug": "<?php echo $movie->slug ?>",
		"cover": "<?php echo urlencode($movie->getCoverUrl()) ?>",
		"ticket": "<?php echo urlencode($movie->getTicketUrl()) ?>",
		"imdbUrl": "<?php echo urlencode($movie->imdb_url) ?>",
		"date": "<?php echo urlencode($movie->get('movie_date', 'd/m/Y')) ?>"
	}<?php if ($i < count($list) - 1): ?>,<?php endif ?>
<?php endforeach ?>

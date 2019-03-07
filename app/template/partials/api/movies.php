<?php foreach ($values['list'] as $i => $movie): ?>
  {
    "id": <?php echo $movie->get('id') ?>,
    "idCinema": <?php echo $movie->get('id_cinema') ?>,
	  "name": <?php echo urlencode($movie->get('name')) ?>,
	  "cover": <?php echo urlencode($movie->getCoverUrl()) ?>,
    "ticket": <?php echo urlencode($movie->getTicketUrl()) ?>,
    "imdbUrl": <?php echo urlencode($movie->get('imdb_url')) ?>,
    "date": <?php echo urlencode($movie->get('movie_date', 'd/m/Y')) ?>
  }<?php if ($i<count($values['list'])-1): ?>,<?php endif ?>
<?php endforeach ?>
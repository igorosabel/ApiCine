<?php	use Osumi\OsumiFramework\App\Component\Model\CompanionList\CompanionListComponent; ?>
<?php if (is_null($movie)): ?>
null
<?php else: ?>
{
	"id": {{ movie.id }},
	"idCinema": {{ movie.id_cinema }},
	"name": {{ movie.name | string }},
	"slug": {{ movie.slug | string }},
	"cover": "<?php echo urlencode($movie->getCoverUrl()) ?>",
	"ticket": "<?php echo urlencode($movie->getTicketUrl()) ?>",
	"imdbUrl": {{ movie.imdb_url | string }},
	"date": {{ movie.movie_date | date("d/m/Y") }},
	"companions": [<?php echo new CompanionListComponent(['list' => $movie->getCompanions()]) ?>]
}
<?php endif ?>

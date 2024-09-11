<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\Api\SaveMovie;

use Osumi\OsumiFramework\Routing\OAction;
use Osumi\OsumiFramework\Tools\OTools;
use Osumi\OsumiFramework\App\DTO\MovieDTO;
use Osumi\OsumiFramework\App\Model\Movie;

class SaveMovieAction extends OAction {
	public string $status = 'ok';

	/**
	 * Función para guardar una nueva entrada
	 *
	 * @param MovieDTO $data DTO con los datos de la película a guardar
	 * @return void
	 */
	public function run(MovieDTO $data):void {
		if ($data->isValid()) {
			$id_cinema    = $data->getIdCinema();
			$name         = $data->getName();
			$cover        = $data->getCover();
			$cover_status = $data->getCoverStatus();
			$ticket       = $data->getTicket();
			$imdb_url     = $data->getImdbUrl();
			$date         = $data->getDate();
			$filter       = $data->getFilter();
		}
		else {
			$this->status = 'error';
		}

		if ($this->status=='ok') {
			$movie = new Movie();
			$movie->set('id_user',    $filter['id']);
			$movie->set('id_cinema',  $id_cinema);
			$movie->set('name',       $name);
			$movie->set('slug',       OTools::slugify($name));
			$movie->set('imdb_url',   $imdb_url);
			$movie->set('movie_date', $this->service['Web']->getParsedDate($date));
			$movie->save();

			$cover_ext = null;
			if ($cover_status==2) {
				$cover_ext = array_pop(explode('.', $cover));
			}
			else {
				$cover_ext = $this->service['Web']->getImageExt($cover);
			}
			$ticket_ext = $this->service['Web']->getImageExt($ticket);

			$this->service['Web']->saveTicket($ticket, $movie->get('id'), $ticket_ext);
			if ($cover_status==2) {
				$tmdb_cover = file_get_contents($cover);
				$this->service['Web']->saveCoverImage($tmdb_cover, $movie->get('id'), $cover_ext);
			}
			else {
				$this->service['Web']->saveCover($cover, $movie->get('id'), $cover_ext);
			}
		}
	}
}

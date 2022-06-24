<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Tools\OTools;
use OsumiFramework\App\DTO\MovieDTO;
use OsumiFramework\App\Model\Movie;

#[OModuleAction(
	url: '/save-movie',
	filters: ['login'],
	services: ['web']
)]
class saveMovieAction extends OAction {
	/**
	 * Función para guardar una nueva entrada
	 *
	 * @param MovieDTO $data DTO con los datos de la película a guardar
	 * @return void
	 */
	public function run(MovieDTO $data):void {
		$status = 'ok';
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
			$status = 'error';
		}

		if ($status=='ok') {
			$movie = new Movie();
			$movie->set('id_user',    $filter['id']);
			$movie->set('id_cinema',  $id_cinema);
			$movie->set('name',       $name);
			$movie->set('slug',       OTools::slugify($name));
			$movie->set('imdb_url',   $imdb_url);
			$movie->set('movie_date', $this->web_service->getParsedDate($date));
			$movie->save();

			$cover_ext = null;
			if ($cover_status==2) {
				$cover_ext = array_pop(explode('.', $cover));
			}
			else {
				$cover_ext = $this->web_service->getImageExt($cover);
			}
			$ticket_ext = $this->web_service->getImageExt($ticket);

			$this->web_service->saveTicket($ticket, $movie->get('id'), $ticket_ext);
			if ($cover_status==2) {
				$tmdb_cover = file_get_contents($cover);
				$this->web_service->saveCoverImage($tmdb_cover, $movie->get('id'), $cover_ext);
			}
			else {
				$this->web_service->saveCover($cover, $movie->get('id'), $cover_ext);
			}
		}

		$this->getTemplate()->add('status', $status);
	}
}

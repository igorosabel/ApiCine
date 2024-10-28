<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\Api\SaveMovie;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Tools\OTools;
use Osumi\OsumiFramework\App\DTO\MovieDTO;
use Osumi\OsumiFramework\App\Service\WebService;
use Osumi\OsumiFramework\App\Model\Movie;

class SaveMovieComponent extends OComponent {
	private ?WebService $ws = null;

	public string $status = 'ok';

	public function __construct() {
		parent::__construct();
		$this->ws = inject(WebService::class);
	}

	/**
	 * Función para guardar una nueva entrada
	 *
	 * @param MovieDTO $data DTO con los datos de la película a guardar
	 * @return void
	 */
	public function run(MovieDTO $data): void {
		if (!$data->isValid()) {
			$this->status = 'error';
		}

		if ($this->status === 'ok') {
			$movie = new Movie();
			$movie->id_user    = $data->filter['id'];
			$movie->id_cinema  = $data->id_cinema;
			$movie->name       = $data->name;
			$movie->slug       = OTools::slugify($data->name);
			$movie->imdb_url   = $data->imdb_url;
			$movie->movie_date = $this->ws->getParsedDate($data->date);
			$movie->save();

			$cover_ext = null;
			if ($data->cover_status === 2) {
				$cover_ext = array_pop(explode('.', $data->cover));
			}
			else {
				$cover_ext = $this->ws->getImageExt($data->cover);
			}
			$ticket_ext = $this->ws->getImageExt($data->ticket);

			$this->ws->saveTicket($data->ticket, $movie->id, $ticket_ext);
			if ($data->cover_status === 2) {
				$tmdb_cover = file_get_contents($data->cover);
				$this->ws->saveCoverImage($tmdb_cover, $movie->id, $cover_ext);
			}
			else {
				$this->ws->saveCover($data->cover, $movie->id, $cover_ext);
			}
		}
	}
}

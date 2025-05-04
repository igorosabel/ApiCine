<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\Api\SaveMovie;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Tools\OTools;
use Osumi\OsumiFramework\App\DTO\MovieDTO;
use Osumi\OsumiFramework\App\Service\MovieService;
use Osumi\OsumiFramework\App\Model\Movie;
use Osumi\OsumiFramework\App\Model\MovieCompanion;

class SaveMovieComponent extends OComponent {
	private ?MovieService $ms = null;

	public string $status = 'ok';

	public function __construct() {
		parent::__construct();
		$this->ms = inject(MovieService::class);
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
			$movie = Movie::create();
			$movie->id_user    = $data->idUser;
			$movie->id_cinema  = $data->idCinema;
			$movie->name       = urldecode($data->name);
			$movie->slug       = OTools::slugify($data->name);
			$movie->imdb_url   = urldecode($data->imdbUrl);
			$movie->movie_date = $this->ms->getParsedDate($data->date);
			$movie->save();

			$cover_ext = null;
			if ($data->coverStatus === 2) {
				$cover_ext = array_pop(explode('.', $data->cover));
			}
			else {
				$cover_ext = $this->ms->getImageExt($data->cover);
			}
			$ticket_ext = $this->ms->getImageExt($data->ticket);

			$this->ms->saveTicket(urldecode($data->ticket), $movie->id, $ticket_ext);
			if ($data->coverStatus === 2) {
				$tmdb_cover = file_get_contents(urldecode($data->cover));
				$this->ms->saveCoverImage($tmdb_cover, $movie->id, $cover_ext);
			}
			else {
				$this->ms->saveCover(urldecode($data->cover), $movie->id, $cover_ext);
			}

			foreach ($data->companions as $companion) {
				$mc = MovieCompanion::create();
				$mc->id_movie = $movie->id;
				$mc->id_companion = $companion['id'];
				$mc->save();
			}
		}
	}
}

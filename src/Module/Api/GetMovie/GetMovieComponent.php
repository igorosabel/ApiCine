<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\Api\GetMovie;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Model\Movie;

class GetMovieComponent extends OComponent {
	public string $status     = 'ok';
	public string | int $id         = -1;
	public string | int $id_cinema  = 'null';
	public string $name       = '';
	public string $slug       = '';
	public string $cover      = '';
	public string $ticket     = '';
	public string $imdb_url   = '';
	public string $movie_date = '';

	/**
	 * Función para obtener el detalle de una película
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req): void {
		$this->id = $req->getParamInt('id');
		$filter   = $req->getFilter('Login');

		if (is_null($this->id) || is_null($filter) || !array_key_exists('id', $filter)) {
			$this->status = 'error';
			$this->id = 'null';
		}

		if ($this->status === 'ok') {
			$movie = Movie::findOne(['id' => $this->id]);
			if (!is_null($movie)) {
				if ($movie->id_user === $filter['id']) {
					$this->id_cinema  = $movie->id_cinema;
					$this->name       = $movie->name;
					$this->slug       = $movie->slug;
					$this->cover      = $movie->getCoverUrl();
					$this->ticket     = $movie->getTicketUrl();
					$this->imdb_url   = $movie->imdb_url;
					$this->movie_date = $movie->get('movie_date', 'd/m/Y');
				}
				else {
					$this->status = 'error';
				}
			}
			else {
				$this->status = 'error';
			}
		}
	}
}

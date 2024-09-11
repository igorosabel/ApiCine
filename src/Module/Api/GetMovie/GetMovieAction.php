<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\Api\GetMovie;

use Osumi\OsumiFramework\Routing\OAction;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Model\Movie;

class GetMovieAction extends OAction {
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
	public function run(ORequest $req):void {
		$this->id = $req->getParamInt('id');
		$filter   = $req->getFilter('Login');

		if (is_null($this->id) || is_null($filter) || !array_key_exists('id', $filter)) {
			$this->status = 'error';
			$this->id = 'null';
		}

		if ($this->status=='ok') {
			$movie = new Movie();
			if ($movie->find(['id' => $this->id])) {
				if ($movie->get('id_user') == $filter['id']) {
					$this->id_cinema  = $movie->get('id_cinema');
					$this->name       = $movie->get('name');
					$this->slug       = $movie->get('slug');
					$this->cover      = $movie->getCoverUrl();
					$this->ticket     = $movie->getTicketUrl();
					$this->imdb_url   = $movie->get('imdb_url');
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

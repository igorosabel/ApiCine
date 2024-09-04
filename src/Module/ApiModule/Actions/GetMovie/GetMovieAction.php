<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\ApiModule\Actions\GetMovie;

use Osumi\OsumiFramework\Routing\OModuleAction;
use Osumi\OsumiFramework\Routing\OAction;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Model\Movie;

#[OModuleAction(
	url: '/get-movie',
	filters: ['Login']
)]
class GetMovieAction extends OAction {
	/**
	 * Función para obtener el detalle de una película
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$status     = 'ok';
		$id         = $req->getParamInt('id');
		$filter     = $req->getFilter('Login');
		$id_cinema  = 'null';
		$name       = '';
		$slug       = '';
		$cover      = '';
		$ticket     = '';
		$imdb_url   = '';
		$movie_date = '';

		if (is_null($id) || is_null($filter) || !array_key_exists('id', $filter)) {
			$status = 'error';
			$id = 'null';
		}

		if ($status=='ok') {
			$movie = new Movie();
			if ($movie->find(['id'=>$id])) {
				if ($movie->get('id_user')==$filter['id']) {
					$id_cinema  = $movie->get('id_cinema');
					$name       = $movie->get('name');
					$slug       = $movie->get('slug');
					$cover      = $movie->getCoverUrl();
					$ticket     = $movie->getTicketUrl();
					$imdb_url   = $movie->get('imdb_url');
					$movie_date = $movie->get('movie_date', 'd/m/Y');
				}
				else {
					$status = 'error';
				}
			}
			else {
				$status = 'error';
			}
		}

		$this->getTemplate()->add('status',     $status);
		$this->getTemplate()->add('id',         $id);
		$this->getTemplate()->add('id_cinema',  $id_cinema);
		$this->getTemplate()->add('name',       $name);
		$this->getTemplate()->add('slug',       $slug);
		$this->getTemplate()->add('cover',      $cover);
		$this->getTemplate()->add('ticket',     $ticket);
		$this->getTemplate()->add('imdb_url',   $imdb_url);
		$this->getTemplate()->add('movie_date', $movie_date);
	}
}

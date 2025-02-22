<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\Api\GetCinemaMovies;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Model\Cinema;
use Osumi\OsumiFramework\App\Component\Model\MovieList\MovieListComponent;

class GetCinemaMoviesComponent extends OComponent {
	public string $status = 'ok';
	public ?MovieListComponent $list = null;

	/**
	 * Función para obtener la lista de las últimas películas de un cine concreto
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req): void {
		$id     = $req->getParamInt('id');
		$filter = $req->getFilter('Login');
		$this->list = new MovieListComponent();

		if (is_null($id) || is_null($filter) || !array_key_exists('id', $filter)) {
			$this->status = 'error';
		}

		if ($this->status === 'ok') {
			$cinema = Cinema::findOne(['id' => $id]);
			if (!is_null($cinema)) {
				if ($cinema->id_user === $filter['id']) {
					$this->list->list = $cinema->getMovies();
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

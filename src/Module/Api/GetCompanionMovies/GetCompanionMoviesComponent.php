<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\Api\GetCompanionMovies;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Model\Companion;
use Osumi\OsumiFramework\App\Component\Model\MovieList\MovieListComponent;

class GetCompanionMoviesComponent extends OComponent {
	public string $status = 'ok';
	public ?MovieListComponent $list = null;

	/**
	 * Función para obtener la lista de las películas vistas con un acompañante
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
			$companion = Companion::findOne(['id' => $id]);
			if (!is_null($companion)) {
				if ($companion->for_user === $filter['id']) {
					$this->list->list = $companion->getMovies();
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

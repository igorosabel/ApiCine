<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\ApiModule\Actions\GetCinemaMovies;

use Osumi\OsumiFramework\Routing\OModuleAction;
use Osumi\OsumiFramework\Routing\OAction;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Model\Cinema;
use Osumi\OsumiFramework\App\Component\Api\MoviesComponent\MoviesComponent;

#[OModuleAction(
	url: '/get-cinema-movies',
	filters: ['Login']
)]
class GetCinemaMoviesAction extends OAction {
	/**
	 * Función para obtener la lista de las últimas películas de un cine concreto
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$status = 'ok';
		$id     = $req->getParamInt('id');
		$filter = $req->getFilter('Login');
		$movies_component = new MoviesComponent(['list' => []]);

		if (is_null($id) || is_null($filter) || !array_key_exists('id', $filter)) {
			$status = 'error';
		}

		if ($status=='ok') {
			$cinema = new Cinema();
			if ($cinema->find(['id'=>$id])) {
				if ($cinema->get('id_user')==$filter['id']) {
					$list = $cinema->getMovies();
					$movies_component->setValue('list', $list);
				}
				else {
					$status = 'error';
				}
			}
			else {
				$status = 'error';
			}
		}

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->add('list',   $movies_component);
	}
}

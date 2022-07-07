<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\App\Model\Cinema;
use OsumiFramework\App\Component\Api\MoviesComponent;

#[OModuleAction(
	url: '/get-cinema-movies',
	filters: ['login'],
	services: ['web']
)]
class getCinemaMoviesAction extends OAction {
	/**
	 * Función para obtener la lista de las últimas películas de un cine concreto
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$status = 'ok';
		$id     = $req->getParamInt('id');
		$filter = $req->getFilter('login');
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

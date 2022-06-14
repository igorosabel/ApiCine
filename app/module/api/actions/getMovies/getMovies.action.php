<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\App\Component\MoviesComponent;

#[OModuleAction(
	url: '/get-movies',
	filter: 'login',
	services: ['web'],
	components: ['api/movies']
)]
class getMoviesAction extends OAction {
	/**
	 * Función para obtener la lista de las últimas películas
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$status = 'ok';
		$page   = $req->getParamInt('page');
		$filter = $req->getFilter('login');
		$num_pages = 0;
		$movies_component = new MoviesComponent(['list' => []]);

		if (is_null($page) || is_null($filter) || !array_key_exists('id', $filter)) {
			$status = 'error';
		}

		if ($status=='ok') {
			$list      = $this->web_service->getMovies($filter['id'], $page);
			$num_pages = $this->web_service->getMoviesPages($filter['id']);

			$movies_component->setValue('list', $list);
		}

		$this->getTemplate()->add('status',    $status);
		$this->getTemplate()->add('num_pages', $num_pages);
		$this->getTemplate()->add('list',      $movies_component);
	}
}

<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\ApiModule\Actions\GetMovies;

use Osumi\OsumiFramework\Routing\OModuleAction;
use Osumi\OsumiFramework\Routing\OAction;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Component\Api\MoviesComponent\MoviesComponent;

#[OModuleAction(
	url: '/get-movies',
	filters: ['Login'],
	services: ['Web']
)]
class GetMoviesAction extends OAction {
	/**
	 * Función para obtener la lista de las últimas películas
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$status = 'ok';
		$page   = $req->getParamInt('page');
		$filter = $req->getFilter('Login');
		$num_pages = 0;
		$movies_component = new MoviesComponent(['list' => []]);

		if (is_null($page) || is_null($filter) || !array_key_exists('id', $filter)) {
			$status = 'error';
		}

		if ($status=='ok') {
			$list      = $this->service['Web']->getMovies($filter['id'], $page);
			$num_pages = $this->service['Web']->getMoviesPages($filter['id']);

			$movies_component->setValue('list', $list);
		}

		$this->getTemplate()->add('status',    $status);
		$this->getTemplate()->add('num_pages', $num_pages);
		$this->getTemplate()->add('list',      $movies_component);
	}
}

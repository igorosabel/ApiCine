<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\App\Component\Api\MoviesComponent;

#[OModuleAction(
	url: '/search-titles',
	filters: ['login'],
	services: ['web']
)]
class searchTitlesAction extends OAction {
	/**
	 * Función para buscar películas entre las que el usuario ha visto
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$status = 'ok';
		$q      = $req->getParamString('q');
		$filter = $req->getFilter('login');
		$num_pages = 0;
		$movies_component = new MoviesComponent(['list' => []]);

		if (is_null($q) || is_null($filter) || !array_key_exists('id', $filter)) {
			$status = 'error';
		}

		if ($status=='ok') {
			$list      = $this->web_service->getMoviesByTitle($filter['id'], $q);
			$num_pages = $this->web_service->getMoviesPagesByTitle($filter['id'], $q);

			$movies_component->setValue('list', $list);
		}

		$this->getTemplate()->add('status',    $status);
		$this->getTemplate()->add('num_pages', $num_pages);
		$this->getTemplate()->add('list',      $movies_component);
	}
}

<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\ApiModule\Actions\GetMovies;

use Osumi\OsumiFramework\Routing\OModuleAction;
use Osumi\OsumiFramework\Routing\OAction;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Component\Api\Movies\MoviesComponent;

#[OModuleAction(
	url: '/get-movies',
	filters: ['Login'],
	services: ['Web']
)]
class GetMoviesAction extends OAction {
	public string $status = 'ok';
	public int $num_pages = 0;
	public ?MoviesComponent $list = null;

	/**
	 * Función para obtener la lista de las últimas películas
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$page   = $req->getParamInt('page');
		$filter = $req->getFilter('Login');
		$this->list = new MoviesComponent(['list' => []]);

		if (is_null($page) || is_null($filter) || !array_key_exists('id', $filter)) {
			$this->status = 'error';
		}

		if ($this->status=='ok') {
			$this->num_pages = $this->service['Web']->getMoviesPages($filter['id']);
			$this->list->setValue('list', $this->service['Web']->getMovies($filter['id'], $page));
		}
	}
}

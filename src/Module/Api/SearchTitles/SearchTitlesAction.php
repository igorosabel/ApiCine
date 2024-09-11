<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\Api\SearchTitles;

use Osumi\OsumiFramework\Routing\OAction;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Component\Api\MoviesComponent\MoviesComponent;

class SearchTitlesAction extends OAction {
	public string $status = 'ok';
	public int $num_pages = 0;
	public ?MoviesComponent $list = null;

	/**
	 * Función para buscar películas entre las que el usuario ha visto
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$q      = $req->getParamString('q');
		$filter = $req->getFilter('Login');
		$this->list = new MoviesComponent(['list' => []]);

		if (is_null($q) || is_null($filter) || !array_key_exists('id', $filter)) {
			$this->status = 'error';
		}

		if ($this->status=='ok') {
			$this->num_pages = $this->service['Web']->getMoviesPagesByTitle($filter['id'], $q);
			$this->list->setValue('list', $this->service['Web']->getMoviesByTitle($filter['id'], $q));
		}
	}
}

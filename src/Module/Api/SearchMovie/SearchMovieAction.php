<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\Api\SearchMovie;

use Osumi\OsumiFramework\Routing\OAction;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Service\WebService;
use Osumi\OsumiFramework\App\Component\Api\TmdbList\TmdbListComponent;

class SearchMovieAction extends OAction {
	private ?WebService $ws = null;

	public string $status = 'ok';
	public ?TmdbListComponent $list = null;

	public function __construct() {
		$this->ws = inject(WebService::class);
		$this->list = new TmdbListComponent(['list' => []]);
	}

	/**
	 * Función para buscar películas en The Movie Data Base
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$q      = $req->getParamString('q');
		$filter = $req->getFilter('Login');

		if (is_null($q) || is_null($filter) || !array_key_exists('id', $filter)) {
			$this->status = 'error';
		}

		if ($this->status === 'ok') {
			/*
				// Lista de películas
				https://api.themoviedb.org/3/search/movie?api_key=f54cd33501fddec9a5f6a82d27c61207&language=es-ES&query=angel%20de%20combate
				// Detalle de película
				https://api.themoviedb.org/3/movie/399579?api_key=f54cd33501fddec9a5f6a82d27c61207&language=es-ES
				// Poster
				http://image.tmdb.org/t/p/w300/XXXXX
				// IMDB URL
				https://www.imdb.com/title/XXXXX/
			*/
			$this->list->setValue('list', $this->ws->tmdbList($q));
		}
	}
}

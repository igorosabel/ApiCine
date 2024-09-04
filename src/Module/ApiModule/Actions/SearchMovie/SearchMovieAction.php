<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\ApiModule\Actions\SearchMovie;

use Osumi\OsumiFramework\Routing\OModuleAction;
use Osumi\OsumiFramework\Routing\OAction;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Component\Api\TmdbListComponent\TmdbListComponent;

#[OModuleAction(
	url: '/search-movie',
	filters: ['Login'],
	services: ['Web']
)]
class SearchMovieAction extends OAction {
	/**
	 * Función para buscar películas en The Movie Data Base
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$status = 'ok';
		$q      = $req->getParamString('q');
		$filter = $req->getFilter('Login');
		$tmdb_list_component = new TmdbListComponent(['list' => []]);

		if (is_null($q) || is_null($filter) || !array_key_exists('id', $filter)) {
			$status = 'error';
		}

		if ($status=='ok') {
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
			$list = $this->service['Web']->tmdbList($q);
			$tmdb_list_component->setValue('list', $list);
		}

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->add('list',   $tmdb_list_component);
	}
}
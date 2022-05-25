<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\App\Component\TmdbListComponent;

#[OModuleAction(
	url: '/search-movie',
	filter: 'login',
	services: 'web',
	components: 'api/tmdbList'
)]
class searchMovieAction extends OAction {
	/**
	 * Función para buscar películas en The Movie Data Base
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$status = 'ok';
		$q      = $req->getParamString('q');
		$filter = $req->getFilter('login');
		$tmdb_list_component = new TmdbListComponent(['list'=>[], 'extra'=>'nourlencode']);

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
			$list = $this->web_service->tmdbList($q);
			$tmdb_list_component = new TmdbListComponent(['list'=>$list, 'extra'=>'nourlencode']);
		}

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->add('list',   $tmdb_list_component);
	}
}

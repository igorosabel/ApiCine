<?php declare(strict_types=1);

namespace OsumiFramework\App\Module;

use OsumiFramework\OFW\Core\OModule;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\OFW\Routing\ORoute;
use OsumiFramework\App\Model\User;
use OsumiFramework\App\Model\Cinema;
use OsumiFramework\App\Model\Movie;
use OsumiFramework\App\Service\webService;
use OsumiFramework\OFW\Plugins\OToken;

#[ORoute(
	type: 'json',
	prefix: '/api'
)]
class api extends OModule {
	private ?webService $web_service = null;

	function __construct() {
		$this->web_service = new webService();
	}

	/**
	 * Función para iniciar sesión en la aplicación
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	#[ORoute('/login')]
	public function login(ORequest $req): void {
		$status = 'ok';
		$name   = $req->getParamString('name');
		$pass   = $req->getParamString('pass');

		$id    = 'null';
		$token = '';

		if (is_null($name) || is_null($pass)) {
			$status = 'error';
		}

		if ($status=='ok') {
			$u = new User();
			if ($u->find(['name'=>$name])) {
				if (password_verify($pass, $u->get('pass'))) {
					$id = $u->get('id');

					$tk = new OToken($this->getConfig()->getExtra('secret'));
					$tk->addParam('id',   $id);
					$tk->addParam('name', $name);
					$tk->addParam('exp', time() + (24 * 60 * 60));
					$token = $tk->getToken();
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
		$this->getTemplate()->add('id',     $id);
		$this->getTemplate()->add('name',   $name);
		$this->getTemplate()->add('token',  $token);
	}

	/**
	 * Función para registrarse en la aplicación
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	#[ORoute('/register')]
	public function register(ORequest $req): void {
		$status = 'ok';
		$name   = $req->getParamString('name');
		$pass   = $req->getParamString('pass');
		$id     = 'null';
		$token  = '';

		if (is_null($name) || is_null($pass)) {
			$status = 'error';
		}

		if ($status=='ok') {
			$u = new User();
			if ($u->find(['name'=>$name])) {
				$status = 'error-user';
			}
			else {
				$u->set('name', $name);
				$u->set('pass', password_hash($pass, PASSWORD_BCRYPT));
				$u->save();

				$id = $u->get('id');

				$tk = new OToken($this->getConfig()->getExtra('secret'));
				$tk->addParam('id',   $id);
				$tk->addParam('name', $name);
				$tk->addParam('exp', time() + (24 * 60 * 60));
				$token = $tk->getToken();
			}
		}

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->add('id',     $id);
		$this->getTemplate()->add('name',   $name);
		$this->getTemplate()->add('token',  $token);
	}

	/**
	 * Función para obtener la lista de cines
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	#[ORoute(
		'/get-cinemas',
		filter: 'loginFilter'
	)]
	public function getCinemas(ORequest $req): void {
		$status = 'ok';
		$filter = $req->getFilter('loginFilter');

		if (is_null($filter) || !array_key_exists('id', $filter)) {
			$status = 'error';
		}
		$list = [];

		if ($status=='ok') {
			$list = $this->web_service->getCinemas($filter['id']);
		}

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->addModelComponentList('list', $list, ['id_user', 'created_at', 'updated_at']);
	}

	/**
	 * Función para añadir un nuevo cine
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	#[ORoute(
		'/add-cinema',
		filter: 'loginFilter'
	)]
	public function addCinema(ORequest $req): void {
		$status = 'ok';
		$name   = $req->getParamString('name');
		$filter = $req->getFilter('loginFilter');

		if (is_null($name) || is_null($filter) || !array_key_exists('id', $filter)) {
			$status = 'error';
		}

		if ($status=='ok') {
			$cinema = new Cinema();
			$cinema->set('id_user', $filter['id']);
			$cinema->set('name', $name);
			$cinema->set('slug', OTools::slugify($name));

			$cinema->save();
		}

		$this->getTemplate()->add('status', $status);
	}

	/**
	 * Función para borrar un cine
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	#[ORoute(
		'/delete-cinema',
		filter: 'loginFilter'
	)]
	public function deleteCinema(ORequest $req): void {
		$status = 'ok';
		$id     = $req->getParamInt('id');
		$filter = $req->getFilter('loginFilter');

		if (is_null($id) || is_null($filter) || !array_key_exists('id', $filter)) {
			$status = 'error';
		}

		if ($status=='ok') {
			$cinema = new Cinema();
			if ($cinema->find(['id'=>$id])) {
				if ($cinema->get('id_user')==$filter['id']) {
					$this->web_service->deleteCinema($cinema);
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
	}

	/**
	 * Función para editar el nombre de un cine
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	#[ORoute(
		'/edit-cinema',
		filter: 'loginFilter'
	)]
	public function editCinema(ORequest $req): void {
		$status = 'ok';
		$id     = $req->getParamInt('id');
		$name   = $req->getParamString('name');
		$filter = $req->getFilter('loginFilter');

		if (is_null($id) || is_null($name) || is_null($filter) || !array_key_exists('id', $filter)) {
			$status = 'error';
		}

		if ($status=='ok') {
			$cinema = new Cinema();
			if ($cinema->find(['id'=>$id])) {
				if ($cinema->get('id_user')==$filter['id']) {
					$cinema->set('name', $name);
					$cinema->save();
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
	}

	/**
	 * Función para obtener la lista de las últimas películas
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	#[ORoute(
		'/get-movies',
		filter: 'loginFilter'
	)]
	public function getMovies(ORequest $req): void {
		$status = 'ok';
		$page   = $req->getParamInt('page');
		$filter = $req->getFilter('loginFilter');
		
		if (is_null($page) || is_null($filter) || !array_key_exists('id', $filter)) {
			$status = 'error';
		}
		$list      = [];
		$num_pages = 0;

		if ($status=='ok') {
			$list      = $this->web_service->getMovies($filter['id'], $page);
			$num_pages = $this->web_service->getMoviesPages($filter['id']);
		}

		$this->getTemplate()->add('status',    $status);
		$this->getTemplate()->add('num_pages', $num_pages);
		$this->getTemplate()->addComponent('list', 'api/movies', ['list'=>$list, 'extra'=>'nourlencode']);
	}

	/**
	 * Función para obtener la lista de las últimas películas de un cine concreto
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	#[ORoute(
		'/get-cinema-movies',
		filter: 'loginFilter'
	)]
	public function getCinemaMovies(ORequest $req): void {
		$status = 'ok';
		$id     = $req->getParamInt('id');
		$filter = $req->getFilter('loginFilter');
		$list   = [];

		if (is_null($id) || is_null($filter) || !array_key_exists('id', $filter)) {
			$status = 'error';
		}

		if ($status=='ok') {
			$cinema = new Cinema();
			if ($cinema->find(['id'=>$id])) {
				if ($cinema->get('id_user')==$filter['id']) {
					$list = $cinema->getMovies();
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
		$this->getTemplate()->addComponent('list', 'api/movies', ['list'=>$list, 'extra'=>'nourlencode']);
	}

	/**
	 * Función para guardar una nueva entrada
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	#[ORoute(
		'/save-movie',
		filter: 'loginFilter'
	)]
	public function saveMovie(ORequest $req): void {
		$status       = 'ok';
		$id_cinema    = $req->getParamInt('idCinema');
		$name         = $req->getParamString('name');
		$cover        = $req->getParam('cover',       false);
		$cover_status = $req->getParam('coverStatus', false);
		$ticket       = $req->getParam('ticket',      false);
		$imdb_url     = $req->getParamString('imdbUrl');
		$date         = $req->getParamString('date');
		$filter       = $req->getFilter('loginFilter');

		if (is_null($id_cinema) || is_null($name) || $cover===false || $cover_status===false || $ticket===false || is_null($imdb_url) || is_null($date) || is_null($filter) || !array_key_exists('id', $filter)) {
			$status = 'error';
		}

		if ($status=='ok') {
			$movie = new Movie();
			$movie->set('id_user',    $filter['id']);
			$movie->set('id_cinema',  $id_cinema);
			$movie->set('name',       $name);
			$movie->set('slug',       OTools::slugify($name));
			$movie->set('imdb_url',   $imdb_url);
			$movie->set('movie_date', $this->web_service->getParsedDate($date));

			if ($cover_status==2) {
				$movie->set('cover_ext', array_pop(explode('.', $cover)));
			}
			else {
				$movie->set('cover_ext', $this->web_service->getImageExt($cover));
			}
			$movie->set('ext', $this->web_service->getImageExt($ticket));
			$movie->save();

			$this->web_service->saveTicket($ticket, $movie->get('id'), $movie->get('ext'));
			if ($cover_status==2) {
				$tmdb_cover = file_get_contents($cover);
				$this->web_service->saveCoverImage($tmdb_cover, $movie->get('id'), $movie->get('cover_ext'));
			}
			else {
				$this->web_service->saveCover($cover, $movie->get('id'), $movie->get('cover_ext'));
			}
		}

		$this->getTemplate()->add('status', $status);
	}

	/**
	 * Función para buscar películas en The Movie Data Base
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	#[ORoute(
		'/search-movie',
		filter: 'loginFilter'
	)]
	public function searchMovie(ORequest $req): void {
		$status = 'ok';
		$q      = $req->getParamString('q');
		$filter = $req->getFilter('loginFilter');
		$list   = [];

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
		}

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->addComponent('list', 'api/tmdbList', ['list'=>$list, 'extra'=>'nourlencode']);
	}

	/**
	 * Función para obtener el detalle de una película en The Movie Data Base
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	#[ORoute(
		'/select-result',
		filter: 'loginFilter'
	)]
	public function selectResult(ORequest $req): void {
		$status   = 'ok';
		$id       = $req->getParamInt('id');
		$filter   = $req->getFilter('loginFilter');
		$title    = '';
		$poster   = '';
		$imdb_url = '';

		if (is_null($id) || is_null($filter) || !array_key_exists('id', $filter)) {
			$status = 'error';
		}

		if ($status=='ok') {
			$detail = $this->web_service->tmdbDetail($id);

			$title    = $detail['title'];
			$poster   = $detail['poster'];
			$imdb_url = $detail['imdb_url'];
		}

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->add('title', $title);
		$this->getTemplate()->add('poster', $poster);
		$this->getTemplate()->add('imdb_url', $imdb_url);
	}

	/**
	 * Función para obtener el detalle de una película
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	#[ORoute(
		'/get-movie',
		filter: 'loginFilter'
	)]
	public function getMovie(ORequest $req): void {
		$status     = 'ok';
		$id         = $req->getParamInt('id');
		$filter     = $req->getFilter('loginFilter');
		$id_cinema  = 'null';
		$name       = '';
		$slug       = '';
		$cover      = '';
		$ticket     = '';
		$imdb_url   = '';
		$movie_date = '';

		if (is_null($id) || is_null($filter) || !array_key_exists('id', $filter)) {
			$status = 'error';
			$id = 'null';
		}

		if ($status=='ok') {
			$movie = new Movie();
			if ($movie->find(['id'=>$id])) {
				if ($movie->get('id_user')==$filter['id']) {
					$id_cinema  = $movie->get('id_cinema');
					$name       = $movie->get('name');
					$slug       = $movie->get('slug');
					$cover      = $movie->getCoverUrl();
					$ticket     = $movie->getTicketUrl();
					$imdb_url   = $movie->get('imdb_url');
					$movie_date = $movie->get('movie_date', 'd/m/Y');
				}
				else {
					$status = 'error';
				}
			}
			else {
				$status = 'error';
			}
		}

		$this->getTemplate()->add('status',     $status);
		$this->getTemplate()->add('id',         $id);
		$this->getTemplate()->add('id_cinema',  $id_cinema);
		$this->getTemplate()->add('name',       $name);
		$this->getTemplate()->add('slug',       $slug);
		$this->getTemplate()->add('cover',      $cover);
		$this->getTemplate()->add('ticket',     $ticket);
		$this->getTemplate()->add('imdb_url',   $imdb_url);
		$this->getTemplate()->add('movie_date', $movie_date);
	}
}
<?php
class api extends OController{
  private $web_service;

  function __construct(){
    $this->web_service = new webService($this);
  }

  /*
   * Función para iniciar sesión en la aplicación
   */
  function login($req){
    $status = 'ok';
    $name   = Base::getParam('name', $req['url_params'], false);
    $pass   = Base::getParam('pass', $req['url_params'], false);

    $id    = 'null';
    $token = '';

    if ($name===false || $pass===false){
      $status = 'error';
    }

    if ($status=='ok'){
      $u = new User();
      if ($u->find(['name'=>$name])){
        if (password_verify($pass, $u->get('pass'))){
          $id = $u->get('id');

          $tk = new OToken($this->getConfig()->getExtra('secret'));
          $tk->addParam('id',   $id);
          $tk->addParam('name', $name);
          $tk->addParam('exp', mktime() + (24 * 60 * 60));
          $token = $tk->getToken();
        }
        else{
          $status = 'error';
        }
      }
      else{
        $status = 'error';
      }
    }

    $this->getTemplate()->add('status', $status);
    $this->getTemplate()->add('id',     $id);
    $this->getTemplate()->add('name',   $name);
    $this->getTemplate()->add('token',  $token);
  }

  /*
   * Función para registrarse en la aplicación
   */
  function register($req){
    $status = 'ok';
    $name   = Base::getParam('name', $req['url_params'], false);
    $pass   = Base::getParam('pass', $req['url_params'], false);
    $id     = 'null';
    $token  = '';

    if ($name===false || $pass===false){
      $status = 'error';
    }

    if ($status=='ok'){
      $u = new User();
      if ($u->find(['name'=>$name])){
        $status = 'error-user';
      }
      else{
        $u->set('name', $name);
        $u->set('pass', password_hash($pass, PASSWORD_BCRYPT));
        $u->save();

        $id = $u->get('id');

        $tk = new OToken($this->getConfig()->getExtra('secret'));
        $tk->addParam('id',   $id);
        $tk->addParam('name', $name);
        $tk->addParam('exp', mktime() + (24 * 60 * 60));
        $token = $tk->getToken();
      }
    }

    $this->getTemplate()->add('status', $status);
    $this->getTemplate()->add('id',     $id);
    $this->getTemplate()->add('name',   $name);
    $this->getTemplate()->add('token',  $token);
  }

  /*
   * Función para obtener la lista de cines
   */
  function getCinemas($req){
    $status = 'ok';
    if (!array_key_exists('filter', $req) || !array_key_exists('id', $req['filter'])){
      $status = 'error';
    }
    $list = [];

    if ($status=='ok'){
      $list = $this->web_service->getCinemas($req['filter']['id']);
    }

    $this->getTemplate()->add('status', $status);
    $this->getTemplate()->addPartial('list', 'api/cinemas', ['list'=>$list, 'extra'=>'nourlencode']);
  }

  /*
   * Función para añadir un nuevo cine
   */
  function addCinema($req){
    $status = 'ok';
    $name   = Base::getParam('name', $req['url_params'], false);

    if ($name===false){
      $status = 'error';
    }

    if ($status=='ok'){
      $cinema = new Cinema();
      $cinema->set('id_user', $req['filter']['id']);
      $cinema->set('name', $name);
      $cinema->set('slug', Base::slugify($name));

      $cinema->save();
    }

    $this->getTemplate()->add('status', $status);
  }

  /*
   * Función para borrar un cine
   */
  function deleteCinema($req){
    $status = 'ok';
    $id     = Base::getParam('id', $req['url_params'], false);

    if ($id===false){
      $status = 'error';
    }

    if ($status=='ok'){
      $cinema = new Cinema();
      if ($cinema->find(['id'=>$id])){
        $this->web_service->deleteCinema($cinema);
      }
      else{
        $status = 'error';
      }
    }

    $this->getTemplate()->add('status', $status);
  }

  /*
   * Función para editar el nombre de un cine
   */
  function editCinema($req){
    $status = 'ok';
    $id     = Base::getParam('id',   $req['url_params'], false);
    $name   = Base::getParam('name', $req['url_params'], false);

    if ($id===false || $name===false){
      $status = 'error';
    }

    if ($status=='ok'){
      $cinema = new Cinema();
      if ($cinema->find(['id'=>$id])){
        $cinema->set('name', $name);
        $cinema->save();
      }
      else{
        $status = 'error';
      }
    }

    $this->getTemplate()->add('status', $status);
  }

  /*
   * Función para obtener la lista de las últimas películas
   */
  function getMovies($req){
    $status = 'ok';
    $page   = Base::getParam('page', $req['url_params'], false);
    if (!array_key_exists('filter', $req) || !array_key_exists('id', $req['filter'])){
      $status = 'error';
    }
    if ($page===false){
      $status = 'error';
    }
    $list      = [];
    $num_pages = 0;

    if ($status=='ok'){
      $list = $this->web_service->getMovies($req['filter']['id'], $page);
      $num_pages = $this->web_service->getMoviesPages($req['filter']['id']);
    }

    $this->getTemplate()->add('status',    $status);
    $this->getTemplate()->add('num_pages', $num_pages);
    $this->getTemplate()->addPartial('list', 'api/movies', ['list'=>$list, 'extra'=>'nourlencode']);
  }

  /*
   * Función para obtener la lista de las últimas películas de un cine concreto
   */
  function getCinemaMovies($req){
    $status = 'ok';
    $id     = Base::getParam('id', $req['url_params'], false);
    $list   = [];

    if ($id===false){
      $status = 'error';
    }

    if ($status=='ok'){
      $cinema = new Cinema();
      if ($cinema->find(['id'=>$id])){
        $list = $cinema->getMovies();
      }
      else{
        $status = 'error';
      }
    }

    $this->getTemplate()->add('status', $status);
    $this->getTemplate()->addPartial('list', 'api/movies', ['list'=>$list, 'extra'=>'nourlencode']);
  }

  /*
   * Función para guardar una nueva entrada
   */
  function saveMovie($req){
    $status       = 'ok';
    $id_cinema    = Base::getParam('idCinema',    $req['url_params'], false);
  	$name         = Base::getParam('name',        $req['url_params'], false);
  	$cover        = Base::getParam('cover',       $req['url_params'], false);
  	$cover_status = Base::getParam('coverStatus', $req['url_params'], false);
  	$ticket       = Base::getParam('ticket',      $req['url_params'], false);
  	$imdb_url     = Base::getParam('imdbUrl',     $req['url_params'], false);
  	$date         = Base::getParam('date',        $req['url_params'], false);

  	if ($id_cinema===false || $name===false || $cover===false || $cover_status===false || $ticket===false || $imdb_url===false || $date===false){
    	$status = 'error';
  	}

  	if ($status=='ok'){
    	$movie = new Movie();
    	$movie->set('id_user',    $req['filter']['id']);
    	$movie->set('id_cinema',  $id_cinema);
    	$movie->set('name',       $name);
      $movie->set('slug',       Base::slugify($name));
    	$movie->set('imdb_url',   $imdb_url);
    	$movie->set('movie_date', $this->web_service->getParsedDate($date));

    	if ($cover_status==2){
        $movie->set('cover_ext', array_pop(explode('.', $cover)));
    	}
      else{
        $movie->set('cover_ext', $this->web_service->getImageExt($cover));
      }
    	$movie->set('ext', $this->web_service->getImageExt($ticket));

    	$movie->save();

    	$this->web_service->saveTicket($ticket, $movie->get('id'), $movie->get('ext'));
      if ($cover_status==2){
        $tmdb_cover = file_get_contents($cover);
        $this->web_service->saveCoverImage($tmdb_cover, $movie->get('id'), $movie->get('cover_ext'));
      }
      else{
    	  $this->web_service->saveCover($cover, $movie->get('id'), $movie->get('cover_ext'));
      }
  	}

  	$this->getTemplate()->add('status', $status);
  }

  /*
   * Función para buscar películas en The Movie Data Base
   */
  function searchMovie($req){
    $status = 'ok';
    $q      = Base::getParam('q', $req['url_params'], false);
    $list   = [];

    if ($q===false){
      $status = 'error';
    }

    if ($status=='ok'){
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
    $this->getTemplate()->addPartial('list', 'api/tmdbList', ['list'=>$list, 'extra'=>'nourlencode']);
  }

  /*
   * Función para obtener el detalle de una película en The Movie Data Base
   */
  function selectResult($req){
    $status   = 'ok';
    $id       = Base::getParam('id', $req['url_params'], false);
    $title    = '';
    $poster   = '';
    $imdb_url = '';

    if ($id===false){
      $status = 'error';
    }

    if ($status=='ok'){
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

  /*
   * Función para obtener el detalle de una película
   */
  function getMovie($req){
    $status     = 'ok';
    $id         = Base::getParam('id', $req['url_params'], false);
    $id_cinema  = 'null';
    $name       = '';
    $slug       = '';
    $cover      = '';
    $ticket     = '';
    $imdb_url   = '';
    $movie_date = '';

    if ($id===false){
      $status = 'error';
      $id = 'null';
    }

    if ($status=='ok'){
      $movie = new Movie();
      if ($movie->find(['id'=>$id])){
        $id_cinema  = $movie->get('id_cinema');
    	  $name       = $movie->get('name');
        $slug       = $movie->get('slug');
    	  $cover      = $movie->getCoverUrl();
        $ticket     = $movie->getTicketUrl();
        $imdb_url   = $movie->get('imdb_url');
        $movie_date = $movie->get('movie_date', 'd/m/Y');
      }
      else{
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
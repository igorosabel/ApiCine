<?php declare(strict_types=1);

namespace OsumiFramework\App\Module;

use OsumiFramework\OFW\Routing\OModule;

#[OModule(
	actions: ['addCinema', 'deleteCinema', 'editCinema', 'getCinemaMovies', 'getCinemas', 'getMovie', 'getMovies', 'login', 'register', 'saveMovie', 'searchMovie', 'searchTitles', 'selectResult', 'checkAuth'],
	type: 'json',
	prefix: '/api'
)]
class apiModule {}

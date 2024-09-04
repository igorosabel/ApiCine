<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\ApiModule;

use Osumi\OsumiFramework\Routing\OModule;

#[OModule(
	type: 'json',
	prefix: '/api',
	actions: ['AddCinema', 'DeleteCinema', 'EditCinema', 'GetCinemaMovies', 'GetCinemas', 'GetMovie', 'GetMovies', 'Login', 'Register', 'SaveMovie', 'SearchMovie', 'SearchTitles', 'SelectResult'],
)]
class ApiModule {}

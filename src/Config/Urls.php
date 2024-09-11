<?php declare(strict_types=1);

use Osumi\OsumiFramework\Routing\OUrl;
use Osumi\OsumiFramework\App\Module\Api\AddCinema\AddCinemaAction;
use Osumi\OsumiFramework\App\Module\Api\DeleteCinema\DeleteCinemaAction;
use Osumi\OsumiFramework\App\Module\Api\EditCinema\EditCinemaAction;
use Osumi\OsumiFramework\App\Module\Api\GetCinemaMovies\GetCinemaMoviesAction;
use Osumi\OsumiFramework\App\Module\Api\GetCinemas\GetCinemasAction;
use Osumi\OsumiFramework\App\Module\Api\GetMovie\GetMovieAction;
use Osumi\OsumiFramework\App\Module\Api\GetMovies\GetMoviesAction;
use Osumi\OsumiFramework\App\Module\Api\Login\LoginAction;
use Osumi\OsumiFramework\App\Module\Api\Register\RegisterAction;
use Osumi\OsumiFramework\App\Module\Api\SaveMovie\SaveMovieAction;
use Osumi\OsumiFramework\App\Module\Api\SearchMovie\SearchMovieAction;
use Osumi\OsumiFramework\App\Module\Api\SearchTitles\SearchTitlesAction;
use Osumi\OsumiFramework\App\Module\Api\SelectResult\SelectResultAction;
use Osumi\OsumiFramework\App\Module\Home\Closed\ClosedAction;
use Osumi\OsumiFramework\App\Module\Home\Index\IndexAction;
use Osumi\OsumiFramework\App\Module\Home\NotFound\NotFoundAction;

use Osumi\OsumiFramework\App\Filter\LoginFilter;
use Osumi\OsumiFramework\App\Service\WebService;

$api_urls = [
  [
    'url' => '/add-cinema',
    'action' => AddCinemaAction::class,
    'filters' => [LoginFilter::class],
    'type' => 'json'
  ],
  [
    'url' => '/delete-cinema',
    'action' => DeleteCinemaAction::class,
    'filters' => [LoginFilter::class],
    'services' => [WebService::class],
    'type' => 'json'
  ],
  [
    'url' => '/edit-cinema',
    'action' => EditCinemaAction::class,
    'filters' => [LoginFilter::class],
    'type' => 'json'
  ],
  [
    'url' => '/get-cinema-movies',
    'action' => GetCinemaMoviesAction::class,
    'filters' => [LoginFilter::class],
    'type' => 'json'
  ],
  [
    'url' => '/get-cinemas',
    'action' => GetCinemasAction::class,
    'filters' => [LoginFilter::class],
    'services' => [WebService::class],
    'type' => 'json'
  ],
  [
    'url' => '/get-movie',
    'action' => GetMovieAction::class,
    'filters' => [LoginFilter::class],
    'type' => 'json'
  ],
  [
    'url' => '/get-movies',
    'action' => GetMoviesAction::class,
    'filters' => [LoginFilter::class],
    'services' => [WebService::class],
    'type' => 'json'
  ],
  [
    'url' => '/login',
    'action' => LoginAction::class,
    'type' => 'json'
  ],
  [
    'url' => '/register',
    'action' => RegisterAction::class,
    'type' => 'json'
  ],
  [
    'url' => '/save-movie',
    'action' => SaveMovieAction::class,
    'filters' => [LoginFilter::class],
    'services' => [WebService::class],
    'type' => 'json'
  ],
  [
    'url' => '/search-movie',
    'action' => SearchMovieAction::class,
    'filters' => [LoginFilter::class],
    'services' => [WebService::class],
    'type' => 'json'
  ],
  [
    'url' => '/search-titles',
    'action' => SearchTitlesAction::class,
    'filters' => [LoginFilter::class],
    'services' => [WebService::class],
    'type' => 'json'
  ],
  [
    'url' => '/select-result',
    'action' => SelectResultAction::class,
    'filters' => [LoginFilter::class],
    'services' => [WebService::class],
    'type' => 'json'
  ],
];

$home_urls = [
  [
    'url' => '/closed',
    'action' => ClosedAction::class
  ],
  [
    'url' => '/',
    'action' => IndexAction::class
  ],
  [
    'url' => '/notFound',
    'action' => NotFoundAction::class
  ],
];

$urls = [];
OUrl::addUrls($urls, $api_urls, '/api');
OUrl::addUrls($urls, $home_urls);

return $urls;

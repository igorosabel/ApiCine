<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\Routes;

use Osumi\OsumiFramework\Routing\ORoute;
use Osumi\OsumiFramework\App\Module\Api\AddCinema\AddCinemaComponent;
use Osumi\OsumiFramework\App\Module\Api\DeleteCinema\DeleteCinemaComponent;
use Osumi\OsumiFramework\App\Module\Api\EditCinema\EditCinemaComponent;
use Osumi\OsumiFramework\App\Module\Api\GetCinemaMovies\GetCinemaMoviesComponent;
use Osumi\OsumiFramework\App\Module\Api\GetCinemas\GetCinemasComponent;
use Osumi\OsumiFramework\App\Module\Api\GetCompanionMovies\GetCompanionMoviesComponent;
use Osumi\OsumiFramework\App\Module\Api\GetCompanions\GetCompanionsComponent;
use Osumi\OsumiFramework\App\Module\Api\GetMovie\GetMovieComponent;
use Osumi\OsumiFramework\App\Module\Api\GetMovies\GetMoviesComponent;
use Osumi\OsumiFramework\App\Module\Api\Login\LoginComponent;
use Osumi\OsumiFramework\App\Module\Api\Register\RegisterComponent;
use Osumi\OsumiFramework\App\Module\Api\SaveCompanion\SaveCompanionComponent;
use Osumi\OsumiFramework\App\Module\Api\SaveMovie\SaveMovieComponent;
use Osumi\OsumiFramework\App\Module\Api\SearchMovie\SearchMovieComponent;
use Osumi\OsumiFramework\App\Module\Api\SearchTitles\SearchTitlesComponent;
use Osumi\OsumiFramework\App\Module\Api\SelectResult\SelectResultComponent;
use Osumi\OsumiFramework\App\Filter\LoginFilter;

ORoute::prefix('/api', function() {
  ORoute::post('/add-cinema',           AddCinemaComponent::class,          [LoginFilter::class]);
  ORoute::post('/delete-cinema',        DeleteCinemaComponent::class,       [LoginFilter::class]);
  ORoute::post('/edit-cinema',          EditCinemaComponent::class,         [LoginFilter::class]);
  ORoute::post('/get-cinema-movies',    GetCinemaMoviesComponent::class,    [LoginFilter::class]);
  ORoute::post('/get-cinemas',          GetCinemasComponent::class,         [LoginFilter::class]);
  ORoute::post('/get-companion-movies', GetCompanionMoviesComponent::class, [LoginFilter::class]);
  ORoute::post('/get-companions',       GetCompanionsComponent::class,      [LoginFilter::class]);
  ORoute::post('/get-movie',            GetMovieComponent::class,           [LoginFilter::class]);
  ORoute::post('/get-movies',           GetMoviesComponent::class,          [LoginFilter::class]);
  ORoute::post('/login',                LoginComponent::class);
  ORoute::post('/register',             RegisterComponent::class);
  ORoute::post('/save-companion',       SaveCompanionComponent::class,      [LoginFilter::class]);
  ORoute::post('/save-movie',           SaveMovieComponent::class,          [LoginFilter::class]);
  ORoute::post('/search-movie',         SearchMovieComponent::class,        [LoginFilter::class]);
  ORoute::post('/search-titles',        SearchTitlesComponent::class,       [LoginFilter::class]);
  ORoute::post('/select-result',        SelectResultComponent::class,       [LoginFilter::class]);
});

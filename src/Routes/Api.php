<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\Routes;

use Osumi\OsumiFramework\Routing\ORoute;
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
use Osumi\OsumiFramework\App\Filter\LoginFilter;

ORoute::group('/api', 'json', function() {
  ORoute::post('/add-cinema',        AddCinemaAction::class,       [LoginFilter::class]);
  ORoute::post('/delete-cinema',     DeleteCinemaAction::class,    [LoginFilter::class]);
  ORoute::post('/edit-cinema',       EditCinemaAction::class,      [LoginFilter::class]);
  ORoute::post('/get-cinema-movies', GetCinemaMoviesAction::class, [LoginFilter::class]);
  ORoute::post('/get-cinemas',       GetCinemasAction::class,      [LoginFilter::class]);
  ORoute::post('/get-movie',         GetMovieAction::class,        [LoginFilter::class]);
  ORoute::post('/get-movies',        GetMoviesAction::class,       [LoginFilter::class]);
  ORoute::post('/login',             LoginAction::class);
  ORoute::post('/register',          RegisterAction::class);
  ORoute::post('/save-movie',        SaveMovieAction::class,       [LoginFilter::class]);
  ORoute::post('/search-movie',      SearchMovieAction::class,     [LoginFilter::class]);
  ORoute::post('/search-titles',     SearchTitlesAction::class,    [LoginFilter::class]);
  ORoute::post('/select-result',     SelectResultAction::class,    [LoginFilter::class]);
});

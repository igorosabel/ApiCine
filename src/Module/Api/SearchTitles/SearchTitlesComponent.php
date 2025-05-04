<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\Api\SearchTitles;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Service\MovieService;
use Osumi\OsumiFramework\App\Component\Model\MovieList\MovieListComponent;

class SearchTitlesComponent extends OComponent {
	private ?MovieService $ms = null;

	public string $status = 'ok';
	public int $num_pages = 0;
	public ?MovieListComponent $list = null;

	public function __construct() {
		parent::__construct();
		$this->ms = inject(MovieService::class);
		$this->list = new MovieListComponent();
	}

	/**
	 * Función para buscar películas entre las que el usuario ha visto
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req): void {
		$q      = $req->getParamString('q');
		$filter = $req->getFilter('Login');

		if (is_null($q) || is_null($filter) || !array_key_exists('id', $filter)) {
			$this->status = 'error';
		}

		if ($this->status === 'ok') {
			$this->num_pages  = $this->ms->getMoviesPagesByTitle($filter['id'], $q);
			$this->list->list = $this->ms->getMoviesByTitle($filter['id'], $q);
		}
	}
}

<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\Api\GetMovies;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Service\WebService;
use Osumi\OsumiFramework\App\Component\Api\Movies\MoviesComponent;

class GetMoviesComponent extends OComponent {
	private ?WebService $ws = null;

	public string $status    = 'ok';
	public float  $num_pages = 0;
	public ?MoviesComponent $list = null;

	public function __construct() {
		parent::__construct();
		$this->ws = inject(WebService::class);
		$this->list = new MoviesComponent();
	}

	/**
	 * Función para obtener la lista de las últimas películas
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req): void {
		$page   = $req->getParamInt('page');
		$filter = $req->getFilter('Login');

		if (is_null($page) || is_null($filter) || !array_key_exists('id', $filter)) {
			$this->status = 'error';
		}

		if ($this->status === 'ok') {
			$this->num_pages = $this->ws->getMoviesPages($filter['id']);
			$this->list->list = $this->ws->getMovies($filter['id'], $page);
		}
	}
}

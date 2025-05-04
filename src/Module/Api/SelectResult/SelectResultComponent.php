<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\Api\SelectResult;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Service\TMDBService;

class SelectResultComponent extends OComponent {
	private ?TMDBService $ts = null;

	public string $status   = 'ok';
	public string $title    = '';
	public string $poster   = '';
	public string $imdb_url = '';

	public function __construct() {
		parent::__construct();
		$this->ts = inject(TMDBService::class);
	}

	/**
	 * Función para obtener el detalle de una película en The Movie Data Base
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req): void {
		$id     = $req->getParamInt('id');
		$filter = $req->getFilter('Login');

		if (is_null($id) || is_null($filter) || !array_key_exists('id', $filter)) {
			$this->status = 'error';
		}

		if ($this->status === 'ok') {
			$detail = $this->ts->tmdbDetail($id);

			$this->title    = $detail['title'];
			$this->poster   = $detail['poster'];
			$this->imdb_url = $detail['imdb_url'];
		}
	}
}

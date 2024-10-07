<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\Api\SelectResult;

use Osumi\OsumiFramework\Routing\OAction;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Service\WebService;

class SelectResultAction extends OAction {
	private ?WebService $ws = null;

	public string $status   = 'ok';
	public string $title    = '';
	public string $poster   = '';
	public string $imdb_url = '';

	public function __construct() {
		$this->ws = inject(WebService::class);
	}

	/**
	 * Función para obtener el detalle de una película en The Movie Data Base
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$id     = $req->getParamInt('id');
		$filter = $req->getFilter('Login');

		if (is_null($id) || is_null($filter) || !array_key_exists('id', $filter)) {
			$this->status = 'error';
		}

		if ($this->status === 'ok') {
			$detail = $this->ws->tmdbDetail($id);

			$this->title    = $detail['title'];
			$this->poster   = $detail['poster'];
			$this->imdb_url = $detail['imdb_url'];
		}
	}
}

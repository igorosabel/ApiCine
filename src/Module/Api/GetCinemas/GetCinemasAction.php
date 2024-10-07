<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\Api\GetCinemas;

use Osumi\OsumiFramework\Routing\OAction;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Service\WebService;
use Osumi\OsumiFramework\App\Component\Api\Cinemas\CinemasComponent;

class GetCinemasAction extends OAction {
	private ?WebService $ws = null;

	public string $status = 'ok';
	public ?CinemasComponent $list = null;

	public function __construct() {
		$this->ws = inject(WebService::class);
		$this->list = new CinemasComponent(['list' => []]);
	}

	/**
	 * Función para obtener la lista de cines
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$filter = $req->getFilter('Login');

		if (is_null($filter) || !array_key_exists('id', $filter)) {
			$this->status = 'error';
		}

		if ($this->status === 'ok') {
			$this->list->setValue('list', $this->ws->getCinemas($filter['id']));
		}
	}
}

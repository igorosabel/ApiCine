<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\Api\GetCinemas;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Service\CinemaService;
use Osumi\OsumiFramework\App\Component\Model\CinemaList\CinemaListComponent;

class GetCinemasComponent extends OComponent {
	private ?CinemaService $cs = null;

	public string $status = 'ok';
	public ?CinemaListComponent $list = null;

	public function __construct() {
		parent::__construct();
		$this->cs   = inject(CinemaService::class);
		$this->list = new CinemaListComponent();
	}

	/**
	 * Función para obtener la lista de cines
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req): void {
		$filter = $req->getFilter('Login');

		if (is_null($filter) || !array_key_exists('id', $filter)) {
			$this->status = 'error';
		}

		if ($this->status === 'ok') {
			$this->list->list = $this->cs->getCinemas($filter['id']);
		}
	}
}

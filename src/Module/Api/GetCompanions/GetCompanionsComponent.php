<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\Api\GetCompanions;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Service\CompanionService;
use Osumi\OsumiFramework\App\Component\Model\CompanionList\CompanionListComponent;

class GetCompanionsComponent extends OComponent {
	private ?CompanionService $cs = null;

	public string $status = 'ok';
	public ?CompanionListComponent $list = null;

	public function __construct() {
		parent::__construct();
		$this->cs   = inject(CompanionService::class);
		$this->list = new CompanionListComponent();
	}

	/**
	 * Función para obtener la lista de acompañantes de un usuario
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
			$this->list->list = $this->cs->getCompanions($filter['id']);
		}
	}
}

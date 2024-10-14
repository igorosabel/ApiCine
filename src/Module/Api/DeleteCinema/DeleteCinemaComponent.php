<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\Api\DeleteCinema;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Service\WebService;
use Osumi\OsumiFramework\App\Model\Cinema;

class DeleteCinemaComponent extends OComponent {
	private ?WebService $ws = null;

	public string $status = 'ok';

	public function __construct() {
		parent::__construct();
		$this->ws = inject(WebService::class);
	}

	/**
	 * Función para borrar un cine
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
			$cinema = new Cinema();
			if ($cinema->find(['id' => $id])) {
				if ($cinema->get('id_user') === $filter['id']) {
					$this->ws->deleteCinema($cinema);
				}
				else {
					$this->status = 'error';
				}
			}
			else {
				$this->status = 'error';
			}
		}
	}
}

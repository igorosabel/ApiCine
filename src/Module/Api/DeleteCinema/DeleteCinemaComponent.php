<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\Api\DeleteCinema;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Service\CinemaService;
use Osumi\OsumiFramework\App\Model\Cinema;

class DeleteCinemaComponent extends OComponent {
	private ?CinemaService $cs = null;

	public string $status = 'ok';

	public function __construct() {
		parent::__construct();
		$this->cs = inject(CinemaService::class);
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
			$cinema = Cinema::findOne(['id' => $id]);
			if (!is_null($cinema)) {
				if ($cinema->id_user === $filter['id']) {
					$this->cs->deleteCinema($cinema);
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

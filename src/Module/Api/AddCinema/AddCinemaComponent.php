<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\Api\AddCinema;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\Tools\OTools;
use Osumi\OsumiFramework\App\Model\Cinema;

class AddCinemaComponent extends OComponent {
	public string $status = 'ok';

	/**
	 * Función para añadir un nuevo cine
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req): void {
		$name   = $req->getParamString('name');
		$filter = $req->getFilter('Login');

		if (is_null($name) || is_null($filter) || !array_key_exists('id', $filter)) {
			$this->status = 'error';
		}

		if ($this->status === 'ok') {
			$cinema = new Cinema();
			$cinema->id_user = $filter['id'];
			$cinema->name = $name;
			$cinema->slug = OTools::slugify($name);

			$cinema->save();
		}
	}
}

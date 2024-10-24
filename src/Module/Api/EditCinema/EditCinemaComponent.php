<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\Api\EditCinema;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Model\Cinema;

class EditCinemaComponent extends OComponent {
	public string $status = 'ok';

	/**
	 * Función para editar el nombre de un cine
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req): void {
		$id     = $req->getParamInt('id');
		$name   = $req->getParamString('name');
		$filter = $req->getFilter('Login');

		if (is_null($id) || is_null($name) || is_null($filter) || !array_key_exists('id', $filter)) {
			$this->status = 'error';
		}

		if ($this->status === 'ok') {
			$cinema = Cinema::findOne(['id' => $id]);
			if (!is_null($cinema)) {
				if ($cinema->id_user === $filter['id']) {
					$cinema->name = $name;
					$cinema->save();
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

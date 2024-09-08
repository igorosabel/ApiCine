<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\ApiModule\Actions\AddCinema;

use Osumi\OsumiFramework\Routing\OModuleAction;
use Osumi\OsumiFramework\Routing\OAction;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\Tools\OTools;
use Osumi\OsumiFramework\App\Model\Cinema;

#[OModuleAction(
	url: '/add-cinema',
	filters: ['Login']
)]
class AddCinemaAction extends OAction {
	public string $status = 'ok';

	/**
	 * Función para añadir un nuevo cine
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$name   = $req->getParamString('name');
		$filter = $req->getFilter('Login');

		if (is_null($name) || is_null($filter) || !array_key_exists('id', $filter)) {
			$this->status = 'error';
		}

		if ($this->status=='ok') {
			$cinema = new Cinema();
			$cinema->set('id_user', $filter['id']);
			$cinema->set('name', $name);
			$cinema->set('slug', OTools::slugify($name));

			$cinema->save();
		}
	}
}

<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\OFW\Tools\OTools;
use OsumiFramework\App\Model\Cinema;

#[OModuleAction(
	url: '/add-cinema',
	filters: ['login']
)]
class addCinemaAction extends OAction {
	/**
	 * Función para añadir un nuevo cine
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$status = 'ok';
		$name   = $req->getParamString('name');
		$filter = $req->getFilter('login');

		if (is_null($name) || is_null($filter) || !array_key_exists('id', $filter)) {
			$status = 'error';
		}

		if ($status=='ok') {
			$cinema = new Cinema();
			$cinema->set('id_user', $filter['id']);
			$cinema->set('name', $name);
			$cinema->set('slug', OTools::slugify($name));

			$cinema->save();
		}

		$this->getTemplate()->add('status', $status);
	}
}

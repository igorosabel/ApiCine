<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\App\Component\CinemasComponent;

#[OModuleAction(
	url: '/get-cinemas',
	filter: 'login',
	services: 'web',
	components: 'api/cinemas'
)]
class getCinemasAction extends OAction {
	/**
	 * Función para obtener la lista de cines
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$status = 'ok';
		$filter = $req->getFilter('login');
		$cinemas_component = new CinemasComponent(['list'=>[], 'extra'=>'nourlencode']);

		if (is_null($filter) || !array_key_exists('id', $filter)) {
			$status = 'error';
		}
		$list = [];

		if ($status=='ok') {
			$list = $this->web_service->getCinemas($filter['id']);
			$cinemas_component = new CinemasComponent(['list'=>$list, 'extra'=>'nourlencode']);
		}

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->add('list',   $cinemas_component);
	}
}

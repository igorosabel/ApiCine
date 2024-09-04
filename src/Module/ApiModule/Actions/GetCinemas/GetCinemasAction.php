<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\ApiModule\Actions\GetCinemas;

use Osumi\OsumiFramework\Routing\OModuleAction;
use Osumi\OsumiFramework\Routing\OAction;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Component\Api\CinemasComponent\CinemasComponent;

#[OModuleAction(
	url: '/get-cinemas',
	filters: ['Login'],
	services: ['Web']
)]
class GetCinemasAction extends OAction {
	/**
	 * Función para obtener la lista de cines
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$status = 'ok';
		$filter = $req->getFilter('Login');
		$cinemas_component = new CinemasComponent(['list' => []]);

		if (is_null($filter) || !array_key_exists('id', $filter)) {
			$status = 'error';
		}
		$list = [];

		if ($status=='ok') {
			$list = $this->service['Web']->getCinemas($filter['id']);
			$cinemas_component->setValue('list', $list);
		}

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->add('list',   $cinemas_component);
	}
}

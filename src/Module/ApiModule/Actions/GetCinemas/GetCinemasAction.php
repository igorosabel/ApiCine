<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\ApiModule\Actions\GetCinemas;

use Osumi\OsumiFramework\Routing\OModuleAction;
use Osumi\OsumiFramework\Routing\OAction;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Component\Api\Cinemas\CinemasComponent;

#[OModuleAction(
	url: '/get-cinemas',
	filters: ['Login'],
	services: ['Web']
)]
class GetCinemasAction extends OAction {
	public string $status = 'ok';
	public ?CinemasComponent $list = null;

	/**
	 * Función para obtener la lista de cines
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$filter = $req->getFilter('Login');
		$this->list = new CinemasComponent(['list' => []]);

		if (is_null($filter) || !array_key_exists('id', $filter)) {
			$this->status = 'error';
		}

		if ($this->status=='ok') {
			$this->list->setValue('list', $this->service['Web']->getCinemas($filter['id']));
		}
	}
}

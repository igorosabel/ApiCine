<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\HomeModule\Actions\Index;

use Osumi\OsumiFramework\Routing\OModuleAction;
use Osumi\OsumiFramework\Routing\OAction;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\Routing\OUrl;

#[OModuleAction(
	url: '/index'
)]
class IndexAction extends OAction {
	/**
	 * Home pública
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		OUrl::goToUrl('https://cine.osumi.es');
	}
}

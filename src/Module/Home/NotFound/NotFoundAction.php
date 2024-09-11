<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\Home\NotFound;

use Osumi\OsumiFramework\Routing\OAction;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\Routing\OUrl;

class NotFoundAction extends OAction {
	/**
	 * Página de error 404
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		OUrl::goToUrl('https://cine.osumi.es');
	}
}

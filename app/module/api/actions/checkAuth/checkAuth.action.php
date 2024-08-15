<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\OFW\Plugins\OToken;

#[OModuleAction(
	url: '/check-auth'
)]
class checkAuthAction extends OAction {
	/**
	 * Función para comprobar la validez de un token de seguridad
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$status = 'error';
		$id = -1;
		$tk = new OToken($this->getConfig()->getExtra('secret'));

		$token = $_COOKIE['auth_token'] ?? null;

		if ($token && $tk->checkToken($token)) {
			$status = 'ok';
			$id = intval($tk->getParam('id'));
		}

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->add('id',     $id);
	}
}

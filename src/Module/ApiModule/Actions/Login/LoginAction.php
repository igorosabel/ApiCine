<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\ApiModule\Actions\Login;

use Osumi\OsumiFramework\Routing\OModuleAction;
use Osumi\OsumiFramework\Routing\OAction;
use Osumi\OsumiFramework\App\DTO\UserDTO;
use Osumi\OsumiFramework\App\Model\User;
use Osumi\OsumiFramework\Plugins\OToken;

#[OModuleAction(
	url: '/login'
)]
class LoginAction extends OAction {
	/**
	 * Función para iniciar sesión en la aplicación
	 *
	 * @param UserDTO $data Nombre y contraseña del usuario
	 * @return void
	 */
	public function run(UserDTO $data):void {
		$status = 'ok';
		$id    = 'null';
		$token = '';

		if (!$data->isValid()) {
			$status = 'error';
		}

		if ($status=='ok') {
			$u = new User();
			if ($u->find(['name'=>$data->getName()])) {
				if (password_verify($data->getPass(), $u->get('pass'))) {
					$id = $u->get('id');

					$tk = new OToken($this->getConfig()->getExtra('secret'));
					$tk->addParam('id',   $id);
					$tk->addParam('name', $data->getName());
					$tk->addParam('exp', time() + (24 * 60 * 60));
					$token = $tk->getToken();
				}
				else {
					$status = 'error';
				}
			}
			else {
				$status = 'error';
			}
		}

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->add('id',     $id);
		$this->getTemplate()->add('name',   $data->getName());
		$this->getTemplate()->add('token',  $token);
	}
}

<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\App\DTO\UserDTO;
use OsumiFramework\App\Model\User;
use OsumiFramework\OFW\Plugins\OToken;

#[OModuleAction(
	url: '/register'
)]
class registerAction extends OAction {
	/**
	 * Función para registrarse en la aplicación
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
				$status = 'error-user';
			}
			else {
				$u->set('name', $data->getName());
				$u->set('pass', password_hash($data->getPass(), PASSWORD_BCRYPT));
				$u->save();

				$id = $u->get('id');

				$tk = new OToken($this->getConfig()->getExtra('secret'));
				$tk->addParam('id',   $id);
				$tk->addParam('name', $data->getName());
				$tk->addParam('exp', time() + (24 * 60 * 60));
				$token = $tk->getToken();
			}
		}

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->add('id',     $id);
		$this->getTemplate()->add('name',   $data->getName());
		$this->getTemplate()->add('token',  $token);
	}
}

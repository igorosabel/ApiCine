<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\App\DTO\UserDTO;
use OsumiFramework\App\Model\User;
use OsumiFramework\OFW\Plugins\OToken;

#[OModuleAction(
	url: '/login'
)]
class loginAction extends OAction {
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

					$cookie_options = [
					    'expires' => time() + (24 * 60 * 60),
					    'path' => '/',
					    'domain' => '.osumi.es',
					    'secure' => true,
					    'httponly' => true,
					    'samesite' => 'Strict'
					];

					if (setcookie('auth_token', $token, $cookie_options)) {
						//echo "COOKIE IS SET\n";
					}
					else {
						echo "COOKIE NOT SET\n";
					}
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
	}
}

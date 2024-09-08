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
	public string       $status = 'ok';
	public string | int $id     = 'null';
	public string       $name   = '';
	public string       $token  = '';

	/**
	 * Función para iniciar sesión en la aplicación
	 *
	 * @param UserDTO $data Nombre y contraseña del usuario
	 * @return void
	 */
	public function run(UserDTO $data):void {
		if (!$data->isValid()) {
			$this->status = 'error';
		}

		if ($this->status=='ok') {
			$u = new User();
			if ($u->find(['name'=>$data->getName()])) {
				if (password_verify($data->getPass(), $u->get('pass'))) {
					$this->id = $u->get('id');
					$this->name = $data->getName();

					$tk = new OToken($this->getConfig()->getExtra('secret'));
					$tk->addParam('id',   $this->id);
					$tk->addParam('name', $this->name);
					$tk->addParam('exp', time() + (24 * 60 * 60));
					$this->token = $tk->getToken();
				}
				else {
					$this->status = 'error';
				}
			}
			else {
				$this->status = 'error';
			}
		}
	}
}

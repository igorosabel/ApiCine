<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\Api\Login;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\App\DTO\UserDTO;
use Osumi\OsumiFramework\App\Model\User;
use Osumi\OsumiFramework\Plugins\OToken;

class LoginComponent extends OComponent {
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
	public function run(UserDTO $data): void {
		if (!$data->isValid()) {
			$this->status = 'error';
		}

		if ($this->status === 'ok') {
			$u = User::findOne(['name' => $data->name]);
			if (!is_null($u)) {
				if (password_verify($data->pass, $u->pass)) {
					$this->id   = $u->id;
					$this->name = $data->name;

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

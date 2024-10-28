<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\DTO;

use Osumi\OsumiFramework\Core\ODTO;
use Osumi\OsumiFramework\Web\ORequest;

class UserDTO implements ODTO{
	public string $name = '';
	public string $pass = '';

	public function isValid(): bool {
		return ($this->name !== '' && $this->pass !== '');
	}

	public function load(ORequest $req): void {
		$name = $req->getParamString('name');
		if (!is_null($name)) {
			$this->name = $name;
		}
		$pass = $req->getParamString('pass');
		if (!is_null($pass)) {
			$this->pass = $pass;
		}
	}
}

<?php declare(strict_types=1);

namespace OsumiFramework\App\DTO;

use OsumiFramework\OFW\Core\ODTO;
use OsumiFramework\OFW\Web\ORequest;

class UserDTO implements ODTO{
	private string $name = '';
	private string $pass = '';

	public function getName(): string {
		return $this->name;
	}
	private function setName(string $name): void {
		$this->name = $name;
	}
	public function getPass(): string {
		return $this->pass;
	}
	private function setPass(string $pass): void {
		$this->pass = $pass;
	}

	public function isValid(): bool {
		return ($this->getName() != '' && $this->getPass() != '');
	}

	public function load(ORequest $req): void {
		$name = $req->getParamString('name');
		if (!is_null($name)) {
			$this->setName($name);
		}
		$pass = $req->getParamString('pass');
		if (!is_null($pass)) {
			$this->setPass($pass);
		}
	}
}
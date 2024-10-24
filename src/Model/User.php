<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Model;

use Osumi\OsumiFramework\ORM\OModel;
use Osumi\OsumiFramework\ORM\OPK;
use Osumi\OsumiFramework\ORM\OField;
use Osumi\OsumiFramework\ORM\OCreatedAt;
use Osumi\OsumiFramework\ORM\OUpdatedAt;

class User extends OModel {
	#[OPK(
		comment: 'Id único de cada usuario'
	)]
	public ?int $id;

	#[OField(
		comment: 'Nombre de usuario',
		max: 100,
		nullable: false
	)]
	public ?string $name;

	#[OField(
		comment: 'Contraseña cifrada del usuario',
		max: 250,
		nullable: false
	)]
	public ?string $pass;

	#[OCreatedAt(
		comment: 'Fecha de creación del registro'
	)]
	public ?string $created_at;

	#[OUpdatedAt(
		comment: 'Fecha de última modificación del registro'
	)]
	public ?string $updated_at;

	/**
	 * Devuelve el nombre del usuario
	 */
	public function __toString() {
		return $this->name;
	}
}

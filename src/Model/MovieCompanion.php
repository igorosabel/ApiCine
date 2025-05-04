<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Model;

use Osumi\OsumiFramework\ORM\OModel;
use Osumi\OsumiFramework\ORM\OPK;
use Osumi\OsumiFramework\ORM\OField;
use Osumi\OsumiFramework\ORM\OCreatedAt;
use Osumi\OsumiFramework\ORM\OUpdatedAt;

class MovieCompanion extends OModel{
	#[OPK(
	  comment: 'Id de la película',
		ref: 'movie.id'
	)]
	public ?int $id_movie;

	#[OPK(
	  comment: 'Id del acompañante',
		ref: 'companion.id'
	)]
	public ?int $id_companion;

	#[OCreatedAt(
	  comment: 'Fecha de creación del registro'
	)]
	public ?string $created_at;

	#[OUpdatedAt(
	  comment: 'Fecha de última modificación del registro'
	)]
	public ?string $updated_at;
}

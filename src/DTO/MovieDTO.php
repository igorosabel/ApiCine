<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\DTO;

use Osumi\OsumiFramework\DTO\ODTO;
use Osumi\OsumiFramework\DTO\ODTOField;

class MovieDTO extends ODTO{
	#[ODTOField(required: true)]
	public ?int $idCinema = null;

	#[ODTOField(required: true)]
	public ?string $name = null;

	#[ODTOField(required: true)]
	public ?string $cover = null;

	#[ODTOField(required: true)]
	public ?int $coverStatus = null;

	#[ODTOField(required: true)]
	public ?string $ticket = null;

	#[ODTOField(required: true)]
	public ?string $imdbUrl = null;

	#[ODTOField(required: true)]
	public ?string $date = null;

	#[ODTOField(required: true, filter: 'Login', filterProperty: 'id')]
	public ?array $idUser = null;
}

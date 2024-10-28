<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\DTO;

use Osumi\OsumiFramework\Core\ODTO;
use Osumi\OsumiFramework\Web\ORequest;

class MovieDTO implements ODTO{
	public ?int    $id_cinema    = null;
	public ?string $name         = null;
	public ?string $cover        = null;
	public ?int    $cover_status = null;
	public ?string $ticket       = null;
	public ?string $imdb_url     = null;
	public ?string $date         = null;
	public ?array  $filter       = null;

	public function isValid(): bool {
		if (
			is_null($this->id_cinema) ||
			is_null($this->name) ||
			is_null($this->cover) ||
			is_null($this->cover_status) ||
			is_null($this->ticket) ||
			is_null($this->imdb_url) ||
			is_null($this->date) ||
			is_null($this->filter) ||
			!array_key_exists('id', $this->filter)
		) {
			return false;
		}
		return true;
	}

	public function load(ORequest $req): void {
		$this->id_cinema = $req->getParamInt('idCinema');
		$this->name = urldecode($req->getParamString('name'));
		$this->cover = urldecode($req->getParamString('cover'));
		$this->cover_status = $req->getParamInt('coverStatus');
		$this->ticket = urldecode($req->getParam('ticket'));
		$this->imdb_url = urldecode($req->getParamString('imdbUrl'));
		$this->date = urldecode($req->getParamString('date'));
		$this->filter = $req->getFilter('Login');
	}
}

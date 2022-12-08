<?php declare(strict_types=1);

namespace OsumiFramework\App\DTO;

use OsumiFramework\OFW\Core\ODTO;
use OsumiFramework\OFW\Web\ORequest;

class MovieDTO implements ODTO{
	private ?int    $id_cinema    = null;
	private ?string $name         = null;
	private ?string $cover        = null;
	private ?int    $cover_status = null;
	private ?string $ticket       = null;
	private ?string $imdb_url     = null;
	private ?string $date         = null;
	private ?array  $filter       = null;

	public function getIdCinema(): ?int {
		return $this->id_cinema;
	}
	private function setIdCinema(?int $id_cinema): void {
		$this->id_cinema = $id_cinema;
	}
	public function getName(): ?string {
		return $this->name;
	}
	private function setName(?string $name): void {
		$this->name = $name;
	}
	public function getCover(): ?string {
		return $this->cover;
	}
	private function setCover(?string $cover): void {
		$this->cover = $cover;
	}
	public function getCoverStatus(): ?int {
		return $this->cover_status;
	}
	private function setCoverStatus(?int $cover_status): void {
		$this->cover_status = $cover_status;
	}
	public function getTicket(): ?string {
		return $this->ticket;
	}
	private function setTicket(?string $ticket): void {
		$this->ticket = $ticket;
	}
	public function getImdbUrl(): ?string {
		return $this->imdb_url;
	}
	private function setImdbUrl(?string $imdb_url): void {
		$this->imdb_url = $imdb_url;
	}
	public function getDate(): ?string {
		return $this->date;
	}
	private function setDate(?string $date): void {
		$this->date = $date;
	}
	public function getFilter(): ?array {
		return $this->filter;
	}
	private function setFilter(?array $filter): void {
		$this->filter = $filter;
	}

	public function isValid(): bool {
		if (
			is_null($this->getIdCinema()) ||
			is_null($this->getName()) ||
			is_null($this->getCover()) ||
			is_null($this->getCoverStatus()) ||
			is_null($this->getTicket()) ||
			is_null($this->getImdbUrl()) ||
			is_null($this->getDate()) ||
			is_null($this->getFilter()) ||
			!array_key_exists('id', $this->getFilter())
		) {
			return false;
		}
		return true;
	}

	public function load(ORequest $req): void {
		$this->setIdCinema($req->getParamInt('idCinema'));
		$this->setName(urldecode($req->getParamString('name')));
		$this->setCover(urldecode($req->getParamString('cover')));
		$this->setCoverStatus($req->getParamInt('coverStatus'));
		$this->setTicket(urldecode($req->getParam('ticket')));
		$this->setImdbUrl(urldecode($req->getParamString('imdbUrl')));
		$this->setDate(urldecode($req->getParamString('date')));
		$this->setFilter($req->getFilter('login'));
	}
}

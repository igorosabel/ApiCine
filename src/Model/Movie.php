<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Model;

use Osumi\OsumiFramework\ORM\OModel;
use Osumi\OsumiFramework\ORM\OPK;
use Osumi\OsumiFramework\ORM\OField;
use Osumi\OsumiFramework\ORM\OCreatedAt;
use Osumi\OsumiFramework\ORM\OUpdatedAt;
use Osumi\OsumiFramework\ORM\ODB;

class Movie extends OModel {
	#[OPK(
		comment: 'Id único de cada película'
	)]
	public ?int $id;

	#[OField(
		comment: 'Id del usuario que añade la película',
		nullable: false,
		ref: 'user.id'
	)]
	public ?int $id_user;

	#[OField(
		comment: 'Id del cine en el que un usuario ha visto la película',
		nullable: false,
		ref: 'cinema.id'
	)]
	public ?int $id_cinema;

	#[OField(
		comment: 'Nombre de la película',
		max: 50,
		nullable: false
	)]
	public ?string $name;

	#[OField(
		comment: 'Slug del nombre de la película',
		max: 50,
		nullable: false
	)]
	public ?string $slug;

	#[OField(
		comment: 'Url de la película en IMDB',
		max: 200,
		nullable: false
	)]
	public ?string $imdb_url;

	#[OField(
		comment: 'Fecha en la que un usuario fue a ver la película',
		type: OField::DATE,
		nullable: false
	)]
	public ?string $movie_date;

	#[OCreatedAt(
		comment: 'Fecha de creación del registro'
	)]
	public ?string $created_at;

	#[OUpdatedAt(
		comment: 'Fecha de última modificación del registro'
	)]
	public ?string $updated_at;

	/**
	 * Devuelve el nombre de la película
	 */
	public function __toString() {
		return $this->name;
	}

	/**
	 * Devuelve la URL de la carátula de la película
	 *
	 * @return string URL de la carátula de la película
	 */
	public function getCoverUrl(): string {
		global $core;
		return $core->config->getUrl('base').'cover/'.$this->id.'.webp';
	}

	/**
	 * Devuelve la URL del ticket de la película
	 *
	 * @return string URL del ticket de la película
	 */
	public function getTicketUrl(): string {
		global $core;
		return $core->config->getUrl('base').'ticket/'.$this->id.'.webp';
	}

	/**
	 * Lista de acompañantes
	 */
	private ?array $companions = null;

	/**
	 * Método para obtener la lista de acompañantes
	 *
	 * @return array Lista de acompañantes
	 */
	public function getCompanions(): array {
		if (is_null($this->companions)) {
			$this->loadCompanions();
		}
		return $this->companions;
	}

	/**
	 * Método para guardar la lista de acompañantes
	 *
	 * @param array $c Lista de acompañantes
	 *
	 * @return void
	 */
	private function setCompanions(array $c): void {
		$this->companions = $c;
	}

	/**
	 * Método para cargar la lista de acompañantes
	 *
	 * @return void
	 */
	private function loadCompanions(): void {
		$db = new ODB();
		$sql = "SELECT * FROM `companion` WHERE `id` IN (SELECT `id_companion` FROM `movie_companion` WHERE `id_movie` = ?) ORDER BY `name`";
		$db->query($sql, [$this->id]);
		$list = [];

		while ($res = $db->next()) {
			$list[] = Companion::from($res);
		}
		$this->setCompanions($list);
	}

	/**
	 * Borra una película de la base de datos, su carátula y su ticket
	 *
	 * @return void
	 */
	public function deleteFull(): void {
		global $core;
		$cover_route  = $core->config->getDir('web').'cover/'.$this->id.'.webp';
		$ticket_route = $core->config->getDir('web').'ticket/'.$this->id.'.webp';

		if (file_exists($cover_route)) {
			unlink($cover_route);
		}
		if (file_exists($ticket_route)) {
			unlink($ticket_route);
		}

		$this->delete();
	}
}

<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Model;

use Osumi\OsumiFramework\ORM\OModel;
use Osumi\OsumiFramework\ORM\OPK;
use Osumi\OsumiFramework\ORM\OField;
use Osumi\OsumiFramework\ORM\OCreatedAt;
use Osumi\OsumiFramework\ORM\OUpdatedAt;
use Osumi\OsumiFramework\ORM\ODB;

class Cinema extends OModel {
	#[OPK(
		comment: 'Id único para cada cine'
	)]
	public ?int $id;

	#[OField(
		comment: 'Id del usuario que añade el cine',
		nullable: false,
		ref: 'user.id'
	)]
	public ?int $id_user;

	#[OField(
		comment: 'Nombre del cine',
		max: 50,
		nullable: false
	)]
	public ?string $name;

	#[OField(
		comment: 'Slug del nombre del cine',
		max: 50,
		nullable: false
	)]
	public ?string $slug;

	#[OCreatedAt(
		comment: 'Fecha de creación del registro'
	)]
	public ?string $created_at;

	#[OUpdatedAt(
		comment: 'Fecha de última modificación del registro'
	)]
	public ?string $updated_at;

	/**
	 * Devuelve el nombre del cine
	 */
	public function __toString() {
		return $this->name;
	}

	private ?array $movies = null;

	/**
	 * Devuelve la lista de películas vistas en un cine
	 *
	 * @return array Lista de películas
	 */
	public function getMovies(): array {
		if (is_null($this->movies)){
			$this->loadMovies();
		}
		return $this->movies;
	}

	/**
	 * Guarda la lista de películas de un cine
	 *
	 * @param array $movies Lista de películas
	 *
	 * @return void
	 */
	public function setMovies(array $movies): void {
		$this->movies = $movies;
	}

	/**
	 * Carga la lista de películas de un cine
	 *
	 * @return void
	 */
	public function loadMovies(): void {
		$db = new ODB();
		$sql = "SELECT * FROM `movie` WHERE `id_cinema` = ? ORDER BY `movie_date` DESC";
		$db->query($sql, [$this->get('id')]);
		$list = [];

		while ($res = $db->next()) {
			$movie = new Movie($res);

			$list[] = $movie;
		}

		$this->setMovies($list);
	}
}

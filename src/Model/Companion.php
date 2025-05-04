<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Model;

use Osumi\OsumiFramework\ORM\OModel;
use Osumi\OsumiFramework\ORM\OPK;
use Osumi\OsumiFramework\ORM\OField;
use Osumi\OsumiFramework\ORM\OCreatedAt;
use Osumi\OsumiFramework\ORM\OUpdatedAt;
use Osumi\OsumiFramework\ORM\ODB;

class Companion extends OModel {
	#[OPK(
		comment: 'Id único de cada acompañant'
	)]
	public ?int $id;

	#[OField(
		comment: 'Id del usuario que crea los acompañantes',
		nullable: false,
		ref: 'user.id'
	)]
	public ?int $for_user;

	#[OField(
		comment: 'Id del usuario',
		nullable: true,
		default: null,
		ref: 'user.id'
	)]
	public ?int $id_user;

	#[OField(
		comment: 'Nombre del acompañante',
		max: 100,
		nullable: false
	)]
	public ?string $name;

	#[OCreatedAt(
		comment: 'Fecha de creación del registro'
	)]
	public ?string $created_at;

	#[OUpdatedAt(
		comment: 'Fecha de última modificación del registro'
	)]
	public ?string $updated_at;

	/**
	 * Usuario registrado
	 */
	private ?User $user = null;
	private bool $user_loaded = false;

	/**
	 * Método para obtener el usuario registrado de un acompañante
	 *
	 * @return ?User Devuelve el usuario o null si tiene un usuario registrado
	 */
	public function getUser(): ?User {
		if (is_null($this->user) && !$this->user_loaded) {
			$this->loadUser();
		}
		return $this->user;
	}

	/**
	 * Método para asignar un usuario registrado al acompañante
	 *
	 * @param User $u Usuario registrado
	 *
	 * @return void
	 */
	private function setUser(User $u): void {
		$this->user = $u;
	}

	/**
	 * Método para cargar un usuario registrado al acompañante
	 *
	 * @return void
	 */
	private function loadUser(): void {
		if (!is_null($this->id_user)) {
			$this->setUser( User::findOne(['id' => $this->id_user]) );
		}
		$this->user_loaded = true;
	}

	/**
	 * Listado de películas
	 */
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
		$sql = "SELECT * FROM `movie` WHERE `id` IN (SELECT `id_movie` FROM `movie_companion` WHERE `id_companion` = ?) ORDER BY `movie_date` DESC";
		$db->query($sql, [$this->get('id')]);
		$list = [];

		while ($res = $db->next()) {
			$list[] = Movie::from($res);
		}

		$this->setMovies($list);
	}
}

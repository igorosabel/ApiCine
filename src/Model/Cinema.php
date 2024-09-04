<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Model;

use Osumi\OsumiFramework\DB\OModel;
use Osumi\OsumiFramework\DB\OModelGroup;
use Osumi\OsumiFramework\DB\OModelField;

class Cinema extends OModel {
	/**
	 * Configures current model object based on data-base table structure
	 */
	function __construct() {
		$model = new OModelGroup(
			new OModelField(
				name: 'id',
				type: OMODEL_PK,
				comment: 'Id único para cada cine'
			),
			new OModelField(
				name: 'id_user',
				type: OMODEL_NUM,
				comment: 'Id del usuario que añade el cine',
				nullable: false,
				ref: 'user.id'
			),
			new OModelField(
				name: 'name',
				type: OMODEL_TEXT,
				size: 50,
				comment: 'Nombre del cine',
				nullable: false
			),
			new OModelField(
				name: 'slug',
				type: OMODEL_TEXT,
				size: 50,
				comment: 'Slug del nombre del cine',
				nullable: false
			),
			new OModelField(
				name: 'created_at',
				type: OMODEL_CREATED,
				comment: 'Fecha de creación del registro'
			),
			new OModelField(
				name: 'updated_at',
				type: OMODEL_UPDATED,
				comment: 'Fecha de última modificación del registro'
			)
		);

		parent::load($model);
	}

	/**
	 * Devuelve el nombre del cine
	 */
	public function __toString() {
		return $this->get('name');
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
		$sql = "SELECT * FROM `movie` WHERE `id_cinema` = ? ORDER BY `movie_date` DESC";
		$this->db->query($sql, [$this->get('id')]);
		$list = [];

		while ($res=$this->db->next()) {
			$movie = new Movie();
			$movie->update($res);

			array_push($list, $movie);
		}

		$this->setMovies($list);
	}
}

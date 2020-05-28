<?php declare(strict_types=1);
class Cinema extends OModel {
	/**
	 * Configures current model object based on data-base table structure
	 */
	function __construct() {
		$table_name = 'cinema';
		$model = [
			'id' => [
				'type'    => OCore::PK,
				'comment' => 'Id único para cada cine'
			],
			'id_user' => [
				'type'     => OCore::NUM,
				'comment'  => 'Id del usuario que añade el cine',
				'nullable' => false,
				'ref'      => 'user.id'
			],
			'name' => [
				'type'     => OCore::TEXT,
				'size'     => 50,
				'comment'  => 'Nombre del cine',
				'nullable' => false
			],
			'slug' => [
				'type'     => OCore::TEXT,
				'size'     => 50,
				'comment'  => 'Slug del nombre del cine',
				'nullable' => false
			],
			'created_at' => [
				'type'    => OCore::CREATED,
				'comment' => 'Fecha de creación del registro'
			],
			'updated_at' => [
				'type'    => OCore::UPDATED,
				'comment' => 'Fecha de última modificación del registro'
			]
		];

		parent::load($table_name, $model);
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
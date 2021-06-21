<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;

class Movie extends OModel {
	/**
	 * Configures current model object based on data-base table structure
	 */
	function __construct() {
		$table_name = 'movie';
		$model = [
			'id' => [
				'type'    => OModel::PK,
				'comment' => 'Id único de cada película'
			],
			'id_user' => [
				'type'     => OModel::NUM,
				'comment'  => 'Id del usuario que añade la película',
				'nullable' => false,
				'ref'      => 'user.id'
			],
			'id_cinema' => [
				'type'     => OModel::NUM,
				'comment'  => 'Id del cine en el que un usuario ha visto la película',
				'nullable' => false,
				'ref'      => 'cinema.id'
			],
			'name' => [
				'type'     => OModel::TEXT,
				'size'     => 50,
				'comment'  => 'Nombre de la película',
				'nullable' => false
			],
			'slug' => [
				'type'     => OModel::TEXT,
				'size'     => 50,
				'comment'  => 'Slug del nombre de la película',
				'nullable' => false
			],
			'imdb_url' => [
				'type'     => OModel::TEXT,
				'size'     => 200,
				'comment'  => 'Url de la película en IMDB',
				'nullable' => false
			],
			'movie_date' => [
				'type'     => OModel::DATE,
				'comment'  => 'Fecha en la que un usuario fue a ver la película',
				'nullable' => false
			],
			'created_at' => [
				'type'    => OModel::CREATED,
				'comment' => 'Fecha de creación del registro'
			],
			'updated_at' => [
				'type'    => OModel::UPDATED,
				'comment' => 'Fecha de última modificación del registro'
			]
		];

		parent::load($table_name, $model);
	}

	/**
	 * Devuelve el nombre de la película
	 */
	public function __toString(){
		return $this->get('name');
	}

	/**
	 * Devuelve la URL de la carátula de la película
	 *
	 * @return string URL de la carátula de la película
	 */
	public function getCoverUrl(): string {
		global $core;
		return $core->config->getUrl('base').'cover/'.$this->get('id').'.webp';
	}

	/**
	 * Devuelve la URL del ticket de la película
	 *
	 * @return string URL del ticket de la película
	 */
	public function getTicketUrl(): string {
		global $core;
		return $core->config->getUrl('base').'ticket/'.$this->get('id').'.webp';
	}

	/**
	 * Borra una película de la base de datos, su carátula y su ticket
	 *
	 * @return void
	 */
	public function deleteFull(): void{
		global $core;
		$cover_route  = $core->config->getDir('web').'cover/'.$this->get('id').'.webp';
		$ticket_route = $core->config->getDir('web').'ticket/'.$this->get('id').'.webp';

		if (file_exists($cover_route)) {
			unlink($cover_route);
		}
		if (file_exists($ticket_route)) {
			unlink($ticket_route);
		}

		$this->delete();
	}
}
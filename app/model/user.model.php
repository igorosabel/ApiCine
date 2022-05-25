<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;

class User extends OModel{
	/**
	 * Configures current model object based on data-base table structure
	 */
	 function __construct() {
		$model = [
			'id' => [
				'type'    => OModel::PK,
				'comment' => 'Id único de cada usuario'
			],
			'name' => [
				'type'     => OModel::TEXT,
				'size'     => 100,
				'comment'  => 'Nombre de usuario',
				'nullable' => false
			],
			'pass' => [
				'type'     => OModel::TEXT,
				'size'     => 255,
				'comment'  => 'Contraseña cifrada del usuario',
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

		parent::load($model);
	}

	/**
	 * Devuelve el nombre del usuario
	 */
	public function __toString(){
		return $this->get('name');
	}
}
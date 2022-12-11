<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;
use OsumiFramework\OFW\DB\OModelGroup;
use OsumiFramework\OFW\DB\OModelField;

class User extends OModel{
	/**
	 * Configures current model object based on data-base table structure
	 */
	 function __construct() {
		$model = new OModelGroup(
			new OModelField(
				name: 'id',
				type: OMODEL_PK,
				comment: 'Id único de cada usuario'
			),
			new OModelField(
				name: 'name',
				type: OMODEL_TEXT,
				size: 100,
				comment: 'Nombre de usuario',
				nullable: false
			),
			new OModelField(
				name: 'pass',
				type: OMODEL_TEXT,
				size: 255,
				comment: 'Contraseña cifrada del usuario',
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
	 * Devuelve el nombre del usuario
	 */
	public function __toString(){
		return $this->get('name');
	}
}
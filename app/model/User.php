<?php
class User extends OModel{
  function __construct(){
    $table_name = 'user';
    $model = [
        'id' => [
          'type'    => OCore::PK,
          'comment' => 'Id único de cada usuario'
        ],
        'name' => [
          'type'     => OCore::TEXT,
          'size'     => 100,
          'comment'  => 'Nombre de usuario',
          'nullable' => false
        ],
        'pass' => [
          'type'     => OCore::TEXT,
          'size'     => 255,
          'comment'  => 'Contraseña cifrada del usuario',
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

  public function __toString(){
    return $this->get('name');
  }
}
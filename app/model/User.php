<?php
class User extends OBase{
  function __construct(){
    $table_name = 'user';
    $model = [
        'id' => [
          'type'    => Base::PK,
          'comment' => 'Id único de cada usuario'
        ],
        'username' => [
          'type'     => Base::TEXT,
          'size'     => 100,
          'comment'  => 'Nombre de usuario',
          'nullable' => false
        ],
        'pass' => [
          'type'     => Base::TEXT,
          'size'     => 255,
          'comment'  => 'Contraseña cifrada del usuario',
          'nullable' => false
        ],
        'created_at' => [
          'type'    => Base::CREATED,
          'comment' => 'Fecha de creación del registro'
        ],
        'updated_at' => [
          'type'    => Base::UPDATED,
          'comment' => 'Fecha de última modificación del registro'
        ]
    ];

    parent::load($table_name, $model);
  }

  public function __toString(){
    return $this->get('username');
  }
}
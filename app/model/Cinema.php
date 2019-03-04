<?php
class Cinema extends OBase{
  function __construct(){
    $table_name = 'cinema';
    $model = [
        'id' => [
          'type'    => Base::PK,
          'comment' => 'Id único para cada cine'
        ],
        'id_user' => [
          'type'     => Base::NUM,
          'comment'  => 'Id del usuario que añade el cine',
          'nullable' => false,
          'ref'      => 'user.id'
        ],
        'name' => [
          'type'     => Base::TEXT,
          'size'     => 50,
          'comment'  => 'Nombre del cine',
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
    return $this->get('name');
  }
}
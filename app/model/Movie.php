<?php
class Movie extends OBase{
  function __construct(){
    $table_name = 'movie';
    $model = [
        'id' => [
          'type'    => Base::PK,
          'comment' => 'Id único de cada película'
        ],
        'id_user' => [
          'type'     => Base::NUM,
          'comment'  => 'Id del usuario que añade la película',
          'nullable' => false,
          'ref'      => 'user.id'
        ],
        'id_cinema' => [
          'type'     => Base::NUM,
          'comment'  => 'Id del cine en el que un usuario ha visto la película',
          'nullable' => false,
          'ref'      => 'cinema.id'
        ],
        'name' => [
          'type'     => Base::TEXT,
          'size'     => 50,
          'comment'  => 'Nombre de la película',
          'nullable' => false
        ],
        'ext' => [
          'type'     => Base::TEXT,
          'size'     => 5,
          'comment'  => 'Extensión del archivo de la entrada',
          'nullable' => false
        ],
        'imdb_url' => [
          'type'     => Base::TEXT,
          'size'     => 200,
          'comment'  => 'Url de la película en IMDB',
          'nullable' => false
        ],
        'cover_ext' => [
          'type'     => Base::TEXT,
          'size'     => 5,
          'comment'  => 'Extensión del archivo de la carátula',
          'nullable' => false
        ],
        'movie_date' => [
          'type'     => Base::DATE,
          'comment'  => 'Fecha en la que un usuario fue a ver la película',
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
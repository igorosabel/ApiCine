<?php
class Movie extends OModel{
  function __construct(){
    $table_name = 'movie';
    $model = [
        'id' => [
          'type'    => OCore::PK,
          'comment' => 'Id único de cada película'
        ],
        'id_user' => [
          'type'     => OCore::NUM,
          'comment'  => 'Id del usuario que añade la película',
          'nullable' => false,
          'ref'      => 'user.id'
        ],
        'id_cinema' => [
          'type'     => OCore::NUM,
          'comment'  => 'Id del cine en el que un usuario ha visto la película',
          'nullable' => false,
          'ref'      => 'cinema.id'
        ],
        'name' => [
          'type'     => OCore::TEXT,
          'size'     => 50,
          'comment'  => 'Nombre de la película',
          'nullable' => false
        ],
        'slug' => [
          'type'     => OCore::TEXT,
          'size'     => 50,
          'comment'  => 'Slug del nombre de la película',
          'nullable' => false
        ],
        'ext' => [
          'type'     => OCore::TEXT,
          'size'     => 5,
          'comment'  => 'Extensión del archivo de la entrada',
          'nullable' => false
        ],
        'imdb_url' => [
          'type'     => OCore::TEXT,
          'size'     => 200,
          'comment'  => 'Url de la película en IMDB',
          'nullable' => false
        ],
        'cover_ext' => [
          'type'     => OCore::TEXT,
          'size'     => 5,
          'comment'  => 'Extensión del archivo de la carátula',
          'nullable' => false
        ],
        'movie_date' => [
          'type'     => OCore::DATE,
          'comment'  => 'Fecha en la que un usuario fue a ver la película',
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

  public function getCoverUrl(){
    global $core;
    return $core->config->getUrl('base').'cover/'.$this->get('id').'.'.$this->get('cover_ext');
  }

  public function getTicketUrl(){
    global $core;
    return $core->config->getUrl('base').'ticket/'.$this->get('id').'.'.$this->get('ext');
  }

  public function deleteFull(){
    global $core;
    $cover_route  = $core->config->getDir('web').'cover/'.$this->get('id').'.'.$this->get('cover_ext');
    $ticket_route = $core->config->getDir('web').'ticket/'.$this->get('id').'.'.$this->get('ext');

    if (file_exists($cover_route)){
      unlink($cover_route);
    }
    if (file_exists($ticket_route)){
      unlink($ticket_route);
    }

    $this->delete();
  }
}
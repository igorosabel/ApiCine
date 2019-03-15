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
        'slug' => [
          'type'     => Base::TEXT,
          'size'     => 50,
          'comment'  => 'Slug del nombre del cine',
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

  private $movies = null;

  public function getMovies(){
    if (is_null($this->movies)){
      $this->loadMovies();
    }
    return $this->movies;
  }

  public function setMovies($movies){
    $this->movies = $movies;
  }

  public function loadMovies(){
    $sql = "SELECT * FROM `movie` WHERE `id_cinema` = ? ORDER BY `movie_date` DESC";
    $this->db->query($sql, [$this->get('id')]);
    $list = [];

    while ($res=$this->db->next()){
      $movie = new Movie();
      $movie->update($res);

      array_push($list, $movie);
    }

    $this->setMovies($list);
  }
}
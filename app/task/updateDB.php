<?php
class updateDBTask{
  public function __toString(){
    return "updateDB: Función para actualizar la base de datos de la versión 1 a la 2.";
  }

  private $db = null;

  function __construct(){
    $this->db = new ODB();
  }

  public function loadCinemas(){
    $sql = "SELECT * FROM `cine`";
    $this->db->query($sql);
    $cines = $this->db->fetchAll();

    $sql = "INSERT INTO `cinema` (`id`, `id_user`, `name`, `slug`, `created_at`, `updated_at`) VALUES (?, 1, ?, ?, ?, ?)";
    foreach ($cines as $cine){
      echo "SQL: ".$sql."\n";
      $params = [
        (int)$cine['id'],
        $cine['nombre'],
        Base::slugify($cine['nombre']),
        $cine['created_at'],
        $cine['updated_at']
      ];
      echo "PARAMS: \n";
      var_dump($params);
      echo "\n";
      $this->db->query($sql, $params);
    }
  }

  public function loadMovies(){
    $sql = "SELECT * FROM `pelicula`";
    $this->db->query($sql);
    $peliculas = $this->db->fetchAll();

    $sql = "INSERT INTO `movie` (`id`, `id_user`, `id_cinema`, `name`, `slug`, `ext`, `imdb_url`, `cover_ext`, `movie_date`, `created_at`, `updated_at`) VALUES (?, 1, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    foreach ($peliculas as $pelicula){
      echo "SQL: ".$sql."\n";
      $params = [
        (int)$pelicula['id'],
        (int)$pelicula['id_cine'],
        $pelicula['nombre'],
        Base::slugify($pelicula['nombre']),
        $pelicula['ext'],
        $pelicula['imdb_url'],
        $pelicula['caratula_ext'],
        $pelicula['fecha'],
        $pelicula['created_at'],
        $pelicula['updated_at']
      ];
      echo "PARAMS: \n";
      var_dump($params);
      echo "\n";
      $this->db->query($sql, $params);
    }
  }

  public function run(){
    $this->loadMovies();
  }
}
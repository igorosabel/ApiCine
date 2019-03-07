<?php
class webService extends OService{
  function __construct($controller=null){
    $this->setController($controller);
  }

  public function getCinemas($id_user){
    $db = $this->getController()->getDB();
    $sql = "SELECT * FROM `cinema` WHERE `id_user` = ?";
    $db->query($sql, [$id_user]);
    $ret = [];

    while ($res = $db->next()){
      $cinema = new Cinema();
      $cinema->update($res);

      array_push($ret, $cinema);
    }

    return $ret;
  }

  public function getMovies($id_user, $page){
    $db = $this->getController()->getDB();
    $c  = $this->getController()->getConfig();
    $lim = ($page-1) * $c->getExtra('num_por_pag');

    $sql = "SELECT * FROM `movie` WHERE `id_user` = ? LIMIT ?, ?";
    $db->query($sql, [$id_user, $lim, $c->getExtra('num_por_pag')]);
    $ret = [];

    while ($res = $db->next()){
      $movie = new Movie();
      $movie->update($res);

      array_push($ret, $movie);
    }

    return $ret;
  }

  public function getCinemaMovies($cinema){
    $db = $this->getController()->getDB();
    $sql = "SELECT * FROM `movie` WHERE `id_user` = ? AND `id_cinema` = ?";

    $db->query($sql, [$cinema->get('id_user'), $cinema->get('id')]);
    $ret = [];

    while ($res = $db->next()){
      $movie = new Movie();
      $movie->update($res);

      array_push($ret, $movie);
    }

    return $ret;
  }

  public function deleteCinema($cinema){
    $movies = $this->getCinemaMovies($cinema);
    foreach ($movies as $movie){
      $movie->deleteFull();
    }

    $cinema->delete();
  }
  
  public function tmdbList($q){
    $c = $this->getController()->getConfig();
    $query = sprintf("https://api.themoviedb.org/3/search/movie?query=%s&language=es-ES&api_key=%s",
      urlencode($q),
      $c->getExtra('tmdb_api_key')
    );
    $curl = curl_init();

    curl_setopt_array($curl, [
      CURLOPT_URL => $query,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_POSTFIELDS => "{}",
    ]);
    
    $response = curl_exec($curl);
    $err = curl_error($curl);
    
    curl_close($curl);
    
    if ($err){
      return "cURL Error #:" . $err;
    }
    else {
      $list = [];
      $data = json_decode($response, true);
      foreach ($data['results'] as $result){
        array_push($list, [
          'id' => $result['id'],
          'title' => $result['title']
        ]);
      }
      return $list;
    }
  }
}
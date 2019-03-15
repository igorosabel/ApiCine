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

    $sql = "SELECT * FROM `movie` WHERE `id_user` = ? ORDER BY `movie_date` DESC LIMIT ".$lim.",".$c->getExtra('num_por_pag');
    $db->query($sql, [$id_user]);
    $ret = [];

    while ($res = $db->next()){
      $movie = new Movie();
      $movie->update($res);

      array_push($ret, $movie);
    }

    return $ret;
  }

  public function getMoviesPages($id_user){
    $db = $this->getController()->getDB();
    $c  = $this->getController()->getConfig();

    $sql = "SELECT COUNT(*) AS `num` FROM `movie` WHERE `id_user` = ?";
    $db->query($sql, [$id_user]);
    $res = $db->next();

    return ceil( (int)$res['num'] / $c->getExtra('num_por_pag'));
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
          'title' => $result['title'],
          'poster' => 'http://image.tmdb.org/t/p/w300'.$result['poster_path']
        ]);
      }
      return $list;
    }
  }

  public function tmdbDetail($id){
    $c = $this->getController()->getConfig();
    $query = sprintf("https://api.themoviedb.org/3/movie/%s?api_key=%s&language=es-ES",
      $id,
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
      $data = json_decode($response, true);
      return ['title'    => $data['title'],
              'poster'   => 'http://image.tmdb.org/t/p/w300'.$data['poster_path'],
              'imdb_url' => 'https://www.imdb.com/title/'.$data['imdb_id'].'/'
             ];
    }
  }

  public function getParsedDate($str){
    $fec = strtotime($str);
    return date('Y-m-d H:i:s', $fec);
  }

  public function getImageExt($img){
    $arr_data = explode(';', $img);
    $arr_data = explode(':', $arr_data[0]);
    $arr_data = explode('/', $arr_data[1]);

    return $arr_data[1];
  }

  public function saveTicket($base64_string, $id, $ext) {
    $c = $this->getController()->getConfig();
    $route = $c->getDir('web').'ticket/'.$id.'.'.$ext;
    $this->saveImage($route, $base64_string);
  }

  public function saveCover($base64_string, $id, $ext) {
    $c = $this->getController()->getConfig();
    $route = $c->getDir('web').'cover/'.$id.'.'.$ext;
    $this->saveImage($route, $base64_string);
  }

  public function saveImage($route, $base64_string){
    if (file_exists($route)){
      unlink($route);
    }

    $ifp = fopen($route, 'wb');
    $data = explode(',', $base64_string);
    fwrite($ifp, base64_decode($data[1]));
    fclose($ifp);
  }

  public function saveCoverImage($image, $id, $ext){
    $c = $this->getController()->getConfig();
    $route = $c->getDir('web').'cover/'.$id.'.'.$ext;
    if (file_exists($route)){
      unlink($route);
    }

    $ifp = fopen($route, 'wb');
    fwrite($ifp, $image);
    fclose($ifp);
  }
}
<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Service;

use Osumi\OsumiFramework\Core\OService;
use Osumi\OsumiFramework\ORM\ODB;
use Osumi\OsumiFramework\Tools\OTools;
use Osumi\OsumiFramework\Plugins\OImage;
use Osumi\OsumiFramework\App\Model\Movie;

class MovieService extends OService {
  /**
	 * Obtiene la lista de películas de un usuario
	 *
	 * @param int $id_user Id del usuario
	 *
	 * @param int $page Página de resultados a buscar
	 *
	 * @return array Lista de películas del usuario
	 */
 	public function getMovies(int $id_user, int $page): array {
		$lim = ($page - 1) * $this->getConfig()->getExtra('num_por_pag');
		return Movie::where(
			['id_user' => $id_user],
			[
				'order_by' => 'movie_date#desc',
				'limit' => $lim . "," . $this->getConfig()->getExtra('num_por_pag')
			]);
	}

	/**
	 * Obtiene el número de páginas de resultados de las películas de un usuario
	 *
	 * @param int $id_user Id del usuario
	 *
	 * @return int Número de páginas de resultados
	 */
	public function getMoviesPages(int $id_user): int {
		$num = Movie::count(['id_user' => $id_user]);
		return intval( ceil( $num / $this->getConfig()->getExtra('num_por_pag')) );
	}

	/**
	 * Obtiene la lista de películas de un usuario, buscando por título
	 *
	 * @param int $id_user Id del usuario
	 *
	 * @param string $q Cadena de texto a buscar
	 *
	 * @return array Lista de películas del usuario
	 */
 	public function getMoviesByTitle(int $id_user, string $q): array {
		$db = new ODB();
		$sql = "SELECT * FROM `movie` WHERE `id_user` = ? AND `slug` LIKE '%" . OTools::slugify($q) . "%' ORDER BY `movie_date` DESC";
		$db->query($sql, [$id_user]);
		$ret = [];

		while ($res = $db->next()) {
			$movie = new Movie($res);
			$ret[] = $movie;
		}

		return $ret;
	}

	/**
	 * Obtiene el número de páginas de resultados de las películas de un usuario
	 *
	 * @param int $id_user Id del usuario
	 *
	 * @param string $q Cadena de texto a buscar
	 *
	 * @return int Número de páginas de resultados
	 */
	public function getMoviesPagesByTitle(int $id_user, string $q): int {
		$db = new ODB();
		$sql = "SELECT COUNT(*) AS `num` FROM `movie` WHERE `id_user` = ? AND `slug` LIKE '%" . OTools::slugify($q) . "%'";
		$db->query($sql, [$id_user]);
		$res = $db->next();

		return intval( ceil( (int) $res['num'] / $this->getConfig()->getExtra('num_por_pag')) );
	}

  /**
	 * Devuelve una fecha con un formato concreto (Y-m-d H:i:s)
	 *
	 * @param string $str Fecha a formatear
	 *
	 * @return string Fecha formateada
	 */
	public function getParsedDate(string $str): string {
		return date('Y-m-d H:i:s', strtotime($str));
	}

  /**
	 * Obtiene la extensión de un archivo de imagen a partir de una cadena de Base64
	 *
	 * @param string $img Contenido de la imagen en Base64
	 *
	 * @return string Extensión del archivo de imagen
	 */
	public function getImageExt(string $img): string {
		$arr_data = explode(';', urldecode($img));
		$arr_data = explode(':', $arr_data[0]);
		$arr_data = explode('/', $arr_data[1]);

		return $arr_data[1];
	}

  /**
	 * Guarda la imagen del ticket de una película
	 *
	 * @param string $base64_string Contenido de la imagen del ticket en Base64
	 *
	 * @param int $id Id de la imagen en la base de datos
	 *
	 * @param string $ext Extensión del archivo de imagen a guardar
	 *
	 * @return void
	 */
	public function saveTicket(string $base64_string, int $id, string $ext): void {
		$route_orig = $this->getConfig()->getDir('web') . 'ticket/' . $id . '.' . $ext;
		$route_webp = $this->getConfig()->getDir('web') . 'ticket/' . $id . '.webp';
		$this->saveImage($route_orig, $base64_string);
		$this->saveImageWebp($route_orig, $route_webp);
	}

	/**
	 * Guarda la imagen de la carátula de una película
	 *
	 * @param string $base64_string Contenido de la imagen de la carátula en Base64
	 *
	 * @param int $id Id de la imagen en la base de datos
	 *
	 * @param string $ext Extensión del archivo de imagen a guardar
	 *
	 * @return void
	 */
	public function saveCover(string $base64_string, int $id, string $ext): void {
		$route_orig = $this->getConfig()->getDir('web') . 'cover/' . $id . '.' . $ext;
		$route_webp = $this->getConfig()->getDir('web') . 'cover/' . $id . '.webp';
		$this->saveImage($route_orig, $base64_string);
		$this->saveImageWebp($route_orig, $route_webp);
	}

	/**
	 * Guarda una carátula como archivo
	 *
	 * @param string $image Contenido de la imagen
	 *
	 * @param int $id Id de la imagen en la base de datos
	 *
	 * @param string $ext Extensión del archivo de imagen a guardar
	 *
	 * @return void
	 */
	public function saveCoverImage(string $image, int $id, string $ext): void {
		$route_orig = $this->getConfig()->getDir('web') . 'cover/' . $id . '.' . $ext;
		$route_webp = $this->getConfig()->getDir('web') . 'cover/' . $id . '.webp';
		if (file_exists($route_orig)) {
			unlink($route_orig);
		}

		$ifp = fopen($route_orig, 'wb');
		fwrite($ifp, $image);
		fclose($ifp);

		$this->saveImageWebp($route_orig, $route_webp);
	}

	/**
	 * Guarda una imagen a partir de una cadena de Base64
	 *
	 * @param string $route Ruta del archivo de imagen a guardar
	 *
	 * @param string $base64_string Contenido de la imagen en Base64
	 *
	 * @return void
	 */
	private function saveImage(string $route, string $base64_string): void {
		if (file_exists($route)) {
			unlink($route);
		}

		$ifp = fopen($route, 'wb');
		$data = explode(',', $base64_string);
		fwrite($ifp, base64_decode($data[1]));
		fclose($ifp);
	}

	/**
	 * Guarda una imagen previa, temporal, como la definitiva en formato Webp
	 *
	 * @param string $route_orig Ruta al archivo temporal original
	 *
	 * @param string $route_webp Ruta definitiva al archivo webp
	 *
	 * @return void
	 */
	private function saveImageWebp(string $route_orig, string $route_webp): void {
		$im = new OImage();
		$im->load($route_orig);

		// Compruebo tamaño inicial
		if ($im->getWidth() > 1000) {
			$im->resizeToWidth(1000);
			$im->save($route_orig, $im->getImageType());
		}

		// Guardo la imagen ya modificada como WebP
		$im->save($route_webp, IMAGETYPE_WEBP);

		// Borro la imagen temporal
		unlink($route_orig);
	}
}

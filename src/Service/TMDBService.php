<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Service;

use Osumi\OsumiFramework\Core\OService;

class TMDBService extends OService {
  /**
	 * Obtiene la lista de películas de The Movie Database
	 *
	 * @param string $q Cadena de texto a buscar
	 *
	 * @return ?array Lista de resultados obtenidos o null en caso de que haya algún error
	 */
	public function tmdbList($q): ?array {
		$query = sprintf($this->getConfig()->getExtra('tmdb_search_url'),
			urlencode($q),
			$this->getConfig()->getExtra('tmdb_api_key')
		);
		$curl = curl_init();

		curl_setopt_array($curl, [
			CURLOPT_URL => $query,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET"
		]);

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
			$this->getLog()->error('cURL Error #:'.$err);
			return null;
		}

		$list = [];
		$data = json_decode($response, true);

		foreach ($data['results'] as $result) {
			$list[] = [
				'id'     => $result['id'],
				'title'  => $result['title'],
				'poster' => sprintf($this->getConfig()->getExtra('tmdb_poster_url'), $result['poster_path'])
			];
		}
		return $list;
	}

	/**
	 * Obtiene el detalle de una película
	 *
	 * @param int $id Id de una película
	 *
	 * @return ?array Detalle de la película o null si ocurre algún error
	 */
	public function tmdbDetail(int $id): ?array {
		$query = sprintf($this->getConfig()->getExtra('tmdb_movie_url'),
			$id,
			$this->getConfig()->getExtra('tmdb_api_key')
		);
		$curl = curl_init();

		curl_setopt_array($curl, [
			CURLOPT_URL => $query,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET"
		]);

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
			$this->getLog()->error('cURL Error #:'.$err);
			return null;
		}

		$data = json_decode($response, true);
		return [
			'title'    => $data['title'],
			'poster'   => sprintf($this->getConfig()->getExtra('tmdb_poster_url'), $data['poster_path']),
			'imdb_url' => sprintf($this->getConfig()->getExtra('imdb_url'), $data['imdb_id'])
		];
	}
}

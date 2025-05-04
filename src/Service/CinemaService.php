<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Service;

use Osumi\OsumiFramework\Core\OService;
use Osumi\OsumiFramework\App\Model\Cinema;

class CinemaService extends OService {
  /**
	 * Obtiene la lista de cines de un usuario
	 *
	 * @param int $id_user Id del usuario
	 *
	 * @return array Lista de cines del usuario
	 */
	public function getCinemas(int $id_user): array {
		return Cinema::where(['id_user' => $id_user]);
 	}

	/**
	 * Borrar un cine con todas sus películas
	 *
	 * @param Cinema $cinema Objeto cine que hay que borrar
	 *
	 * @return void
	 */
	public function deleteCinema(Cinema $cinema): void {
		$movies = $cinema->getMovies();
		foreach ($movies as $movie) {
			$movie->deleteFull();
		}

		$cinema->delete();
	}
}

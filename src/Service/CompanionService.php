<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Service;

use Osumi\OsumiFramework\Core\OService;
use Osumi\OsumiFramework\App\Model\Companion;

class CompanionService extends OService {
  /**
	 * Obtiene la lista de acompañantes de un usuario
	 *
	 * @param int $id_user Id del usuario
	 *
	 * @return array Lista de acompañantes del usuario
	 */
	public function getCompanions(int $id_user): array {
		return Companion::where(['for_user' => $id_user]);
 	}
}

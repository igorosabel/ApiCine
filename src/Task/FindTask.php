<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Task;

use Osumi\OsumiFramework\Core\OTask;
use Osumi\OsumiFramework\App\Service\WebService;

class FindTask extends OTask {
	public function __toString() {
		return "find: Función para buscar películas en The Movie Database";
	}

	private ?webService $web_service = null;

	function __construct() {
		$this->web_service = new WebService();
	}

	public function run(array $options=[]): void {
		if (count($options)==0) {
			echo "\n  Error: tienes que indicar una cadena de texto a buscar.\n\n";
			exit;
		}

		$result = $this->web_service->tmdbList($options[0]);
		var_dump($result);
	}
}

<?php declare(strict_types=1);
class findTask {
	public function __toString() {
		return "find: Función para buscar películas en The Movie Database";
	}

	private ?webService $web_service = null;

	function __construct() {
		$this->web_service = new webService();
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
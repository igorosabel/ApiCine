<?php declare(strict_types=1);

namespace OsumiFramework\App\Module;

use OsumiFramework\OFW\Core\OModule;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\OFW\Routing\ORoute;
use OsumiFramework\OFW\Routing\OUrl;

class home extends OModule {
	/**
	 * Página temporal, sitio cerrado
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	#[ORoute(
		'/closed',
		layout: 'blank'
	)]
	public function closed(ORequest $req): void {
		OUrl::goToUrl('https://cine.osumi.es');
	}

	/**
	 * Página de error 404
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	#[ORoute('/not-found')]
	public function notFound(ORequest $req): void {
		OUrl::goToUrl('https://cine.osumi.es');
	}

	/**
	 * Home pública
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	#[ORoute('/')]
	public function index(ORequest $req): void {
		OUrl::goToUrl('https://cine.osumi.es');
	}
}
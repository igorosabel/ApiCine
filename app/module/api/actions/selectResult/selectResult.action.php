<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;

#[OModuleAction(
	url: '/select-result',
	filter: 'login',
	services: 'web'
)]
class selectResultAction extends OAction {
	/**
	 * Función para obtener el detalle de una película en The Movie Data Base
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$status   = 'ok';
		$id       = $req->getParamInt('id');
		$filter   = $req->getFilter('login');
		$title    = '';
		$poster   = '';
		$imdb_url = '';

		if (is_null($id) || is_null($filter) || !array_key_exists('id', $filter)) {
			$status = 'error';
		}

		if ($status=='ok') {
			$detail = $this->web_service->tmdbDetail($id);

			$title    = $detail['title'];
			$poster   = $detail['poster'];
			$imdb_url = $detail['imdb_url'];
		}

		$this->getTemplate()->add('status',   $status);
		$this->getTemplate()->add('title',    $title);
		$this->getTemplate()->add('poster',   $poster);
		$this->getTemplate()->add('imdb_url', $imdb_url);
	}
}

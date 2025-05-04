<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\Api\SaveCompanion;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Model\Companion;
use Osumi\OsumiFramework\App\Model\User;
use Osumi\OsumiFramework\App\Component\Model\Companion\CompanionComponent;

class SaveCompanionComponent extends OComponent {
	public string              $status    = 'ok';
  public ?CompanionComponent $companion = null;

	/**
	 * Función para guardar un acompañante
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req): void {
		$id       = $req->getParamInt('id');
		$name     = $req->getParamString('name');
    $username = $req->getParamString('username');
		$filter   = $req->getFilter('Login');
		$this->companion = new CompanionComponent();

		if (is_null($name) || is_null($filter) || !array_key_exists('id', $filter)) {
			$this->status = 'error';
		}

		if ($this->status === 'ok') {
			$c = new Companion();
			if (!is_null($id)) {
				$c = Companion::findOne(['id' => $id]);
			}
			$c->for_user = $filter['id'];
			$c->name = urldecode($name);

			if (!is_null($username) && $username !== '') {
	      $user = User::findOne(['name' => $username]);
	      if (!is_null($user)) {
	        $c->id_user = $user->id;
	      }
			}

			$c->save();

      $this->companion->companion = $c;
		}
	}
}

<?php
use Osumi\OsumiFramework\App\Component\Model\Companion\CompanionComponent;

foreach ($list as $i => $companion) {
  $component = new CompanionComponent([ 'companion' => $companion ]);
	echo strval($component);
	if ($i < count($list) - 1) {
		echo ",\n";
	}
}

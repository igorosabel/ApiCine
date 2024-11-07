<?php
use Osumi\OsumiFramework\App\Component\Model\Cinema\CinemaComponent;

foreach ($list as $i => $cinema) {
  $component = new CinemaComponent([ 'cinema' => $cinema ]);
	echo strval($component);
	if ($i < count($list) - 1) {
		echo ",\n";
	}
}

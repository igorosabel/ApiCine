<?php
use Osumi\OsumiFramework\App\Component\Model\Movie\MovieComponent;

foreach ($list as $i => $movie) {
  $component = new MovieComponent([ 'movie' => $movie ]);
	echo strval($component);
	if ($i < count($list) - 1) {
		echo ",\n";
	}
}

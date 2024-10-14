<?php foreach ($list as $i => $result): ?>
	{
		"id": <?php echo $result['id'] ?>,
		"title": "<?php echo urlencode($result['title']) ?>",
		"poster": "<?php echo urlencode($result['poster']) ?>"
	}<?php if ($i<count($list) - 1): ?>,<?php endif ?>
<?php endforeach ?>

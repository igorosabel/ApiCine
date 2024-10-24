<?php foreach ($list as $i => $cinema): ?>
{
	"id": <?php echo $cinema->id ?>,
	"name": "<?php echo urlencode($cinema->name) ?>",
	"slug": "<?php echo $cinema->slug ?>"
}<?php if ($i < count($list) - 1): ?>,<?php endif ?>
<?php endforeach ?>

<?php foreach ($values['list'] as $i => $cinema): ?>
  {
    "id": <?php echo $cinema->get('id') ?>,
    "name": "<?php echo urlencode($cinema->get('name')) ?>",
    "slug": "<?php echo $cinema->get('slug') ?>"
  }<?php if ($i<count($values['list'])-1): ?>,<?php endif ?>
<?php endforeach ?>
<?php foreach ($values['list'] as $i => $result): ?>
  {
    "id": <?php echo $result['id'] ?>,
    "title": "<?php echo urlencode($result['title']) ?>"
  }<?php if ($i<count($values['list'])-1): ?>,<?php endif ?>
<?php endforeach ?>
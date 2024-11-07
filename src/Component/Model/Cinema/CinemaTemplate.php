<?php if (is_null($cinema)): ?>
null
<?php else: ?>
{
	"id": {{ cinema.id }},
	"name": {{ cinema.name | string }},
	"slug": {{ cinema.slug | string }}
}
<?php endif ?>

<?php if (is_null($companion)): ?>
null
<?php else: ?>
{
	"id": {{ companion.id }},
	"idUser": {{ companion.id_user | number }},
	"name": {{ companion.name | string }},
	"username": <?php echo (!is_null($companion->getUser())) ? '"'.$companion->getUser()->name.'"' : 'null' ?>
}
<?php endif ?>

<ul>
<?php foreach ($subjects as $v): ?>
<li><?= $box('hello')->assign(['subject' => $v]) ?></li>
<?php endforeach; ?>
</ul>

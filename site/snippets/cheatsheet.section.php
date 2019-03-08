<ul class="cheatsheet-section-entries">
  <?php
  $advanced = param('advanced') === 'true';
  $entries  = $section->children()->listed();
  
  if ($advanced === false) {
    $entries = $entries->filter(function ($p) {
      return !$p->isInternal() && !$p->isDeprecated();
    });
  }
  ?>

  <?php foreach ($entries as $entry): ?>
  <li>
    <a href="<?= $entry->url() ?>">
      <?= $entry->title() ?>
    </a>
  </li>
  <?php endforeach ?>
</ul>

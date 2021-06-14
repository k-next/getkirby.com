<nav aria-label="Plugins menu">
  <div class="sticky" style="--top: var(--spacing-6)">
    <?php slot('hero') ?>
    <p class="h1 mb-12 color-gray-400"><a href="/plugins">Plugins</a></p>
    <?php endslot() ?>
    <ul class="filters">
      <li>
        <a href="/plugins"<?= ariaCurrent(!$currentCategory) ?>>
          <?= icon('list') ?> All plugins
        </a>
      </li>
      <li>
        <button class="search-button" type="button" data-area="plugin">
          <?= icon('search') ?> Search
        </button>
      </li>
      <li>
        <a href="/submit-plugin">
          <!-- @todo: use `plus` icon after fixed, currently plus icon dimensions are broken -->
          <?= icon('git') ?> Submit Plugin
        </a>
      </li>
      <li>
        <hr class="hr">
      </li>
      <?php foreach ($categories as $categoryId => $category): ?>
        <li>
          <a aria-label="<?= $category['label'] ?> plugins" href="/plugins/category:<?= $categoryId ?>"<?= ariaCurrent($categoryId === $currentCategory) ?>>
            <?= icon($category['icon']) ?> <?= $category['label'] ?>
          </a>
        </li>
      <?php endforeach ?>
    </ul>
  </div>
</nav>

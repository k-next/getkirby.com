<?php layout() ?>
<?= css('assets/css/layouts/features.css') ?>

<header class="mb-12 flex items-end justify-between">
  <div>
    <h1 class="h1">3.6-alpha ⚡</h1>
    <p class="h1 color-gray-600">Developer preview</p>
  </div>

  <?php snippet('cta', [
    'buttons' => [
      [
        'text' => 'Try now',
        'link' => 'https://github.com/getkirby/kirby/releases/tag/3.6.0-alpha.1',
        'icon' => 'download',
        'style' => 'filled'
      ],
      [
        'text' => '3.6 docs',
        'link' => $page->children()->first()->url(),
        'icon' => 'book',
        'style' => 'outlined'
      ],
    ],
    'center' => false,
    'mb' => 0
  ]) ?>
</header>

<?php snippet('templates/release-36/roadmap') ?>
<?php snippet('templates/release-36/fiber') ?>
<?php snippet('templates/release-36/plugins') ?>
<?php snippet('templates/release-36/cardlets') ?>

<section id="assets" class="mb-42">
  <div class="columns" style="--columns: 2; --gap: var(--spacing-24)">
    <div>
      <?php snippet('templates/features/intro', [
        'title' => 'WebP & Avif Support',
        'intro' => 'Serve smaller and better images',
        'text'  => 'Our image processing API finally supports webp and avif as better alternatives for your jpegs or pngs.',
      ]) ?>

      <figure class="bg-black rounded">
        <?= $page->webp()->kt() ?>
      </figure>

    </div>
    <div>
      <?php snippet('templates/features/intro', [
        'title' => 'Better panel image settings',
        'intro' => 'Improve your previews with custom queries',
        'text'  => 'You can now set custom backgrounds, icons, images and more for your pages via blueprint settings',
      ]) ?>

      <figure class="bg-black rounded">
        <?= $page->imageSettings()->kt() ?>
      </figure>

    </div>
  </div>
</section>

<?php snippet('templates/release-36/webp') ?>

<section id="get-started" class="mb-42">
  <h2 class="h2 text-center mb-6">What are you waiting for?</h2>
  <?php snippet('cta', [
    'buttons' => [
      [
        'text' => 'Try now',
        'link' => 'https://github.com/getkirby/kirby/releases/tag/3.6.0-alpha.1',
        'icon' => 'download'
      ],
      [
        'text'  => '3.6 docs',
        'link'  => $page->children()->first()->url(),
        'icon'  => 'book',
        'style' => 'outlined'
      ]
    ]
  ]) ?>
</section>

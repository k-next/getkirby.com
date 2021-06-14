<?php layout('plugins') ?>

<header class="mb-12">
  <h1 class="h1 mb-6">
    Submit Your Plugin
  </h1>

  <div class="prose">
    <p>If you have Kirby 3 plugin or if there is a Kirby plugin that you think is not in the plugins directory, you can add it via the form below. You must have a valid plugin skeleton to submit
      it.</p>
    <ul>
      <li>Plugin must be hosted on GitHub</li>
      <li>Existing <code>composer.json</code> file</li>
      <li>Composer type should be <code>kirby-plugin</code></li>
    </ul>
  </div>

  <?php if (empty($errors) === false) : ?>
    <aside class="pt-6">
      <?php foreach ($errors as $message): ?>
        <div class="block box box--alert mb-3">
          <?php snippet('kirbytext/box', [
            'type' => 'alert',
            'text' => $message
          ]) ?>
        </div>
      <?php endforeach ?>
    </aside>
  <?php endif ?>

  <?php if (param('status') === 'success'): ?>
    <aside class="pt-6 mb-3">
      <div class="block box box--success">
        <?php snippet('kirbytext/box', [
          'type' => 'success',
          'text' => 'Thanks for your entry and contribution. Your plugin has been successfully registered and submitted for approval. It will start publishing after approval.'
        ]) ?>
      </div>
    </aside>
  <?php endif ?>
</header>

<form action="http://forms.getkirby.test/submit-plugin" id="plugin-form" method="post" class="plugin-form highlight bg-light mb-12" enctype="multipart/form-data">
  <div class="plugin-fields">
    <div class="plugin-field mb-3">
      <label class="h5 mb-3 block" for="title">Title <small>(required)</small></label>
      <input class="input mb-3" id="title" name="title" type="text" value="<?= esc(get('title')) ?>" required>
      <p class="text-sm"></p>
    </div>
    <div class="plugin-field mb-3">
      <label class="h5 mb-3 block" for="url">Repository <small>(required)</small></label>
      <input class="input mb-3" id="url" name="url" type="url" value="<?= esc(get('url')) ?>" required>
      <p class="text-sm">GitHub repository url. Eg: <code>https://github.com/johndoe/new-kirby-plugin</code></p>
    </div>
    <div class="plugin-field mb-3">
      <label class="h5 mb-3 block" for="category">Category <small>(required)</small></label>
      <select name="category" class="input mb-3" required>
        <option value>Select Category</option>
        <?php foreach ($categories as $categoryId => $category): ?>
          <option value="<?= $categoryId ?>" <?= r((esc(get('category')) === $categoryId), 'selected') ?>><?= $category['label'] ?></option>
        <?php endforeach ?>
      </select>
      <p class="text-sm"></p>
    </div>
    <div class="plugin-field mb-3">
      <label class="h5 mb-3 block" for="description">Description <small>(required)</small></label>
      <textarea class="input mb-3" id="description" name="description" type="text" rows="5" minlength="20" maxlength="255" required><?= esc(get('description')) ?></textarea>
      <p class="text-sm"></p>
    </div>
    <div class="plugin-field mb-3">
      <label class="h5 mb-3 block" for="developer">Plugin Author <small>(required)</small></label>
      <input class="input mb-3" id="developer" name="developer" type="text" value="<?= esc(get('developer')) ?>" required>
      <p class="text-sm">If there is a plugin for this user, the name will not be updated.</p>
    </div>
    <div class="plugin-field mb-3">
      <label class="h5 mb-3 block" for="featured_image">Featured Image</label>
      <input class="input mb-3" id="featured_image" name="featured_image" type="file" accept="image/png">
      <p class="text-sm">Only JPG or PNG supports. Max resolution is 1280x800 px and max file size ise 1MB.</p>
    </div>
    <div class="plugin-field mb-3">
      <button type="submit" class="btn btn--filled w-100%">
        <svg width="16" height="16" fill="currentColor">
          <g transform="translate(0 0)">
            <path d="M9.2,0H5.4c-0.4,0-0.8,0.3-1,0.7l-2,7C2.2,8.4,2.7,9,3.3,9H7l-1.5,7l7.3-9.4C13.3,6,12.8,5,12,5H9l1.1-3.7 C10.3,0.6,9.8,0,9.2,0z"></path>
          </g>
        </svg>
        Submit
      </button>
    </div>
  </div>
</form>

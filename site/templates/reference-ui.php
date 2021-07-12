<?php layout('reference') ?>

<?php slot('toc') ?>
<?php endslot() ?>

<?php slot() ?>
<div class="prose">
  <?php if ($page->description()->isNotEmpty()): ?>
    <?= $page->description()->kt() ?>
  <?php endif ?>

  <?php if ($page->example()->isNotEmpty()): ?>
    <?= kirbytext("
```html
" . trim($page->example()) . "
```
    ") ?>
  <?php endif ?>
  
  <?php if ($page->props()->isNotEmpty()): ?>
  <h2>Props</h2>
  <div class="table">
    <table>
      <thead>
        <tr>
          <th>Prop</th>
          <th>Type</th>
          <th>Default</th>
          <th>Description</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($page->props()->value() as $prop): ?>
        <tr>
          <td><?= $prop['name'] ?></td>
          <td>
            <div class="<?= isset($prop['values']) ? 'mb-3' : null ?>">
              <?= Types::format($prop['type']['name']) ?>
            </div>
            <?php if ($prop['values'] ?? null) : ?>
              <small>
                <b>Valid values:</b><br> 
                <?= kti(implode(', ', $prop['values'])) ?>
              </small>
            <?php endif ?>
          </td>
          <td><?= $prop['defaultValue']['value'] ?? '<span>–</span>' ?></td>
          <td>
            <div class="<?= isset($prop['tags']['example']) ? 'mb-3' : null ?>">
              <?= kti($prop['description'] ?? null) ?>
            </div>
            
            <?php if ($prop['tags']['example'] ?? null) : ?>
              <small>
                <b>Example:</b><br> 
                <?= kti('```' . $prop['tags']['example'][0]['description'] . '```') ?>
              </small>
            <?php endif ?>
          </td>
        </tr>
        <?php endforeach ?>
      </tbody>
    </table>
  </div>
  <?php endif ?>

  <?php if ($page->methods()->isNotEmpty()): ?>
  <h2>Methods</h2>
  <div class="table">
    <table>
      <thead>
        <tr>
          <th>Method</th>
          <th>Parameters</th>
          <th>Description</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($page->methods()->value() as $method): ?>
      <tr>
        <td><?= $method['name'] ?></td>
        <td>
            <?php if (count($method['params'] ?? []) > 0) : ?>
            <ul>
              <?php foreach ($method['params'] as $param): ?>
              <li>
                <?= Types::format($param['type']['name'] ?? null) ?>
                <?= $param['name'] ?><br>
                <small><?= kti($param['description'] ?? null) ?></small>
              </li>
              <?php endforeach ?>
            </ul>
            <?php else: ?>
            <span>–</span>
            <?php endif ?>
          </td>
        <td>
          <?= kti($method['description'] ?? null) ?>
        </td>
      </tr>
      <?php endforeach ?>
      </tbody>
    </table>
  </div>
  <?php endif ?>

  <?php if ($page->events()->isNotEmpty()): ?>
  <h2>Events</h2>
  <div class="table">
    <table>
      <thead>
        <tr>
          <th>Event</th>
          <th>Description</th>
          <th>Passes</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($page->events()->value() as $event): ?>
        <tr>
          <td><?= $event['name'] ?></td>
          <td>
            <div class="<?= isset($event['tags'][0]) ? 'mb-3' : null ?>">
              <?= kti($event['description'] ?? null) ?>
            </div>
            
            <?php if ($event['tags'][0] ?? null) : ?>
              <small>
                <b>Example:</b><br> 
                <?= kti('```' . $event['tags'][0]['content'] . '```') ?>
              </small>
            <?php endif ?>  
          </td>        

          <td>
            <?php if (count($event['properties'] ?? []) > 0) : ?>
            <ul>
              <?php foreach ($event['properties'] as $property): ?>
              <li>
                <?= Types::format(implode('|', $property['type']['names'] ?? [])) ?>
                <?= $property['name'] ?><br>
                <small><?= kti($property['description'] ?? null) ?></small>
              </li>
              <?php endforeach ?>
            </ul>
            <?php else: ?>
            <span>–</span>
            <?php endif ?>
          </td>
        </tr>
        <?php endforeach ?>
      </tbody>
    </table>
  </div>
  <?php endif ?>

  <?php if ($page->slots()->isNotEmpty()): ?>
  <h2>Slots</h2>
  <div class="table">
    <table>
      <thead>
        <tr>
          <th>Slot</th>
          <th>Description</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($page->slots()->value() as $slot): ?>
        <tr>
          <td><?= $slot['name'] ?></td>
          <td><?= kti($slot['description'] ?? null) ?></td>
        </tr>
        <?php endforeach ?>
      </tbody>
    </table>
  </div>
  <?php endif ?>

  <?php if ($page->text()->isNotEmpty()): ?>
  <div id="docs">
    <div></div>
    <?= $page->text()->kt() ?>

    <h2>CSS classes</h2>
    <?= $page->css()->or('`.k-' . $page->slug() . '`')->kt() ?>
  </div>
  <?php endif ?>

</div>
<?php endslot() ?>

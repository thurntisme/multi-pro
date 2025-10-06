<?php
$lastSlug = getLastSegmentFromUrl();

$links = [
  '' => 'Market',
  'buy-list' => 'Buy List',
  'sell-list' => 'Sell List',
  'favorite' => 'Favorite',
];
?>

<ul class="nav nav-tabs nav-border-top nav-border-top-primary mb-3" role="tablist">
  <?php foreach ($links as $slug => $link) { ?>
    <li class="nav-item">
      <a class="nav-link <?= ($lastSlug === 'transfer' ? !$slug : $lastSlug === $slug) ? 'active' : '' ?>"
        href="<?= App\Helpers\Network::home_url("football-manager/transfer" . (!$slug ? $slug : '/' . $slug)) ?>">
        <?= $link ?>
      </a>
    </li>
  <?php } ?>
</ul>
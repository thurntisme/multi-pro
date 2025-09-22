<?php
$path = getLastSegmentFromUrl();

echo '<div>
  <div class="mt-2">
    <a href="' . home_url('finance') . '" class="btn btn-' . ($path == 'finance' ? '' : 'outline-') . 'dark">Overview</a>
    <a href="' . home_url('finance/budget') . '" class="btn btn-' . ($path == 'budget' ? '' : 'outline-') . 'info">Budget</a>
    <a href="' . home_url('finance/income') . '" class="btn btn-' . ($path == 'income' ? '' : 'outline-') . 'success">Income</a>
    <a href="' . home_url('finance/expenses') . '" class="btn btn-' . ($path == 'expenses' ? '' : 'outline-') . 'danger">Expenses</a>
  </div>
</div>';

<?php

switch ($type) {
  case 'select':
    renderFormControl($id, $name, $label, $options, $value);
    break;

  default:
    echo '';
    break;
}

function renderFormControl($id, $name, $label, $options = [], $value = null)
{
  echo '<div class="mb-3">';
  echo '<label for="' . htmlspecialchars($id) . '" class="form-label">' . htmlspecialchars($label) . '</label>';
  echo '<select class="form-select" data-choices data-choices-search-false name="' . $name . '" id="' . htmlspecialchars($id) . '">';
  if (empty($value)) {
    $selected = ' selected';
  }
  echo '<option value=""' . $selected . '>--Select--</option>';

  foreach ($options as $option => $text) {
    $selected = $value === $option ? ' selected' : '';
    echo '<option value="' . htmlspecialchars($option) . '"' . $selected . '>' . htmlspecialchars($text) . '</option>';
  }

  echo '</select>';
  echo '</div>';
}

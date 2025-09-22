<?php
$user_roles = [
  'guest' => 'Guest',
  'standard_user' => 'Standard User',
  'premium_user' => 'Premium User',
  'super_admin' => 'Super Admin',
];

$super_user_pack = ['super_admin'];
$premium_user_pack = ['super_admin', 'premium_user'];
$standard_user_pack = ['super_admin', 'premium_user', 'standard_user'];
$guest_user_pack = ['super_admin', 'premium_user', 'standard_user', 'guest'];

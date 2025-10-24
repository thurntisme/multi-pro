<?php

namespace App\Services;

use App\Core\Service;

class TokenService extends Service
{
  private $secretKey;

  public function __construct(string $secretKey = 'e$K#!eq32VSUZV2GWT3G')
  {
    $this->secretKey = $secretKey;
  }

  // 🔹 Simple hash function (same logic as JS simpleHash)
  private function simpleHash(string $input): string
  {
    $hash = 0;
    $len = strlen($input);

    for ($i = 0; $i < $len; $i++) {
      $hash = ($hash << 5) - $hash + ord($input[$i]);
      $hash = $hash & 0xFFFFFFFF; // 32-bit integer overflow
    }

    return base_convert(abs($hash), 10, 36);
  }

  // 🔹 Create guest token
  public function createToken(int $maxAgeSeconds = 86400): string
  {
    $payload = [
      'exp' => time() + $maxAgeSeconds,
      'random' => uniqid('', true),
    ];

    $base = base64_encode(json_encode($payload));
    $signature = $this->simpleHash($base . $this->secretKey);

    return $base . '.' . $signature;
  }

  // 🔹 Validate guest token
  public function validateToken(string $token): bool
  {
    if (empty($token)) return false;

    $parts = explode('.', $token);
    if (count($parts) !== 2) return false;

    [$base, $signature] = $parts;

    $expectedSig = $this->simpleHash($base . $this->secretKey);
    if ($expectedSig !== $signature) return false; // tampered

    $payload = json_decode(base64_decode($base), true);
    if (!$payload || !isset($payload['exp'])) return false;

    if ($payload['exp'] < time()) return false; // expired

    return true;
  }
}

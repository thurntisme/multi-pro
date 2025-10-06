<?php
namespace App\Core;

abstract class Service
{
    protected function fillData(?array $data, array $fields = []): ?array
    {
        if (!$data) {
            return null;
        }

        $result = [];
        foreach ($fields as $field) {
            if (array_key_exists($field, $data)) {
                $result[$field] = $data[$field];
            }
        }

        return $result;
    }
}

<?php

namespace Inilim;

class JSON
{
   public function isJSON(string $value): bool
   {
      $this->decode($value);
      return !$this->hasError();
   }

   public function isJSONAsArray(string $value): bool
   {
      $value = $this->decode($value);
      if ($this->hasError()) return false;
      return is_array($value);
   }

   public function isJSONAsInteger(string $value): bool
   {
      $value = $this->decode($value);
      if ($this->hasError()) return false;
      return is_int($value);
   }

   public function isJSONAsString(string $value): bool
   {
      $value = $this->decode($value);
      if ($this->hasError()) return false;
      return is_string($value);
   }

   public function isJSONAsFloat(string $value): bool
   {
      $value = $this->decode($value);
      if ($this->hasError()) return false;
      return is_float($value);
   }

   /**
    * @param mixed[]|array{} $default
    * @return mixed[]|array{}
    */
   public function tryDecodeAsArray(string $value, array $default = []): array
   {
      $value = $this->decode($value);
      if (is_array($value)) return $value;
      return $default;
   }

   public function tryDecodeAsString(string $value, string $default = ''): string
   {
      $value = $this->decode($value);
      if (is_string($value)) return $value;
      return $default;
   }

   public function tryDecodeAsInteger(string $value, int $default = 0): int
   {
      $value = $this->decode($value);
      if (is_int($value)) return $value;
      return $default;
   }

   public function tryDecodeAsFloat(string $value, float $default = 0.0): float
   {
      $value = $this->decode($value);
      if (is_float($value)) return $value;
      return $default;
   }

   // ------------------------------------------------------------------
   // protected
   // ------------------------------------------------------------------

   protected function hasError(): bool
   {
      return json_last_error() !== JSON_ERROR_NONE;
   }

   /**
    * @return mixed
    */
   protected function decode(string $value)
   {
      return json_decode($value, true);
   }
}

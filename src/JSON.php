<?php

namespace Inilim\JSON;

use stdClass;

class JSON
{
   public function isJSON(?string $value): bool
   {
      if (is_null($value)) return false;
      $this->decode($value);
      return !$this->hasError();
   }

   /**
    * @param boolean $strict true если вы ожидаете именно массив "[...]" | false если вы ожидаете массив или обьект как массив "[...]"|"{...}"
    */
   public function isJSONAsArray(?string $value, bool $strict = false): bool
   {
      if (is_null($value)) return false;
      $value = $this->decode($value, (!$strict ? true : false));
      if ($this->hasError()) return false;
      return is_array($value);
   }

   public function isJSONAsObject(?string $value): bool
   {
      if (is_null($value)) return false;
      $value = $this->decode($value, false);
      if ($this->hasError()) return false;
      return is_object($value);
   }

   public function isJSONAsInteger(?string $value): bool
   {
      if (is_null($value)) return false;
      $value = $this->decode($value);
      if ($this->hasError()) return false;
      return is_int($value);
   }

   public function isJSONAsString(?string $value): bool
   {
      if (is_null($value)) return false;
      $value = $this->decode($value);
      if ($this->hasError()) return false;
      return is_string($value);
   }

   public function isJSONAsFloat(?string $value): bool
   {
      if (is_null($value)) return false;
      $value = $this->decode($value);
      if ($this->hasError()) return false;
      return is_float($value);
   }

   /**
    * @param mixed[]|array{} $default
    * @return mixed[]|array{}
    */
   public function tryDecodeAsArray(?string $value, array $default = []): array
   {
      if (is_null($value)) return $default;
      $value = $this->decode($value);
      if (is_array($value)) return $value;
      return $default;
   }

   public function tryDecodeAsObject(?string $value, object $default = new stdClass): object
   {
      if (is_null($value)) return $default;
      $value = $this->decode($value, false);
      if (is_object($value)) return $value;
      return $default;
   }

   public function tryDecodeAsString(?string $value, string $default = ''): string
   {
      if (is_null($value)) return $default;
      $value = $this->decode($value);
      if (is_string($value)) return $value;
      return $default;
   }

   public function tryDecodeAsInteger(?string $value, int $default = 0): int
   {
      if (is_null($value)) return $default;
      $value = $this->decode($value);
      if (is_int($value)) return $value;
      return $default;
   }

   public function tryDecodeAsFloat(?string $value, float $default = 0.0): float
   {
      if (is_null($value)) return $default;
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
   protected function decode(
      string $value,
      bool $associative = true,
      int $depth = 512,
      int $flags = 0
   ) {
      return json_decode($value, $associative, $depth, $flags);
   }
}

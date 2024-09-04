<?php

namespace Inilim\JSON;

class JSON
{
   function isJSON(?string $value): bool
   {
      if ($value === null) return false;
      $this->decode($value);
      return !$this->hasError();
   }

   /**
    * @param mixed $value
    */
   function isJSONSerializable($value, int $flags = 0, int $depth = 512): bool
   {
      return \json_encode($value, $flags, $depth) !== false;
   }

   /**
    * @param boolean $strict true если вы ожидаете именно массив "[...]" | false если вы ожидаете массив или обьект как массив "[...]"|"{...}"
    */
   function isJSONAsArray(?string $value, bool $strict = false): bool
   {
      if ($value === null) return false;
      $value = $this->decode($value, (!$strict ? true : false));
      if ($this->hasError()) return false;
      return \is_array($value);
   }

   function isJSONAsObject(?string $value): bool
   {
      if ($value === null) return false;
      $value = $this->decode($value, false);
      if ($this->hasError()) return false;
      return \is_object($value);
   }

   function isJSONAsInteger(?string $value): bool
   {
      if ($value === null) return false;
      $value = $this->decode($value);
      if ($this->hasError()) return false;
      return \is_int($value);
   }

   function isJSONAsString(?string $value): bool
   {
      if ($value === null) return false;
      $value = $this->decode($value);
      if ($this->hasError()) return false;
      return \is_string($value);
   }

   function isJSONAsFloat(?string $value): bool
   {
      if ($value === null) return false;
      $value = $this->decode($value);
      if ($this->hasError()) return false;
      return \is_float($value);
   }

   /**
    * @param mixed $default
    * @return mixed
    */
   function tryDecodeAsArray(?string $value, $default = null)
   {
      if ($value === null) return $default;
      $value = $this->decode($value);
      if (\is_array($value)) return $value;
      return $default;
   }

   function dataGetFromJSON(?string $json, string $key_dot, $default = null)
   {
      $t = $this->tryDecodeAsArray($json, []);
      if (!$t) return $default;
      return \_arr()->dataGet(
         $t,
         $key_dot,
         $default,
      );
   }

   /**
    * @param mixed $default
    * @return mixed
    */
   function tryDecodeAsObject(?string $value, $default = null)
   {
      if ($value === null) return $default;
      $value = $this->decode($value, false);
      if (\is_object($value)) return $value;
      return $default;
   }

   /**
    * @param mixed $default
    * @return mixed
    */
   function tryDecodeAsString(?string $value, $default = null)
   {
      if ($value === null) return $default;
      $value = $this->decode($value);
      if (\is_string($value)) return $value;
      return $default;
   }

   /**
    * @param mixed $default
    * @return mixed
    */
   function tryDecodeAsInteger(?string $value, $default = null)
   {
      if ($value === null) return $default;
      $value = $this->decode($value);
      if (\is_int($value)) return $value;
      return $default;
   }

   /**
    * @param mixed $default
    * @return mixed
    */
   function tryDecodeAsFloat(?string $value, $default = null)
   {
      if ($value === null) return $default;
      $value = $this->decode($value);
      if (\is_float($value)) return $value;
      return $default;
   }

   /**
    * gettype - вернет null если json не валидный
    */
   function getTypeFromJSON(?string $value): ?string
   {
      if ($value === null) return null;
      $value = $this->decode($value, false);
      if ($this->hasError()) return null;
      return \gettype($value);
   }

   function getLastErrorCode(): int
   {
      return \json_last_error();
   }

   // ------------------------------------------------------------------
   // protected
   // ------------------------------------------------------------------

   protected function hasError(): bool
   {
      return \json_last_error() !== \JSON_ERROR_NONE;
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
      return \json_decode($value, $associative, $depth, $flags);
   }
}

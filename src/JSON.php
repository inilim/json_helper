<?php

namespace Inilim\JSON;

use Inilim\FuncCore\FuncCore;

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
      return $this->tryEncode($value, $flags, $depth) === null ? false : true;
   }

   function isJSONAsArrList(?string $value): bool
   {
      if ($value === null) return false;
      $value = $this->decode($value);
      if ($this->hasError()) return false;
      return \is_array($value);
   }

   /**
    * array assoc OR array list | analogue "isJSONAsArrOrObj"
    */
   function isJSONAsArray(?string $value): bool
   {
      return $this->isJSONAsArrOrObj($value);
   }

   function isJSONAsArrAssoc(?string $value): bool
   {
      return $this->isJSONAsObject($value);
   }

   /**
    * analogue "isJSONAsArray"
    */
   function isJSONAsArrOrObj(?string $value): bool
   {
      if ($value === null) return false;
      $value = $this->decode($value);
      if ($this->hasError()) return false;
      return \is_array($value) || \is_object($value);
   }

   function isJSONAsObject(?string $value): bool
   {
      if ($value === null) return false;
      $value = $this->decode($value);
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

   // ------------------------------------------------------------------
   // 
   // ------------------------------------------------------------------

   /**
    * @template T
    * @param T $default
    * @return list<mixed>|T
    */
   function tryDecodeAsArrList(?string $value, $default = null)
   {
      if ($value === null) return $default;
      $value = $this->decode($value);
      if (\is_array($value)) return $value;
      return $default;
   }

   /**
    * object to array
    * 
    * @template T
    * @param T $default
    * @return mixed[]|array{}|T
    */
   function tryDecodeAsArray(?string $value, $default = null)
   {
      if ($value === null) return $default;
      $value = $this->decode($value, true);
      if (\is_array($value)) return $value;
      return $default;
   }

   /**
    * @template T
    * @param T $default
    * @return object|T
    */
   function tryDecodeAsObject(?string $value, $default = null)
   {
      if ($value === null) return $default;
      $value = $this->decode($value);
      if (\is_object($value)) return $value;
      return $default;
   }

   /**
    * @template T
    * @param T $default
    * @return string|T
    */
   function tryDecodeAsString(?string $value, $default = null)
   {
      if ($value === null) return $default;
      $value = $this->decode($value);
      if (\is_string($value)) return $value;
      return $default;
   }

   /**
    * @template T
    * @param T $default
    * @return int|T
    */
   function tryDecodeAsInteger(?string $value, $default = null)
   {
      if ($value === null) return $default;
      $value = $this->decode($value);
      if (\is_int($value)) return $value;
      return $default;
   }

   /**
    * @template T
    * @param T $default
    * @return float|T
    */
   function tryDecodeAsFloat(?string $value, $default = null)
   {
      if ($value === null) return $default;
      $value = $this->decode($value);
      if (\is_float($value)) return $value;
      return $default;
   }

   // ------------------------------------------------------------------
   // 
   // ------------------------------------------------------------------

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
    * gettype - вернет null если json не валидный
    */
   function getTypeFromJSON(?string $value): ?string
   {
      if ($value === null) return null;
      $value = $this->decode($value, false);
      if ($this->hasError()) return null;
      return \gettype($value);
   }

   function getLastErrorMsg(): string
   {
      return \json_last_error_msg();
   }

   function getLastErrorCode(): int
   {
      return \json_last_error();
   }

   function hasError(): bool
   {
      return \json_last_error() !== \JSON_ERROR_NONE;
   }

   // ------------------------------------------------------------------
   // 
   // ------------------------------------------------------------------

   /**
    * @return mixed
    */
   function decode(
      string $value,
      ?bool $associative = null,
      int $depth         = 512,
      int $flags         = 0
   ) {
      return \json_decode($value, $associative, $depth, $flags);
   }

   /**
    * @param mixed $value
    */
   function encode($value, int $flags = 0, int $depth = 512): string|false
   {
      return \json_encode($value, $flags, $depth);
   }

   /**
    * the method does not throw exceptions JsonException, instead it returns the default value
    * 
    * @template T
    * @param T $default
    * @return mixed|T
    */
   function tryDecode(
      string $value,
      ?bool $associative = null,
      int $depth         = 512,
      int $flags         = 0,
      $default           = null,
   ) {
      try {
         $value = \json_decode($value, $associative, $depth, $flags);
      } catch (\JsonException) {
         return $default;
      }
      if ($this->hasError()) {
         return $default;
      }
      return $value;
   }

   /**
    * the method does not throw exceptions JsonException, instead it returns the default value
    * 
    * @template T
    * @param T $default return default if failed encode
    * @param mixed $value
    * @return string|T
    */
   function tryEncode($value, int $flags = 0, int $depth = 512, $default = null)
   {
      try {
         $value = \json_encode($value, $flags, $depth);
      } catch (\JsonException) {
         return $default;
      }
      if ($value === false) {
         return $default;
      }
      return $value;
   }
}

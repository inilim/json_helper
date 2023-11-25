<?php

namespace Inilim;

class JSON
{
   public function isJSON(string $value): bool
   {
      json_decode($value);
      return !$this->hasError();
   }

   public function isJSONAsArray(string $value): bool
   {
      $decode = json_decode($value, true);
      if ($this->hasError()) return false;
      return is_array($decode);
   }

   // ------------------------------------------------------------------
   // protected
   // ------------------------------------------------------------------

   protected function hasError(): bool
   {
      return json_last_error() !== JSON_ERROR_NONE;
   }
}

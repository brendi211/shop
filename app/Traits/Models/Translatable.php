<?php

namespace App\Traits\Models;

trait Translatable
{
    public function __get($field)
    {
        $lang = config('locale.current');

        if (isset($this->translate) && in_array($field, $this->translate)) {
            if ($this->checkNeededTranslatable($field)) {
                return $this->translateOtherFields($field);
            }

            return $this->{"{$field}_{$lang}"};
        }

        return parent::__get($field);
    }

    private function checkNeededTranslatable($field)
    {
        $lang = config('locale.current');
        $default = config('locale.default');

        return (is_null($this->{"{$field}_{$lang}"}) || empty($this->{"{$field}_{$lang}"})) && !empty($this->{"{$field}_{$default}"});
    }

    private function translateOtherFields(string $field): ?string
    {
        $lang = config('locale.current');
        $default = config('locale.default');

        if ($lang == $default) {
            return null;
        }

        if (is_null($this->{"{$field}_{$lang}"}) || empty($this->{"{$field}_{$lang}"}) && !is_null($this->{"{$field}_{$default}"})) {
            $this->{"{$field}_{$lang}"} = translate_text($this->{"{$field}_$default"}, $lang);
            $this->save();
        }

        return $this->{"{$field}_{$lang}"};
    }
}
<?php namespace Impl\Repo;

abstract class RepoAbstract {

    /**
     * Bir değişkeni URL için "slug-friendly/slug-dostu" yap
     * @param  string $string  İnsan-dostu tag'ı
     * @return string       Bilgisayar-dostu tag'ı
     */
    protected function slug($string)
    {
        return filter_var( str_replace(' ', '-', strtolower( trim($string) ) ), FILTER_SANITIZE_URL);
    }

}
<?php namespace Impl\Repo\Tag;

interface TagInterface {

    /**
     * Mevcut tag'ları bul veya mevcut değilse oluştur
     *
     * @param  array $tags  Değişlkenler dizisi, her biri bir tag'ı ifade eder
     * @return array        Tag nesnesi Arrayable collection'ı dizisi
     */
    public function findOrCreate(array $tags);

}
<?php namespace Impl\Repo\Article;

interface ArticleInterface {

    /**
     * Status'a kayıtsız kalarak
     * ID'ye göre tek bir makale getirir
     *
     * @param  int $id Makale ID
     * @return stdObject Makale bilgisi nesnesi
     */
    public function byId($id);

    /**
     * Sayfalandırılmış makaleleri getirir
     *
     * @param int  Sayfa başına makale sayısı
     * @return StdClass Sayfalama için $items ve $totalItems'den oluşan nesne
     */
    public function byPage($page=1, $limit=10);

    /**
     * URL'ye göre tek bir makale getirir
     *
     * @param string  Makalenin URL slug'ı
     * @return object   Makale bilgisinden oluşan nesne
     */
    public function bySlug($slug);

   /**
     * @param string  Tagın URL slug'ı
     * @param int  Geçerli sayfa
     * @param int  Sayfa başına makale sayısı
     * @return object  Sayfalama için $items ve $totalItems'den oluşan nesne
     */
    public function byTag($tag, $page=1, $limit=10);

    /**
     * Yeni bir makale oluşturur
     *
     * @param array  Yeni bir nesne oluşturma verisi
     * @return boolean
     */
    public function create(array $data);

    /**
     * Mevcut bir makaleyi günceller
     *
     * @param array  Bir makaleyi güncelleme verisi
     * @return boolean
     */
    public function update(array $data);

}
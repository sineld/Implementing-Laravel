<?php namespace Impl\Service\Cache;

interface CacheInterface {

    /**
     * Cache'den veriyi getir
     *
     * @param string    Cache öğesinin keyi
     * @return mixed    Cache'deki PHP veri sonucu
     */
    public function get($key);

    /**
     * Cache'e veri ekle
     *
     * @param string    Cache öğesinin keyi
     * @param mixed     Saklanacak veri
     * @param integer   Öğenin saklanacağı dakika sayısı
     * @return mixed    Kolaylık için döndürülen $value değişkeni
     */
    public function put($key, $value, $minutes=null);

    /**
     * Sayfalamayı dikkate alarak
     * cache'e veri ekle
     *
     * @param integer   Önbelleklenmiş öğelerin sayfası
     * @param integer   Sayfa başına sonuç sayısı
     * @param integer   Olası öğelerin toplam sayısı
     * @param mixed     Bu sayfadaki öğeler
     * @param string    Cache öğesi keyi
     * @param integer   Öğenin saklanacağı dakika sayısı
     * @return mixed    Kolaylık için döndürülen $items değişkeni
     */
    public function putPaginated($currentPage, $perPage, $totalItems, $items, $key, $minutes=null);

    /**
     * Cache'de öğe var mı yok mu test eder
     * Sadece, eğer varsa ve zamanı dolmamışsa döndürür
     *
     * @param string    Cache öğesi keyi
     * @return bool     Cache öğesinin mevcut olup olmadığı
     */
    public function has($key);

}
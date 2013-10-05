<?php namespace Impl\Service\Cache;

use Illuminate\Cache\CacheManager;

class LaravelCache implements CacheInterface {

    protected $cache;
    protected $cachekey;
    protected $minutes;

    public function __construct(CacheManager $cache, $cachekey, $minutes=null)
    {
        $this->cache = $cache;
        $this->cachekey = $cachekey;
        $this->minutes = $minutes;
    }

    /**
     * Cache'den veriyi getir
     *
     * @param string    Cache öğesinin keyi
     * @return mixed    Cache'deki PHP veri sonucu
     */
    public function get($key)
    {
        return $this->cache->section($this->cachekey)->get($key);
    }

    /**
     * Cache'e veri ekle
     *
     * @param string    Cache öğesinin keyi
     * @param mixed     Saklanacak veri
     * @param integer   Öğenin saklanacağı dakika sayısı
     * @return mixed    Kolaylık için döndürülen $value değişkeni
     */
    public function put($key, $value, $minutes=null)
    {
        if( is_null($minutes) )
        {
            $minutes = $this->minutes;
        }

        return $this->cache->section($this->cachekey)->put($key, $value, $minutes);
    }

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
    public function putPaginated($currentPage, $perPage, $totalItems, $items, $key, $minutes=null)
    {
        $cached = new \StdClass;

        $cached->currentPage = $currentPage;
        $cached->items = $items;
        $cached->totalItems = $totalItems;
        $cached->perPage = $perPage;

        $this->put($key, $cached, $minutes);

        return $cached;
    }

    /**
     * Cache'de öğe var mı yok mu test eder
     * Sadece, eğer varsa ve zamanı dolmamışsa döndürür
     *
     * @param string    Cache öğesi keyi
     * @return bool     Cache öğesinin mevcut olup olmadığı
     */
    public function has($key)
    {
        return $this->cache->section($this->cachekey)->has($key);
    }

}
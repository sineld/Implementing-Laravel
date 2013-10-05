<?php namespace Impl\Repo\Status;

interface StatusInterface {

    /**
     * Tüm Status'ları getir
     * @return Array Arrayable collection
     */
    public function all();

    /**
     * Belirli bir  status'u getir
     * @param  int $id Status ID
     * @return object  Status nesnesi
     */
    public function byId($id);

    /**
     * Belirli bir  status'u getir
     * @param  int $id Status slug
     * @return object  Status nesnesi
     */
    public function byStatus($status);

}
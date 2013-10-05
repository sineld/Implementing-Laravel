<?php namespace Impl\Repo\Status;

use Impl\Repo\RepoAbstract;
use Illuminate\Database\Eloquent\Model;

class EloquentStatus extends RepoAbstract implements StatusInterface {

    protected $status;

    public function __construct(Model $status)
    {
        $this->status = $status;
    }

    /**
     * Tüm Status'ları getir
     * @return Array Arrayable collection
     */
    public function all()
    {
        return $this->status->all();
    }

    /**
     * Belirli bir  status'u getir
     * @param  int $id Status ID
     * @return object  Status nesnesi
     */
    public function byId($id)
    {
        return $this->status->find($id);
    }

    /**
     * Belirli bir  status'u getir
     * @param  int $id Status slug'ı
     * @return object  Status nesnesi
     */
    public function byStatus($status)
    {
        return $this->status->where('slug', $status);
    }

}
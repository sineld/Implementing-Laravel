<?php

class Article extends Eloquent {

    /**
     * Model tarafından kullanılan veritabanı tablosu.
     *
     * @var string
     */
    protected $table = 'articles';

    /**
     * Mass assign edilebilecek özellikler.
     *
     * @var array
     */
    protected $fillable = array(
        'user_id',
        'status_id',
        'title',
        'slug',
        'excerpt',
        'content',
    );

    /**
     * Model'in yumuşak silme kullanıp kullanamayacağı.
     *
     * @var bool
     */
    protected $softDelete = true;

    /**
     * Tekten teke ilişki tanımla.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function author()
    {
        return $this->belongsTo('User');
    }

    /**
     * Tekten teke ilişki tanımla.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function status()
    {
        return $this->belongsTo('Status');
    }

    /**
     * Çoktan çoğa ilişki tanımla.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany('Tag', 'articles_tags', 'article_id', 'tag_id');
    }

}
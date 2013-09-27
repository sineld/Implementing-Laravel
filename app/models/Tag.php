<?php

class Tag extends Eloquent {

    /**
     * Model tarafından kullanılan veritabanı tablosu.
     *
     * @var string
     */
    protected $table = 'tags';



    /**
     * Mass assign edilebilecek özellikler.
     *
     * @var array
     */
    protected $fillable = array(
        'tag',
        'slug',
    );

    /**
     * Model'in timestamp kullanıp kullanamayacağı.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Çoktan çoğa ilişki tanımla.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function articles()
    {
        return $this->belongsToMany('Article', 'articles_tags', 'tag_id', 'article_id');
    }

}
<?php

class Status extends Eloquent {

    /**
     * Model tarafından kullanılan veritabanı tablosu.
     *
     * @var string
     */
    protected $table = 'statuses';

    /**
     * Mass assign edilebilecek özellikler.
     *
     * @var array
     */
    protected $fillable = array(
        'status',
        'slug',
    );

}
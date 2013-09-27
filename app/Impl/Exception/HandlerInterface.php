<?php namespace Impl\Exception;

interface HandlerInterface {

    /**
     * Impl İstisnalarını İşle
     *
     * @param \Impl\Exception\ImplException
     * @return void
     */
    public function handle(ImplException $exception);

}
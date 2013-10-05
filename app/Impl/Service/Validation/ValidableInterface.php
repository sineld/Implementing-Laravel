<?php namespace Impl\Service\Validation;

interface ValidableInterface {

    /**
     * Geçerlilik denetimi yapılacak verileri ekle
     *
     * @param array
     * @return \Impl\Service\Validation\ValidableInterface  $this
     */
    public function with(array $input);

    /**
     * Geçerlilik denetiminden geçip/kalındığını test et
     *
     * @return boolean
     */
    public function passes();

    /**
     * Validation hatalarını getir
     *
     * @return array
     */
    public function errors();

}
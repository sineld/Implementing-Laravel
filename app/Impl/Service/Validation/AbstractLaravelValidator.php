<?php namespace Impl\Service\Validation;

use Illuminate\Validation\Factory;

abstract class AbstractLaravelValidator implements ValidableInterface {

    /**
     * Validator
     *
     * @var \Illuminate\Validation\Factory
     */
    protected $validator;

    /**
     * anahtar => değer dizisi şeklinde validation verisi
     *
     * @var Array
     */
    protected $data = array();

    /**
     * Validation hataları
     *
     * @var Array
     */
    protected $errors = array();

    /**
     * Validation kuralları
     *
     * @var Array
     */
    protected $rules = array();

    public function __construct(Factory $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Geçerlilik denetiminden geçirilecek veriyi ayarla
     *
     * @return \Impl\Service\Validation\AbstractLaravelValidator
     */
    public function with(array $data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Validation geçer veya kalır
     *
     * @return Boolean
     */
    public function passes()
    {
        $validator = $this->validator->make($this->data, $this->rules);

        if( $validator->fails() )
        {
            $this->errors = $validator->messages();
            return false;
        }

        return true;
    }

    /**
     * Varsa, hataları döndürür
     *
     * @return array
     */
    public function errors()
    {
        return $this->errors;
    }

}
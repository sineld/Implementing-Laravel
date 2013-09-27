<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	/**
     * Model tarafından kullanılan veritabanı tablosu.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * Modelin JSON formundan hariç tutulacak özellikler
	 *
	 * @var array
	 */
	protected $hidden = array('password');

	/**
	 * Kullanıcıya ait benzersiz tanımlayıcıyı getir
	 *
	 * @return mixed
	 */
	public function getAuthIdentifier()
	{
		return $this->getKey();
	}

	/**
	 * Kullanıcıya ait şifreyi getir
	 *
	 * @return string
	 */
	public function getAuthPassword()
	{
		return $this->password;
	}

	/**
	 * Şifre anımsatıcıların gönderildiği e-mail adresini getir.
	 *
	 * @return string
	 */
	public function getReminderEmail()
	{
		return $this->email;
	}

}
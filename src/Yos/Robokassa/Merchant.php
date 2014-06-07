<?php namespace Yos\Robokassa;

class Merchant {

    /**
     * @var string
     */
    protected $login;

    /**
     * @var string
     */
    protected $password1;

    /**
     * @var string
     */
    protected $password2;

    /**
     * @var string
     */
    protected $baseUrl = 'https://merchant.roboxchange.com/Index.aspx?';

    /**
     * @param array $merchantCredentials
     * @param bool $test
     */
    function __construct($merchantCredentials, $test = false)
    {
        $this->login = $merchantCredentials['login'];
        $this->password1 = $merchantCredentials['password1'];
        $this->password2 = $merchantCredentials['password2'];

        if ($test) $this->baseUrl = 'http://test.robokassa.ru/Index.aspx?';
    }

    /**
     * Get login of a merchant
     *
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Get first password1 of a merchant
     *
     * @return string
     */
    public function getFirstPassword()
    {
        return $this->password1;
    }

    /**
     * Get second password of a merchant
     *
     * @return string
     */
    public function getSecondPassword()
    {
        return $this->password2;
    }
}
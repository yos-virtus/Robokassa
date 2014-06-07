<?php namespace Yos\Robokassa;

use Yos\Robokassa\Exceptions\NonExistentPaymentStageException;

class Payment {

    /**
     * Stage initialize
     */
    const INIT = 'init';

    /**
     * Stage result
     */
    const RESULT = 'result';

    /**
     * Stage success
     */
    const SUCCESS = 'success';

    /**
     * @var string
     */
    protected $paymentStage = self::INIT;

    /**
     * @param Merchant $merchant
     */
    function __construct(Merchant $merchant)
    {
        $this->merchant = $merchant;
    }

    /**
     * Get payment stage
     *
     * @return string
     */
    public function getPaymentStage()
    {
        return $this->paymentStage;
    }

    /**
     * Set payment stage
     *
     * @param int $stage
     * @throws Exceptions\NonExistentPaymentStageException
     */
    public function setPaymentStage($stage)
    {
        switch ($stage)
        {
            case self::INIT:
                $this->paymentStage = self::INIT;
                break;

            case self::RESULT:
                $this->paymentStage = self::RESULT;
                break;

            case self::SUCCESS:
                $this->paymentStage = self::SUCCESS;
                break;

            default:
                throw new NonExistentPaymentStageException;
        }
    }

    /**
     * Build signature depending on payment stage
     *
     * @param float $outSum
     * @param int $invoiceId
     * @param array|bool $customParams
     * @return string
     */
    public function buildSignature($outSum, $invoiceId, $customParams=false)
    {
        $this->paymentStage != self::INIT ?: $crcFields[] = $this->merchant->getLogin();
        $crcFields[] = $outSum;
        $crcFields[] = $invoiceId;
        $crcFields[] = $this->paymentStage == self::RESULT ? $this->merchant->getSecondPassword(): $this->merchant->getFirstPassword() ;

        if($customParams != false)
        {
            $customParamsArray = $this->prepareCustomParameters($customParams);
            $crcFields = array_merge($crcFields, $customParamsArray);
        }

        return $this->makeHash($crcFields);
    }

    /**
     * Check the initials hash and the respond hash
     *
     * @param $data
     * @return bool
     */
    public function validateSignature($data)
    {
        $customParams = $data;

        unset($customParams['OutSum'], $customParams['InvId'], $customParams['SignatureValue']);

        $currentSignature = $this->buildSignature($data['OutSum'], $data['InvId'], $customParams);

        return ($data['SignatureValue'] == $currentSignature) ? true : false;
    }

    /**
     * Prepare parameters for to be hashed string
     *
     * @param array $customParams
     * @return array
     */
    protected function prepareCustomParameters($customParams)
    {
        ksort($customParams);

        foreach ($customParams as $key => $value)
        {
            $crcFields[] = $key . '=' . $value;
        }

        return $crcFields;
    }

    /**
     * Create signature hash from prepared parameters array
     *
     * @param array $crcFields
     * @return string
     */
    protected function makeHash($crcFields)
    {
        return strtolower(md5(implode(':', $crcFields)));
    }

} 
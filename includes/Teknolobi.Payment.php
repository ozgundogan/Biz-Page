<?php

Class PaymentOperations extends Teknolobi
{
    public $paytype;
    function __construct($paytype)
    {
        $this->paytype = $paytype;
        if($paytype == "paypal") {
            require(__DIR__ . '/plugins/PayPal/bootstrap.php');
            $payer = new Payer();
        }
    }
}
?>
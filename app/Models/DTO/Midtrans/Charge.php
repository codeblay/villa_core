<?php

namespace App\Models\DTO\Midtrans;

final class Charge
{
    const PAYMENT_TYPE_QRIS             = 'qris';
    const PAYMENT_TYPE_ECHANNEL         = 'echannel';
    const PAYMENT_TYPE_BANK_TRANSFER    = 'bank_transfer';
    
    const PAYMENT = [
        self::PAYMENT_TYPE_QRIS,
        self::PAYMENT_TYPE_ECHANNEL,
        self::PAYMENT_TYPE_BANK_TRANSFER,
    ];

    public string                   $payment_type;
    public ChargeTransactionDetails $transaction_details;
    public ChargeCustomerDetails    $customer_details;
    public ChargeBankTransfer       $bank_transfer;
    public ChargeEchannel           $echannel;
    public array                    $item_details;
}

final class ChargeTransactionDetails
{
    public string   $order_id;
    public int      $gross_amount;
}

final class ChargeCustomerDetails
{
    public string   $first_name;
    public string   $email;
    public string   $phone;
}

final class ChargeItemDetails
{
    public string   $name;
    public int      $price;
    public int      $quantity;
}

final class ChargeBankTransfer
{

    const BCA       = 'bca';
    const BRI       = 'bri';
    const PERMATA   = 'permata';
    const CIMB      = 'cimb';
    
    public string   $bank;
}

final class ChargeEchannel
{   
    public string $bill_info1;
    public string $bill_info2;
}

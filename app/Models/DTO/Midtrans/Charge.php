<?php

namespace App\Models\DTO\Midtrans;

final class Charge
{
    const PAYMENT_TYPE_QRIS             = 'qris';
    const PAYMENT_TYPE_GOPAY            = 'gopay';
    const PAYMENT_TYPE_BANK_TRANSFER    = 'bank_transfer';

    public string                   $payment_type;
    public ChargeTransactionDetails $transaction_details;
    public ChargeCustomerDetails    $customer_details;
    public ChargeBankTransfer       $bank_transfer;
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

final class ChargeBankTransfer
{

    const BCA = 'bca';
    const BRI = 'bri';
    const BNI = 'bni';
    
    public string   $bank;
    public string   $va_number;
}

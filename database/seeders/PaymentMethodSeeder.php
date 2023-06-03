<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    protected $paymentMethodsActive = [
        "Paypal",
        "Zain Cash",
         "Cash"
    ];
    public function run()
    {
        foreach ($this->paymentMethodsActive as $method){
            $payment = new PaymentMethod();
            $payment->name_en = $method;
            $payment->name_ar = __($method, [], 'ar');
            $payment->flag = str_replace(" ", "-",strtolower($method));
            $payment->save();
        }
    }
}

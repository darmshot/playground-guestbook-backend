<?php

namespace App\Console\Commands;

use App\Models\Payment;
use Illuminate\Console\Command;

class PaymentReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payment:report
    {--date=}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $payments = Payment::query()
            ->with(['loan'])
            ->whereDate('payment_date', $this->option('date'))
            ->get()->map(fn(Payment $item) => [
                'loan' => $item->loan?->reference,
                'amount_to_pay' =>$item->loan?->amount_to_pay,
                'payer' => "$item->payer_name $item->payer_surname",
                ...array_intersect_key($item->toArray(),array_flip(['payment_date','amount','status','description'])),
            ])->toArray();

        $this->table([
            'loan' => 'Loan',
            'amount_to_pay' => 'Amount to pay',
            'payer' => 'Payer',
            'payment_date' => 'Payment date',
            'amount' => 'Amount',
            'status' => 'Status',
            'description' => 'Description'
        ], $payments);
    }
}

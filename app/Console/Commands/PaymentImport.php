<?php

namespace App\Console\Commands;

use App\Contracts\PaymentContract;
use App\Data\Payment\PaymentRowCSV;
use App\Data\Payment\PayPayload;
use Illuminate\Console\Command;
use Illuminate\Contracts\Console\Isolatable;
use Illuminate\Support\Facades\Storage;

class PaymentImport extends Command implements Isolatable
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payment:import
     {--file=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function __construct(protected PaymentContract $payment)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $path = $this->option('file');

        if (! Storage::disk('public')->exists($path)) {
            $this->error('File is not found.');

            return 3;
        }

        $code = $this->call('payment:check', [
            '--file' => $path,
        ]);

        if ($code) {
            return $code;
        }

        $this->import();

        return 0;
    }

    protected function import(): void
    {
        $handle = Storage::disk('public')->readStream($this->option('file'));

        $row = 1;
        while (($data = fgetcsv($handle, 1000, ',')) !== false) {

            if ($row === 1) {
                $row++;

                continue;
            }

            $paymentRowCSV = new PaymentRowCSV(
                payment_date: $data[0],
                payer_name: $data[1],
                payer_surname: $data[2],
                amount: (float) $data[3],
                description: $data[5],
                national_security_number: $data[4],
                payment_reference: $data[6],
            );

            $this->payment->pay(PayPayload::fromPaymentRowCSV($paymentRowCSV));

            $row++;
        }

        fclose($handle);
    }
}

<?php

namespace App\Console\Commands;

use App\Contracts\PaymentContract;
use App\Rules\DescriptionHasLoan;
use Illuminate\Console\Command;
use Illuminate\Contracts\Console\Isolatable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PaymentCheck extends Command implements Isolatable
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payment:check
     {--file=}
     {--first-fail : Check before first fail.}
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
    protected int $errorCount = 0;

    private bool $firstFail = false;

    public function __construct(protected PaymentContract $payment)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        if (! Storage::disk('public')->exists($this->option('file'))) {
            $this->error('File is not found.');

            return 3;
        }

        $this->firstFail = $this->hasOption('first-fail');

        $exitCode = $this->check();

        return $exitCode;
    }

    /**
     * 0 - fine
     * 1 - duplicate entity
     * 3 - not valid
     *
     * @return int - exit code
     */
    protected function check(): int
    {
        Cache::tags('payment_import')->flush();
        $code = 0;

        $handle = Storage::disk('public')->readStream($this->option('file'));
        $row = 1;
        while (($data = fgetcsv($handle, 1000, ',')) !== false) {

            if ($row === 1) {
                $row++;

                continue;
            }

            $associateData = $this->associateData($data);

            if ($this->errorCount > 100) {
                $this->error('Too much errors.');
                break;
            }

            if ($row > 100_000) {
                $this->error('Too much rows. Break of parts not more 100.000 rows.');
                $code = 3;
                break;
            }

            $this->validate($associateData, $row, $code);

            if ($this->firstFail && $this->errorCount > 0) {
                break;
            }

            $row++;
        }

        fclose($handle);

        Cache::tags('payment_import')->flush();

        return $code;
    }

    protected function associateData(array $data): array
    {
        return [
            'payment_date' => $data[0],
            'payer_name' => $data[1],
            'payer_surname' => $data[2],
            'amount' => $data[3],
            'national_security_number' => $data[4],
            'description' => $data[5],
            'payment_reference' => $data[6],
        ];
    }

    protected function validate(array $associateData, $row, &$code = 0): void
    {
        $validate = Validator::make($associateData, [
            'payment_date' => 'date_format:YdmHis',
            'payer_name' => 'string|required',
            'payer_surname' => 'string|required',
            'amount' => 'decimal:2|required|min:0.01|max:100000',
            'national_security_number' => 'string|min:10|max:10|nullable',
            'description' => ['string', 'required', new DescriptionHasLoan],
            'payment_reference' => 'string|nullable|max:36|unique:App\Models\Payment,payment_reference',
        ]);

        if (Cache::tags('payment_import')->has($associateData['payment_reference'])) {
            $this->errorCount++;
            $this->error("Row: $row, payment_reference: Duplicate value.");
            $code = 1;
        } elseif (empty($associateData['payment_reference']) === false && $validate->errors()->has('payment_reference') === false) {
            Cache::tags('payment_import')->put($associateData['payment_reference'], '1');
        }

        foreach ($validate->errors()->getMessages() as $field => $errors) {
            $this->errorCount++;
            $code = 3;
            $this->error("Row: $row, $field: ".implode('|', $errors));
        }
    }
}

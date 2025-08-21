<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Challenge_type;
use App\Models\Countries;
use App\Models\Random_payout;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Events\RecentPayoutEvent;

class CreateRandomPayout extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-random-payout';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create random payout';

    /**
     * Execute the console command.
     */
    public function handle()
    {
		//\Log::info('Random payout triggered:'.date('Y-m-d H:i:s'));
		
		// 1. Generate a random login id
		$login_id = mt_rand(1000000000, 9999999999);
		
		// 2. Generate status
		$status = 'Paid';
		
		// 3. Get a random account size
        $account = Challenge_type::inRandomOrder()->first();
		$account_size = $account->title;
		$account_amount = $account->amount;
		
		// 4. Generate Profits
		$profit = $this->generateBiasedProfit($account_amount);
		
		// 5. Get a random country
        $country = Countries::inRandomOrder()->first();
		$country_name = $country->name;
		
		// 6. Insert in database
		$payout = new Random_payout();
		$payout->login_id = $login_id;
		$payout->status = $status;
		$payout->account_size = $account_size;
		$payout->account_amount = $account_amount;
		$payout->profit = $profit;
		$payout->country_name = $country_name;
		$payout->save();
		
		$notification_message = 'Trader '.$login_id.' just got a payout for '.number_format($profit, 2, '.', ',').get_currency_symbol().' with a '.$account_size.' account! Congratulations!';
		event(new RecentPayoutEvent($notification_message));
    }
	
	private function generateBiasedProfit(float|int $accountAmount)
	{
		// Ensure upper limit never exceeds account size
		$lowMax  = min($accountAmount, 5000);
		$highMax = min($accountAmount, 40000);

		// 80% chance for low profit, 20% for higher (but still ≤ account size)
		return rand(1, 100) <= 80
			? rand(100, $lowMax)
			: rand($lowMax + 1, $highMax);
	}
}


?>
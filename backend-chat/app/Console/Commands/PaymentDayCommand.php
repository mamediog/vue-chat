<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Client;
use App\Models\Project;
use App\Mail\AnyMail;
use Illuminate\Support\Facades\Mail;

class PaymentDayCommand extends Command
{
  protected $signature = 'payment:run';
  protected $description = 'Roda pagamento de todos os clientes';

  protected $rules = [
    [
      'min' => 0,
      'max' => 99,
      'price' => 0,
      'extra' => 0.99
    ],
    [
      'min' => 100,
      'max' => 499,
      'price' => 99,
      'extra' => 0.99
    ],
    [
      'min' => 500,
      'max' => 999,
      'price' => 149,
      'extra' => 0.29
    ]
  ];

  public function __construct()
  {
    parent::__construct();
  }

  public function handle()
  {
    $clients = Client::where('owners.type', 'cli')->get();
    // $pagarme = new \PagarMe\Client(env('PAGARME_KEY'));
    foreach ($clients as $client) {
      $owners = array_filter($client->owners, function ($own) {
        return $own['type'] === 'cli';
      });

      $projects = Project::where('payment', '!=', null)->where(function ($query) use ($owners) {
        foreach ($owners as $own) {
          $query->orWhere('_id', $own['project']);
        }
      })->get();


      $transactions = [];
      foreach ($projects as $project) {
        if (!isset($project['plan'])) {
          continue;
        }
        $numOfSales = isset($project->sales) ? $project->sales : rand(100, 120);
        $plan = $project->plan;
        $plan = $this->rules[$plan];
        $total = $plan['price'];

        if ($numOfSales > $plan['max']) {
          $total += ($numOfSales - $plan['max']) * $plan['extra'];
        }

        if ($total > 0) {
          if (!isset($transactions[$project->payment])) {
            $transactions[$project->payment] = [
              'items' => [],
              'total' => 0
            ];
          }
          $transactions[$project->payment]['items'][] = [
            'id' => $project->_id,
            'title' => 'PWA4All ' . $project->config['project']['display'],
            'unit_price' => $total * 100,
            'quantity' => 1,
            'tangible' => false
          ];

          $transactions[$project->payment]['total'] += $total;
        }


        $this->info($project->name . ' teve ' . $numOfSales . ' vendas no plano de ' . $plan['price'] . ' reais(' . $plan['max'] . ' vendas) cobramos um total de:' .$total);
      }


      if (count($transactions) > 0) {
        $pagarme = new \PagarMe\Client(env('PAGARME_KEY'));

        foreach ($transactions as $cardid => $transaction) {

          if ($transaction['total'] <= 0) {
            foreach ($transaction['items'] as $item) {
              $project = Project::find($item['id']);
              $project->sales = 0;
              $project->payed = true;
              $project->save();
            }

            Mail::to($client->email)->send(new AnyMail('Plano gratuito renovado [PWA4all]', 'Como você não estourou o limite de vendas do plano gratuito não houve cobrança alguma, renovamos o mesmo plano para o próximo mês', ''));       
            $this->info('plano grátis');
            continue;
          }
          $transactionResult = $pagarme->transactions()->create([
            'amount' => $transaction['total'] * 100,
            // 'card_id' => $cardid,
            'card_holder_name' => 'LEONARDO V SOUZA',
            'card_expiration_date' => '1220',
            'card_cvv' => '651',
            'card_number' => '5444 1336 6904 8128',
            'payment_method' => 'credit_card',
            'async' => false,
            'customer' => [
              'id' => $client->pagarme,
              'external_id' => $client->_id,
              'name' => $client->name, 
              'email' => $client->email,
              'type' => 'corporation',
              'country' => 'br',
              'documents' => [
                [
                  'type' => 'cnpj',
                  'number' => $client->cnpj
                ]
              ],
              'phone_numbers' => [ '+55'.str_replace('-', '', str_replace(' ', '', $client->phone)) ]
            ],
            'items' => $transaction['items'],
            'billing' => [
              'name' => $client->name,
              'address' => [
                'country' => 'br',
                'street' => 'Não informado',
                'street_number' => 'Não informado',
                'state' => 'sp',
                'city' => 'Não informado',
                'neighborhood' => 'Não informado',
                'zipcode' => '00000000'
              ]
            ]
          ]);

          $emailItems = array_reduce($transaction['items'], function ($ac, $it) {
            return $ac . '<strong>' . $it['title'] . '</strong> por R$' . ($it['unit_price']/100) . '<br/>';
          }, '');

          if ($transactionResult->status === 'paid') {
            foreach ($transaction['items'] as $item) {
              $project = Project::find($item['id']);
              $project->sales = 0;
              $project->payed = true;
              $project->save();
            }
            Mail::to($client->email)->send(new AnyMail('O pagamento do seu PWA4All foi efetivado!', 'Veja a seguir a cobrança desse mês:<br>'.$emailItems, ''));
            $this->info('pago');
          } else {
            foreach ($transaction['items'] as $item) {
              $project = Project::find($item['id']);
              $project->payed = false;
              // $project->payment = null;
              $project->off_days = 5;
              $project->save();

              exec('(crontab -u web2 -l ; echo "0 3 * * * php '.__DIR__.'/../../../artisan payment:off ' . $item['id'] . '") | crontab -u web2 -');
            }

            Mail::to($client->email)->send(new AnyMail('Erro no pagamento do seu PWA4All', 'Evite o cancelamento da(s) sua(s) loja(s) efetuando o pagamento dos seguintes items em até 5 dias:<br>'.$emailItems, ''));
            $this->info('falha ao pagar');
          }
        }
      }
    }
  }
}
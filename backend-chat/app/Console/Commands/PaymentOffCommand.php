<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Client;
use App\Models\Project;
use App\Mail\AnyMail;
use Illuminate\Support\Facades\Mail;
use Aws\S3\S3Client;
use Aws\S3\Transfer;

class PaymentOffCommand extends Command
{
  protected $signature = 'payment:off {project-id}';
  protected $description = 'Roda retirar o site do ar caso o pagamento não tneha sido efetuado';

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

  public function getUrlKey($project)
  {
    $url = $project->config['project']['url'];
    $url = str_replace('http://', '', $url);
    $url = str_replace('https://', '', $url);
    $url = str_replace('www.', '', $url);
    $url = str_replace('/', '', $url);
    $url = str_replace('.', '_', $url);

    return $url;
  }

  public function handle()
  {
    $id = $this->argument('project-id');
    $project = Project::find($id);
    $clients = Client::where('owners.project', $id)->get();
    $client = null;

    foreach ($clients as $member) {
      foreach ($member['owners'] as $own) {
        if ($own['project'] === $id and $own['type'] === 'cli') {
          $client = $member;
          break;
        }
      }
    }

    if (!$project->payed) {
      $pagarme = new \PagarMe\Client(env('PAGARME_KEY'));
      $numOfSales = isset($project->sales) ? $project->sales : rand(100, 180);
      $plan = $project->plan;
      $plan = $this->rules[$plan];
      $total = $plan['price'];

      if ($numOfSales > $plan['max']) {
        $total += ($numOfSales - $plan['max']) * $plan['extra'];
      }

      if ($total <= 0) {
        $project->sales = 0;
        $project->payed = true;
        $project->save();
        Mail::to($client->email)->send(new AnyMail('Plano gratuito renovado [PWA4all]', 'Como você não estourou o limite de vendas do plano gratuito não houve cobrança alguma, renovamos o mesmo plano para o próximo mês', ''));       
        $this->info('plano grátis');
        exit;
      }

      $items = [[
        'id' => $project->_id,
        'title' => 'PWA4All ' . $project->config['project']['display'],
        'unit_price' => $total * 100,
        'quantity' => 1,
        'tangible' => false
      ]];

      $cardObj = $project->off_days < 3 ? [ 'card_id' => $project->payment ] : [
        'card_holder_name' => 'LEONARDO V SOUZA',
        'card_expiration_date' => '1220',
        'card_cvv' => '651',
        'card_number' => '5444 1336 6904 8128'
      ];


      $transactionResult = $pagarme->transactions()->create(array_merge($cardObj, [
        'amount' => $total * 100,
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
        'items' => $items,
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
      ]));

      $emailItems = array_reduce($items, function ($ac, $it) {
        return $ac . '<strong>' . $it['title'] . '</strong> por R$' . ($it['unit_price']/100) . '<br/>';
      }, '');

      if ($transactionResult->status === 'paid') {
        $project->sales = 0;
        $project->payed = true;
        $project->save();
        exec("crontab -u web2 -l | grep -v 'php ".__DIR__."/../../../artisan payment:off {$project->_id}'  | crontab -u web2 -");
        Mail::to($client->email)->send(new AnyMail('O pagamento do seu PWA4All foi efetivado!', 'Veja a seguir a cobrança desse mês:<br>'.$emailItems, ''));
        $this->info('pago');
      } else {
        $project->payed = false;
        $project->off_days--;
        $project->save();

        if ($project->off_days > 0) {
          Mail::to($client->email)->send(new AnyMail('Erro no pagamento do seu PWA4All', 'Evite o cancelamento de '.$project->config['project']['display'].' efetuando o pagamento dos seguintes items em até '.$project->off_days.' dias:<br>'.$emailItems, ''));
        }

        $this->info('falha ao pagar');
      }

      if ($project->off_days <= 0) {
        $project->payment = null;
        $project->save();

        $s3 = new S3Client([
          'version' => 'latest',
          'region' => 'sa-east-1',
          'credentials' => [
            'key' => env('AWS_ACCESS_KEY', ''),
            'secret' => env('AWS_SECRET_KEY', ''),
          ],
        ]);
        $bucketName = env('AWS_BUCKET_S3', '');
        $projectFolder =  $this->getUrlKey($project);
        $bucketPath =  's3://' . $bucketName . '/' . $projectFolder;
        $s3->deleteMatchingObjects($bucketName, $projectFolder);

        exec("crontab -u web2 -l | grep -v 'php ".__DIR__."/../../../artisan payment:off {$project->_id}'  | crontab -u web2 -");

        Mail::to($client->email)->send(new AnyMail('Seu PWA4All foi retirado do ar', 'Olá, infelizmente por falta de pagamente tivemos de retirar o seu projeto do ar, entre no dashboard e verifique seu dados de pagamento para realizar um novo deploy.', ''));
      }     
    }
  }
}
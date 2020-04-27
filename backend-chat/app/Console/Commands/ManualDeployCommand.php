<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Client;
use App\Models\Project;
use App\Mail\AnyMail;
use Illuminate\Support\Facades\Mail;
use Aws\S3\S3Client;
use Aws\S3\Transfer;
use App\Dashboard\Transpile\Transpiler;
use App\Dashboard\Manager;

class ManualDeployCommand extends Command
{
  protected $signature = 'manual:deploy {user-id} {project-id}';
  protected $description = 'Da deploy manual em uma pasta';

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
    $project = Project::find($this->argument('project-id'));
    $dir = Manager::storageFolder('transpile', $project['name'], $this->argument('user-id'));
    $client = new S3Client([
      'version'     => 'latest',
      'region'      => 'sa-east-1',
      'credentials' => [
          'key'    => env('AWS_ACCESS_KEY', ''),
          'secret' => env('AWS_SECRET_KEY', ''),
      ],
    ]);

    $bucketName = env('AWS_BUCKET_S3', '');
    $projectFolder =  $this->getUrlKey($project);
    $bucketPath =  's3://' . $bucketName . '/' . $projectFolder;

    $client->deleteMatchingObjects($bucketName, $projectFolder);

    $projectDistFolder = $dir . '/dist';
    $manager = new Transfer($client, $projectDistFolder, $bucketPath, [
        'before' => function (\Aws\Command $command) {
            if (in_array($command->getName(), ['PutObject', 'CreateMultipartUpload'])) {
                $command['ACL'] = 'public-read';
            }
        },
    ]);
    $manager->transfer();
    $this->info('enviado');
  }
}
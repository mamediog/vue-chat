<?php
ini_set('display_errors', 'On');
ini_set('display_startup_errors', 'On');
ini_set('error_reporting', -1);
ini_set('log_errors', 'On');

require_once __DIR__.'/../vendor/autoload.php';

function endsWith($haystack, $needle)
{
  $length = strlen($needle);
  if ($length == 0) {
    return true;
  }

  return (substr($haystack, -$length) === $needle);
}

function cleanURL ($url) {
  $url = str_replace('http://', '', $url);
  $url = str_replace('https://', '', $url);
  $url = str_replace('www.', '', $url);
  $url = str_replace('/', '', $url);
  $url = str_replace('.', '_', $url);

  return $url;
}

$cropPath = explode('/', $_GET['q']);

$queryString = $_GET;
unset($queryString['q']);

if (count($cropPath) < 4) {
  die('Invalid image');
}

$id = $cropPath[2];
$size = $cropPath[3];
$removePath = implode('/', [ $cropPath[1], $cropPath[2], $cropPath[3] ]);
$url = str_replace('/'.$removePath.'/', '', $_GET['q']) . (count($queryString) > 0 ? '?' . http_build_query($queryString) : '');
$url = str_replace('https:/', 'https://', $url);
$url = str_replace('http:/', 'http://', $url);
$url = str_replace('///', '//', $url);

if (!startsWith($url, 'https://') and !startsWith($url, 'http://')) {
  die('Invalid image');
}

$dotenv = \Dotenv\Dotenv::create(__DIR__.'/../');
$dotenv->load();

try {
  // $client = new \MongoDB\Client(
  //   'mongodb://'.getenv('DB_USERNAME').':'.getenv('DB_PASSWORD').'@'.getenv('DB_HOST').'/?retryWrites=true&w=majority'
  // );
  // $db = $client->selectDatabase(getenv('DB_DATABASE'));
  // $collection = $db->selectCollection('projects');
  // $project = $collection->findOne(['_id' => new \MongoDB\BSON\ObjectId($id)]);
  // if ($project === null) {
  //   return header('Location:'.$url);
  // }
  // $origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : 'local';
  // $origin = cleanURL($origin);
  // $projectURL = cleanURL($project->config['project']['url']);

  // if ($origin !== $projectURL) {
  //   return header('Location:'.$url);
  // }
  
  $img = file_get_contents($url);
  $ext = explode('?', $url)[0];
  $ext = explode('#', $ext)[0];
  $ext = explode('.', $ext);
  $ext = $ext[count($ext) - 1];
  
  if ($ext === 'gif') {
    header('Content-Type:image/gif');
    die($img);
  }
  
  if ($ext === 'svg') {
    header('Content-Type:image/svg+xml');
    die($img);
  }
  
  $image = \Gumlet\ImageResize::createFromString($img);
  
  if (endsWith($size, 'h')) {
    $image->resizeToHeight(rtrim($size, 'h'));
  }
  
  if (endsWith($size, 'w')) {
    $image->resizeToWidth(rtrim($size, 'w'));
  }
  
  // if (!in_array($ext, ['png', 'jpg', 'jpeg', 'webp'])) {
    header('Pragma: public');
    header('Cache-Control: max-age=86400');
    header('Expires: '. gmdate('D, d M Y H:i:s \G\M\T', time() + 86400));
    header('Content-Type:image/'.$ext);
    die($image->getImageAsString());
  // }
  
  // $source = __DIR__ . '/' . time() . '.' . $ext;
  
  // $image->save($source);
  // $destination = $source . '.webp';
  // \WebPConvert\WebPConvert::convert($source, $destination, []);
  
  // $webpContent = file_get_contents($destination);
  // unlink($source);
  // unlink($destination);
  
  // header('Pragma: public');
  // header('Cache-Control: max-age=86400');
  // header('Expires: '. gmdate('D, d M Y H:i:s \G\M\T', time() + 86400));
  // header('Content-Type:image/webp');
  // die($webpContent);
} catch (\Throwable $th) {
  return header('Location:'.$url);
}
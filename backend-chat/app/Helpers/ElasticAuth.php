<?php
namespace App\Helpers;

class ElasticAuth
{
  public static function makeRule($project, $type, $privileges) {
    $admToken = base64_encode(env('ELASTIC_USER') . ':' . env('ELASTIC_PASSWORD'));
    $ch = curl_init('https://' . env('ELASTIC_HOST') . '/_security/role/rule_' . $type . '_' . $project);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Basic ' . $admToken,
        'Content-Type:application/json'
    ]);

    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
      'indices' => [
        [
            'names' => [ $project . '*' ],
            'privileges' => $privileges
        ]
      ]
    ]));

    return curl_exec($ch);
  }

  public static function makeUser($project, $type, $client) {
    $userPassword = self::randomPassword(12);
    $admToken = base64_encode(env('ELASTIC_USER') . ':' . env('ELASTIC_PASSWORD'));

    $ch = curl_init('https://' . env('ELASTIC_HOST') . '/_security/user/' . $type . '_' . $project);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Basic ' . $admToken,
        'Content-Type:application/json'
    ]);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
        'password' => $userPassword,
        'roles' => [
            'rule_' . $type . '_' . $project
        ],
        'full_name' => $client->name,
        'email' => $client->email
    ]));
    
    return [ 'response' => curl_exec($ch), 'token' => base64_encode($type . '_'. $project . ':' . $userPassword)];
  }

  private static function randomPassword($size) {
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < $size; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}
}
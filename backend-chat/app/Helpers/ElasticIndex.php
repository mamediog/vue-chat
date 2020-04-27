<?php
namespace App\Helpers;

use Elasticsearch\ClientBuilder;

class ElasticIndex
{
    protected $client;
    protected $index;

    public function __construct($host, $user, $password)
    {
        $this->client = ClientBuilder::create()
            ->setHosts([ 'https://'.$user.':'.$password.'@'.$host ])
        ->build();
    }

    public function mapperUrlField ($type, $field) {
        $params = [
            'index' => $this->index,
            'body' => [
                '_source' => [
                    'enabled' => true
                ],
                $type => [
                    'properties' => [
                        $field => [
                            'type' => 'text',
                            'fields' => [
                                'untouched' => [
                                    'type' => 'keyword',
                                ]
                            ],
                            'copy_to' => [
                                'search',
                                'spelling'
                            ],
                            'analyzer' => 'standard'
                        ]
                    ]
                ]
            ]
        ];
        var_dump(json_encode($params));
        $this->client->indices()->putMapping($params);

        // $params = [
        //     'index' => $this->index
        // ];
        // $response = $this->client->indices()->getMapping($params);
        // var_dump(json_encode($response));
    
    }

    public function getClient()
    {
        return $this->client;
    }

    public function setIndexName($index)
    {
        $this->index = $index;
    }

    public function sendProducts($idParam, $values)
    {
        $this->send('product', $idParam, $values);
    }

    public function sendCategories($idParam, $values)
    {
        $this->send('category', $idParam, $values);
    }

    public function deleteCategories ($ids)
    {
        $this->delete('category', $ids);
    }

    public function deleteProducts ($ids)
    {
        $this->delete('product', $ids);
    }

    protected function send($type, $idParam, $values)
    {
        try {
            $objects = ['body' => []];
            foreach ($values as $body) {
                $objects['body'][] = [
                    'index' => [
                        '_index' => $this->index,
                        '_type' => $type,
                        '_id'    => $body[$idParam]
                    ]
                ];
            
                $objects['body'][] = $body;    
            }

            $this->client->bulk($objects);

            $params = [];
            $params['body'] = array(
                'actions' => array(
                    array(
                        'add' => array(
                            'index' => $this->index,
                            'alias' => $this->index
                        )
                    )
                )
            );
            $this->client->indices()->updateAliases($params);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    protected function delete($type, $ids)
    {
        try {
            foreach ($ids as $id) {
                $value = [];
                $value['index'] = $this->index;
                $value['id'] = $id;
                $value['type'] = $type;
    
                $this->client->delete($value);
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}

<?php


namespace HelgeSverre\Brreg;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use HelgeSverre\Brreg\Transformers\BrregCompanyDataTransformer;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

class BrregService
{
    protected $apiEndpoint = "https://data.brreg.no/enhetsregisteret/enhet/";
    protected $format = "json";

    /**
     * @var Manager
     */
    protected $fractal;

    /**
     * @var Client
     */
    protected $client;

    public function __construct(Manager $manager, Client $client)
    {
        $this->fractal = $manager;
        $this->client = $client;
    }

    public function searchByName($name, $page = 0, $size = 10)
    {
        try {
            $response = $this->client->get($this->apiEndpoint, [
                "headers" => ["Accept" => "application/json"],
                "query" => [
                    "page" => $page,
                    "size" => $size,
                    "\$filter" => "startswith(navn, '$name')"
                ]
            ])->getBody()->getContents();
        } catch (ClientException $exception) {
            return false;
        }

        $json = json_decode($response);

        if (empty($json->data)) {
            return ["data" => []];
        }

        return $this->transform($json->data);
    }

    /**
     *
     *
     * @param $registrationNumber
     * @return array|false array of company data on success, false if company does not exist
     */
    public function getCompanyData($registrationNumber)
    {
        $registrationNumber = $this->sanitizeRegistrationNumber($registrationNumber);

        try {
            $response = $this->client->get($this->apiEndpoint . $registrationNumber, [
                "headers" => ["Accept" => "application/json"],
            ])->getBody()->getContents();
        } catch (ClientException $exception) {
            return false;
        }

        return $this->transform(json_decode($response));
    }

    public function sanitizeRegistrationNumber($registrationNumber)
    {
        return trim(str_replace(' ', '', $registrationNumber));
    }

    public function transform($data)
    {
        // Sanity check, if there is no data, just return an empty array.
        if (empty($data)) return [];


        // TODO(16 des 2016) ~ Helge: Write different transformers based on the company type and select the transfomer with a StrategyFactory or switch case.
        $transformer = new BrregCompanyDataTransformer();
        $resource = is_array($data)
            ? new Collection($data, $transformer)
            : new Item($data, $transformer);


        return $this->fractal
            ->createData($resource)
            ->toArray();
    }

}
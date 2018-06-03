<?php

namespace AppBundle\Service\Google;

use AppBundle\Entity\Language;
use Doctrine\ORM\EntityManager;

class GoogleGeocodeService
{
    private $apiKey;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function getPlaceById($placeId, EntityManager $entityManager)
    {
        $repository = $entityManager->getRepository('AppBundle:Language');

        $languages = $repository->findAll();

        $response = [];

        /**
         * @var Language
         */
        foreach ($languages as $language) {
            $query = http_build_query([
                'place_id' => $placeId,
                'language' => $language->getLocale(),
                'key' => $this->apiKey,
            ]);

            $curl = curl_init('https://maps.googleapis.com/maps/api/geocode/json?'.$query);

            curl_setopt_array($curl, array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_REFERER => 'https://findyou.com.ua'
            ));

            $curlResponse = json_decode(curl_exec($curl), true);
            if (array_key_exists('results', $curlResponse)) {
                $response[$language->getLocale()] = $curlResponse['results'];
            }

            curl_close($curl);
        }

        return $response;
    }

    public function getPlaceByCoords($coords, EntityManager $entityManager)
    {
        $repository = $entityManager->getRepository('AppBundle:Language');

        $languages = $repository->findAll();

        $response = [];

        /**
         * @var Language
         */
        foreach ($languages as $language) {
            $query = http_build_query([
                'latlng' => $coords['lat'].','.$coords['lng'],
                'language' => $language->getLocale(),
                'key' => $this->apiKey,
            ]);

            $curl = curl_init('https://maps.googleapis.com/maps/api/geocode/json?'.$query);

            curl_setopt_array($curl, array(
                CURLOPT_RETURNTRANSFER => 1,
            ));

            $curlResponse = json_decode(curl_exec($curl), true);
            if (array_key_exists('results', $curlResponse)) {
                $response[$language->getLocale()] = $curlResponse['results'];
            }

            curl_close($curl);
        }

        return $response;
    }

    public function getCoordsByPlaceId($placeId)
    {
        $query = http_build_query([
            'place_id' => $placeId,
            'key' => $this->apiKey,
        ]);

        $curl = curl_init('https://maps.googleapis.com/maps/api/geocode/json?'.$query);

        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_REFERER => 'https://findyou.com.ua'
        ));

        $curlResponse = json_decode(curl_exec($curl), true);

        if (array_key_exists('results', $curlResponse) && count($curlResponse['results']) > 0) {
            $coords = $curlResponse['results'][0]['geometry']['location'];
        }
        else {
            $coords = ['lat' => null, 'lng' => null];
        }


        return $coords;
    }
}

<?php

namespace zrslib;

use DOMDocument;
use DOMNodeList;
use DOMXPath;
use GuzzleHttp\Client;

/**
 * Reader for the ZRS system.
 *
 * @author Bert Peters
 */
class ZRSReader
{
    const ZRS_LOCATION     = "https://zrs.leidenuniv.nl/ul/";
    const SEARCH_LOCATION  = "start.php";
    const RESULTS_LOCATION = "query.php";

    /**
     * @var Client http client to use.
     */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public static function getInstance()
    {
        $client = new Client();

        return new ZRSReader($client);
    }

    public function getBuildings()
    {
        $document = $this->getSearchPage();
        $xpath    = new DOMXPath($document);

        $options = $xpath->query("//select[@name=\"selgebouw\"]/option");

        return $this->getOptionValues($options);
    }

    public function getOrganisations()
    {
        $document = $this->getSearchPage();
        $xpath = new DOMXPath($document);

        $options = $xpath->query("//select[@name=\"res_instantie\"]/option");

        return $this->getOptionValues($options);
    }

    /**
     * Get all the option values from a list of option elements.
     *
     * @param DOMNodeList $options
     * @return array
     */
    private function getOptionValues(DOMNodeList $options)
    {
        $output = [];

        foreach ($options as $option) {
            $output[] = $option->getAttribute('value');
        }

        return $output;
    }

    public function getReservations(array $config = [])
    {
        $parameters = (new ZRSRequestBuilder($config))->build();
        $response = $this->client->post(self::ZRS_LOCATION . self::RESULTS_LOCATION, [
            'form_params' => $parameters,
        ]);

        $requestDate = (new \DateTimeImmutable())
            ->setDate($parameters['year'], $parameters['month'], $parameters['day']);

        $document = $this->parseHTML($response->getBody()->getContents());
        $reservationRows = (new DOMXPath($document))->query('//tr[td/@bgcolor != \'#666699\']');
        $reservations = [];
        foreach ($reservationRows as $reservationRow) {
            $timings = $this->trim($reservationRow->firstChild->textContent);
            list($start, $end) = explode(" ", $timings);
            $location = $this->trim($reservationRow->childNodes->item(1)->firstChild->textContent);
            $activity = $this->trim($reservationRow->childNodes->item(6)->textContent);

            $reservations[] = new Reservation(
                $this->createCombinedDateTime($requestDate, $start),
                $this->createCombinedDateTime($requestDate, $end),
                $location, $activity
            );
        }

        return $reservations;
    }

    private function trim($string) {
        return trim($string, chr(0xC2).chr(0xA0) . " \n");
    }

    private function createCombinedDateTime(\DateTimeImmutable $date, $time)
    {
        list($hours, $minutes) = explode(":", $time);

        return $date->setTime((int) $hours, (int) $minutes);
    }

    /**
     * Get the search page as a DOM document.
     *
     * @return DOMDocument
     */
    private function getSearchPage()
    {
        $url = self::ZRS_LOCATION.self::SEARCH_LOCATION;

        $response = $this->client->get($url);
        $contents = $response->getBody()->getContents();

        return $this->parseHTML($contents);
    }

    /**
     * Parse a html document.
     *
     * This method disables the built-in error handling to suppress it, and
     * restores it afterwards.
     *
     * @param string $htmlContents
     * @return DOMDocument
     */
    private function parseHTML($htmlContents)
    {
        $document = new DOMDocument();
        $previous = libxml_use_internal_errors(true);
        $document->loadHTML($htmlContents);
        libxml_use_internal_errors($previous);

        return $document;
    }
}

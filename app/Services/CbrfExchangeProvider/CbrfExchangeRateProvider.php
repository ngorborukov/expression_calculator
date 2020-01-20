<?php

namespace App\Services\CbrfExchangeProvider;

use App\Exceptions\ExchangeRateProviderException;
use App\Services\ExchangeRateProviderInterface;
use DateTime;
use GuzzleHttp\Client;
use SimpleXMLElement;

class CbrfExchangeRateProvider implements ExchangeRateProviderInterface
{
    private const URL = 'http://www.cbr.ru/scripts/XML_daily.asp?date_req=%s';

    private const XPATH = '//Valute/CharCode[.="%s"]/parent::*';

    /**
     * @var Client
     */
    private $requester;

    /**
     * @var SimpleXMLElement
     */
    private $xml;

    /**
     * @param Client $requester
     */
    public function __construct(Client $requester)
    {
        $this->requester = $requester;
    }

    /**
     * @param string $from
     * @param string $to
     * @param DateTime|null $onDate
     *
     * @return float
     * @throws ExchangeRateProviderException
     */
    public function getRate(string $from, string $to, DateTime $onDate = null): float
    {
        if ($from === $to) {
            return 1;
        }

        $xml = $this->getXml($onDate);
        if (!$xml) {
            throw new ExchangeRateProviderException('Unable to load data from provider');
        }

        $nodes = $xml->xpath(sprintf(self::XPATH, $from));
        if (!count($nodes)) {
            throw new ExchangeRateProviderException(sprintf('Unable to get rates for currency %s', $from));
        }
        $node = reset($nodes);

        return $this->convertValue($node->Value);
    }

    /**
     * @param DateTime|null $onDate
     *
     * @return SimpleXMLElement
     */
    private function getXml(DateTime $onDate = null): SimpleXMLElement
    {
        if (!$this->xml) {
            $response = $this->requester->get($this->generateURL($onDate));
            $this->xml = new SimpleXMLElement($response->getBody());
        }

        return $this->xml;
    }

    /**
     * @param DateTime|null $onDate
     *
     * @return string
     */
    private function generateURL(DateTime $onDate = null): string
    {
        $onDate = $onDate ?? new DateTime();

        return sprintf(self::URL, $onDate->format('d/m/Y'));
    }

    /**
     * @param string $value
     *
     * @return float
     */
    private function convertValue(string $value): float
    {
        $value = str_replace(',', '.', $value);

        return (float) $value;
    }
}

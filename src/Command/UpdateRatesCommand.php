<?php


namespace App\Command;


use App\Service\CbrRates;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;


/**
 * Class UpdateRatesCommand
 * @package App\Command
 */
class UpdateRatesCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:update-rates';

    /**
     * Url for getting new data
     * @var string
     */
    protected $updateUrl = 'http://www.cbr.ru/scripts/XML_daily.asp';

    /**
     * @var CbrRates
     */
    private $cbrRates;

    /**
     * @var HttpClientInterface
     */
    private $httpClient;

    /**
     * UpdateRatesCommand constructor.
     * @param CbrRates $cbrRates
     * @param HttpClientInterface $httpClient
     */
    public function __construct(CbrRates $cbrRates, HttpClientInterface $httpClient)
    {
        parent::__construct();
        $this->cbrRates = $cbrRates;
        $this->httpClient = $httpClient;
    }

    protected function configure()
    {
        // ...
    }

    /**
     * Update rates from CBR
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $data = $this->httpClient->request('GET', $this->updateUrl)->getContent();

            $xml = simplexml_load_string($data);
            $array = json_decode(json_encode($xml), true);

            if (isset($array['Valute']) && count($array['Valute'])) {
                $data = [];
                foreach ($array['Valute'] as $item) {
                    $data[$item['CharCode']] = floatval(
                        str_replace(',', '.',
                            str_replace('.', '',
                                $item['Value']
                            )
                        )
                    );
                }
                $this->cbrRates->updateRates($data);
                return Command::SUCCESS;
            }
            return Command::FAILURE;
        } catch (\Exception $e) {
            return Command::FAILURE;
        }
    }
}
<?php

namespace App\Command;

use App\Helper\CommandHelper;
use App\Helper\Helper;
use App\Library\Utils\Base\Adapter;
use App\Model\BusinessCharts;
use PHPUnit\Util\Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GetSchedule extends Command
{
    use CommandHelper;

    const COMMAND_NAME = 'get:schedule';
    const PARAM_ADAPTER = 'provider';

    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        parent::__construct();
    }

    protected function configure(): void
    {
        $commandOptions = [
            new InputOption(self::PARAM_ADAPTER, null, InputOption::VALUE_REQUIRED, 'provider name, ex: 1')
        ];

        $this->setName(self::COMMAND_NAME)->setDescription('Pulls and saves work schedule based on provider');
        $this->setDefinition($commandOptions);

        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $this->output = $output;

        $this->setOutput('Started');

        $adapterName = (string)$input->getOption(self::PARAM_ADAPTER);

        if (empty($adapterName) || $adapterName == "") {
            throw new Exception(self::PARAM_ADAPTER . ' cannot be empty', 230);
        }

        try {
            $adapter = (new Adapter())->getByName($adapterName);
            $this->setOutput('Request started for ' . $adapter->getAdapterName() . ' adapter');

            list($success, $message, $response) = $adapter->doRequest($adapter->getServiceUrl());

            if (!$success) {
                $method = strtolower($adapter->getAdapterName()) . "MockData";
                if (!method_exists(Helper::class, $method)) {
                    throw new \Exception($message);
                }

                $response = Helper::$method();
            }

            $this->formatResponse($response);

            $doctrine = $this->container->get('doctrine')->getManager();
            $insertedCount = (new BusinessCharts())->bulkImport($doctrine, $response);

            $this->setOutput("Process completed. Inserted count: " . $insertedCount);

        } catch (
        \Exception|
        NotFoundExceptionInterface|
        ContainerExceptionInterface
        $exception) {
            $this->setOutput($exception->getMessage() . ', code:' . $exception->getCode());
        }
    }

    private function formatResponse(&$response): void
    {
        #TODO can also be appointed from the service
        foreach ($response as $key => $param) {
            $response[$key] = [
                'name' => $param['name'] ?? null,
                'cook_time' => $param['cook_time'] ?? null
            ];
        }
    }

}
<?php

namespace AC\Yotpo\Cli;

use AC\Yotpo\Cli\Commands\YotpoGet;
use Webmozart\Console\Api\Args\Format\Argument;
use Webmozart\Console\Api\Args\Format\Option;
use Webmozart\Console\Config\DefaultApplicationConfig;

class ApplicationConfig extends DefaultApplicationConfig
{
    /**
     * Main application config entry point
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('ycli')
            ->setVersion('1.0.0')
            ->beginCommand('review')
                ->addAlias('r')
                ->setDescription('Get reviews from Yotpo')
                ->setHelp($this->getReviewHelp())
                ->setHandler(new YotpoGet())
                ->addArgument('productId', Argument::REQUIRED, 'The product id for which to fetch reviews')
                ->addOption('page', 'p', Option::INTEGER|Option::REQUIRED_VALUE, 'The page number to fetch (starts at 1)', 1)
                ->addOption('count', 'c', Option::INTEGER|Option::REQUIRED_VALUE, 'The size of page', 5)
                ->addOption('fromDate', 'd', Option::STRING|Option::REQUIRED_VALUE, 'The date from which point on to fetch reviews', '2017-01-01 00:00:00')
            ->end()
        ;
    }

    /**
     * Return the help text for the feview command
     *
     * @return string
     */
    protected function getReviewHelp()
    {
        return <<<EOL
Example usage:
  <c2>./ycli r GHF-0002</c2>           Fetch reviews for GHF-0002 using default values for <b>page</b>, <b>count</b> and <b>fromDate</b>
  <c2>./ycli r GHF-0002 -p 1 -c 2</c2> Fetch reviews for GHF-0002 on page 1 with a page size of 2 using the default value for <b>fromDate</b>
EOL;

    }
}
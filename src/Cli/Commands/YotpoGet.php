<?php

namespace AC\Yotpo\Cli\Commands;

use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Yaml\Yaml;
use Webmozart\Console\Api\Args\Args;
use Webmozart\Console\Api\Command\Command;
use Webmozart\Console\Api\IO\IO;
use Yotpo\Yotpo;

/**
 * Class YotpoGet
 *
 * @package AC\Yotpo\Cli\Commands
 */
class YotpoGet
{
    /**
     * Default entry point for the review command
     *
     * Fetches reviews from yotpo and writes them as a json string to stdout
     * Returns 0 to the console indicating success
     *
     * @param Args    $args
     * @param IO      $io
     * @param Command $command
     *
     * @return int
     */
    public function handle(Args $args, IO $io, Command $command)
    {
        $productId = $args->getArgument('productId');
        $page      = $args->getOption('page');
        $count     = $args->getOption('count');
        $fromDate  = $args->getOption('fromDate');
        $yotpo     = $this->getYotpo();
        $params    = [
            'product_id' => $productId,
            'page'       => $page,
            'count'      => $count,
            'since_date' => $fromDate,
        ];

        try {
            $raw    = $yotpo->get_product_reviews($params);
        } catch (\Exception $e) {
            $result = '<error>Something went wrong while talking to yotpo. Bummer!</error>';
            $io->writeLine($result);

            return 1;
        }

        try {
            $result = $this->parseRaw($raw);
        } catch (\Exception $e) {
            $result = '<error>Unable to parse the yotpo result. I am sad now.</error>';
            $io->writeLine($result);

            return 1;
        }

        $io->write($result);

        return 0;
    }

    /**
     * Get the yotpo instance
     *
     * @return Yotpo
     */
    protected function getYotPo()
    {
        $config = Yaml::parse(file_get_contents(__DIR__.'/../../../.config'));
        $key    = $config['app']['yotpo']['key'];
        $secret = $config['app']['yotpo']['secret'];
        $yotpo  = new Yotpo($key, $secret);

        return $yotpo;
    }

    /**
     * Parse the raw result into what we actually want
     *
     * @param \stdClass $raw
     *
     * @return string
     */
    protected function parseRaw($raw)
    {
        $pa      = PropertyAccess::createPropertyAccessor();
        $reviews = $pa->getValue($raw, 'response.reviews');
        $parsed  = [];

        foreach ($reviews as $review) {
            $parsed[] = [
                'id'         => $pa->getValue($review, 'id'),
                'title'      => $pa->getValue($review, 'title'),
                'content'    => $pa->getValue($review, 'content'),
                'created_at' => $pa->getValue($review, 'created_at'),
            ];
        }

        $reviews = json_encode($parsed);

        return $reviews;
    }
}

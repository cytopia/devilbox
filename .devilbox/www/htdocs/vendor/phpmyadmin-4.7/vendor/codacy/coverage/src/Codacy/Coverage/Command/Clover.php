<?php

namespace Codacy\Coverage\Command;

use Symfony\Component\Console\Command\Command as ConsoleCommand;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Codacy\Coverage\Parser\CloverParser;
use Codacy\Coverage\Util\JsonProducer;
use Codacy\Coverage\Util\GitClient;
use Codacy\Coverage\Util\CodacyApiClient;

/**
 * Class Clover
 *
 */
class Clover extends ConsoleCommand
{

    protected function configure()
    {
        $this
            ->setName("clover")
            ->setDescription("Send coverage results in clover format")
            ->addArgument(
                "path_to_coverage_results",
                InputArgument::OPTIONAL,
                "Path where coverage results are saved: XML file for clover format, directory containing the index.xml for phpunit format"
            )
            ->addOption(
                "git-commit",
                null,
                InputOption::VALUE_REQUIRED,
                "Commit hash of results to be send"
            )
            ->addOption(
                "base-url",
                null,
                InputOption::VALUE_REQUIRED,
                "Codacy base url"
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $projectToken = $this->getProjectToken();

        $parser = $this->getParser($input->getArgument("path_to_coverage_results"));
        $jsonProducer = new JsonProducer();
        $jsonProducer->setParser($parser);

        $commit = $this->getCommitHash($input->getOption("git-commit"));

        $baseUrl = $this->getBaseCodacyUrl($input->getOption("base-url"));

        $data = $jsonProducer->makeJson();

        if ($output->isVerbose()) {
            $output->writeln("Sending coverage results to " . $baseUrl);
            $output->writeln("Generated JSON:");
            $output->writeln($data);
        }

        $client = new CodacyApiClient($baseUrl, $projectToken);
        $result = $client->sendCoverage($commit, $data);
        if ($output->isVerbose()) {
            $output->writeln($result);
        }
    }

    /**
     * Get parser of current format type.
     *
     * @param string $path Path to clover.xml
     *
     * @return CloverParser
     */
    protected function getParser($path = null)
    {
        $path = is_null($path) ?
            join(DIRECTORY_SEPARATOR, array('build', 'logs', 'clover.xml')) :
            $path;
        return new CloverParser($path);
    }

    /**
     * Return Codacy Project Token.
     *
     * @return string       Project token
     *
     * @throws \InvalidArgumentException   If Token not specified
     */
    protected function getProjectToken()
    {
        $projectToken = getenv("CODACY_PROJECT_TOKEN");
        if ($projectToken == false) {
            throw new \InvalidArgumentException(
                "Cannot continue with execution as long as your project token is not set as an environmental variable."
                . PHP_EOL . "Please type: export CODACY_PROJECT_TOKEN=<YOUR TOKEN>"
            );
        }

        return urlencode($projectToken);
    }

    /**
     * Get Git commit hash of project
     *
     * @param string $hash Specified hash
     *
     * @return string       Git commit hash
     *
     * @throws \InvalidArgumentException    When bad hash specified, or can't get commit hash
     */
    protected function getCommitHash($hash = null)
    {
        if (!$hash) {
            $gClient = new GitClient(getcwd());
            return $gClient->getHashOfLatestCommit();
        }

        if (strlen($hash) != 40) {
            throw new \InvalidArgumentException(
                sprintf("Invalid git commit hash %s specified", $hash)
            );
        }

        return urlencode($hash);
    }

    /**
     * Return base Codacy Project URL
     *
     * @param string $url HTTP URL for codacy
     *
     * @return string       Base Codacy Project URL
     */
    protected function getBaseCodacyUrl($url = null)
    {
        if ($url) {
            return $url;
        }
        $url = getenv("CODACY_API_BASE_URL");

        return $url ? $url : "https://api.codacy.com";
    }
}

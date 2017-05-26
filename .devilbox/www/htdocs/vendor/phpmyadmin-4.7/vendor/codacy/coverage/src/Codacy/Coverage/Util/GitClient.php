<?php

namespace Codacy\Coverage\Util;

use Gitonomy\Git\Repository;
use Codacy\Coverage\Util\Config;

/**
 * Class GitClient
 * @author Jakob Pupke <jakob.pupke@gmail.com>
 */
class GitClient
{
    /**
     * @var Repository
     */
    private $_repository;

    /**
     * Instantiates a GitClient object. Reads conf.ini to get the path to the repository.
     * Throws InvalidArgumentException is projectRoot is not properly set in ini file.
     */
    public function __construct($path)
    {
        if (is_dir(getcwd())) {
            $this->_repository = new Repository($path);
        } else {
            throw new \InvalidArgumentException(
                "Could not instantiate GitClient. Using: "
                . getcwd()
            );
        }

    }

    /**
     * @return string The Hash of the latest Commit.
     */
    public function getHashOfLatestCommit()
    {
        $head = $this->_repository->getHeadCommit();
        return $head->getHash();
    }
}


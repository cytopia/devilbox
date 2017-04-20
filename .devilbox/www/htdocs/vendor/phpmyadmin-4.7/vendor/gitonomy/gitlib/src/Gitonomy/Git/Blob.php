<?php

/**
 * This file is part of Gitonomy.
 *
 * (c) Alexandre Salomé <alexandre.salome@gmail.com>
 * (c) Julien DIDIER <genzo.wm@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Gitonomy\Git;

/**
 * Representation of a Blob commit.
 *
 * @author Alexandre Salomé <alexandre.salome@gmail.com>
 */
class Blob
{
    /**
     * @var Repository
     */
    protected $repository;

    /**
     * @var string
     */
    protected $hash;

    /**
     * @var string
     */
    protected $content;

    /**
     * @var string
     */
    protected $mimetype;

    /**
     * @param Repository $repository Repository where the blob is located
     * @param string     $hash       Hash of the blob
     */
    public function __construct(Repository $repository, $hash)
    {
        $this->repository = $repository;
        $this->hash = $hash;
    }

    /**
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * Returns content of the blob.
     *
     * @throws ProcessException Error occurred while getting content of blob
     */
    public function getContent()
    {
        if (null === $this->content) {
            $this->content = $this->repository->run('cat-file', array('-p', $this->hash));
        }

        return $this->content;
    }

    /**
     * Determine the mimetype of the blob.
     *
     * @return string A mimetype
     */
    public function getMimetype()
    {
        if (null === $this->mimetype) {
            $finfo = new \finfo(FILEINFO_MIME);
            $this->mimetype = $finfo->buffer($this->getContent());
        }

        return $this->mimetype;
    }

    /**
     * Determines if file is binary.
     *
     * @return bool
     */
    public function isBinary()
    {
        return !$this->isText();
    }

    /**
     * Determines if file is text.
     *
     * @return bool
     */
    public function isText()
    {
        return (bool) preg_match('#^text/|^application/xml#', $this->getMimetype());
    }
}

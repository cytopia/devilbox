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
namespace Gitonomy\Git\Diff;

use Gitonomy\Git\Repository;

/**
 * @author Alexandre Salomé <alexandre.salome@gmail.com>
 */
class File
{
    /**
     * @var string
     */
    protected $oldName;

    /**
     * @var string
     */
    protected $newName;

    /**
     * @var string
     */
    protected $oldMode;

    /**
     * @var string
     */
    protected $newMode;

    /**
     * @var string
     */
    protected $oldIndex;

    /**
     * @var string
     */
    protected $newIndex;

    /**
     * @var bool
     */
    protected $isBinary;

    /**
     * @var array An array of FileChange objects
     */
    protected $changes;

    /**
     * @var Repository
     */
    protected $repository;

    /**
     * Instanciates a new File object.
     */
    public function __construct($oldName, $newName, $oldMode, $newMode, $oldIndex, $newIndex, $isBinary)
    {
        $this->oldName = $oldName;
        $this->newName = $newName;
        $this->oldMode = $oldMode;
        $this->newMode = $newMode;
        $this->oldIndex = $oldIndex;
        $this->newIndex = $newIndex;
        $this->isBinary = $isBinary;

        $this->changes = array();
    }

    public function addChange(FileChange $change)
    {
        $this->changes[] = $change;
    }

    /**
     * Indicates if this diff file is a creation.
     *
     * @return bool
     */
    public function isCreation()
    {
        return null === $this->oldName;
    }

    /**
     * Indicates if this diff file is a modification.
     *
     * @return bool
     */
    public function isModification()
    {
        return null !== $this->oldName && null !== $this->newName;
    }

    /**
     * Indicates if it's a rename.
     *
     * A rename can only occurs if it's a modification (not a creation or a deletion).
     *
     * @return bool
     */
    public function isRename()
    {
        return $this->isModification() && $this->oldName !== $this->newName;
    }

    /**
     * Indicates if the file mode has changed.
     */
    public function isChangeMode()
    {
        return $this->isModification() && $this->oldMode !== $this->newMode;
    }

    /**
     * Indicates if this diff file is a deletion.
     *
     * @return bool
     */
    public function isDeletion()
    {
        return null === $this->newName;
    }

    /**
     * Indicates if this diff file is a deletion.
     *
     * @return bool
     */
    public function isDelete()
    {
        return null === $this->newName;
    }

    /**
     * @return int Number of added lines
     */
    public function getAdditions()
    {
        $result = 0;
        foreach ($this->changes as $change) {
            $result += $change->getCount(FileChange::LINE_ADD);
        }

        return $result;
    }

    /**
     * @return int Number of deleted lines
     */
    public function getDeletions()
    {
        $result = 0;
        foreach ($this->changes as $change) {
            $result += $change->getCount(FileChange::LINE_REMOVE);
        }

        return $result;
    }

    public function getOldName()
    {
        return $this->oldName;
    }

    public function getNewName()
    {
        return $this->newName;
    }

    public function getName()
    {
        if (null === $this->newName) {
            return $this->oldName;
        }

        return $this->newName;
    }

    public function getOldMode()
    {
        return $this->oldMode;
    }

    public function getNewMode()
    {
        return $this->newMode;
    }

    public function getOldIndex()
    {
        return $this->oldIndex;
    }

    public function getNewIndex()
    {
        return $this->newIndex;
    }

    public function isBinary()
    {
        return $this->isBinary;
    }

    public function getChanges()
    {
        return $this->changes;
    }

    public function toArray()
    {
        return array(
            'old_name' => $this->oldName,
            'new_name' => $this->newName,
            'old_mode' => $this->oldMode,
            'new_mode' => $this->newMode,
            'old_index' => $this->oldIndex,
            'new_index' => $this->newIndex,
            'is_binary' => $this->isBinary,
            'changes' => array_map(function (FileChange $change) {
                return $change->toArray();
            }, $this->changes),
        );
    }

    public static function fromArray(array $array)
    {
        $file = new self($array['old_name'], $array['new_name'], $array['old_mode'], $array['new_mode'], $array['old_index'], $array['new_index'], $array['is_binary']);

        foreach ($array['changes'] as $change) {
            $file->addChange(FileChange::fromArray($change));
        }

        return $file;
    }

    public function getAnchor()
    {
        return substr($this->newIndex, 0, 12);
    }

    public function getRepository()
    {
        return $this->repository;
    }

    public function setRepository(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function getOldBlob()
    {
        if (null === $this->repository) {
            throw new \RuntimeException('Repository is missing to return Blob object.');
        }

        if ($this->isCreation()) {
            throw new \LogicException('Can\'t return old Blob on a creation');
        }

        return $this->repository->getBlob($this->oldIndex);
    }

    public function getNewBlob()
    {
        if (null === $this->repository) {
            throw new \RuntimeException('Repository is missing to return Blob object.');
        }

        if ($this->isDeletion()) {
            throw new \LogicException('Can\'t return new Blob on a deletion');
        }

        return $this->repository->getBlob($this->newIndex);
    }
}

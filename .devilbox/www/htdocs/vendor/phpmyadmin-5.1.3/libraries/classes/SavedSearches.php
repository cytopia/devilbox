<?php
/**
 * Saved searches managing
 */

declare(strict_types=1);

namespace PhpMyAdmin;

use function count;
use function intval;
use function is_string;
use function json_decode;
use function json_encode;
use function max;
use function min;

/**
 * Saved searches managing
 */
class SavedSearches
{
    /**
     * Global configuration
     *
     * @var array
     */
    private $config = null;

    /**
     * Id
     *
     * @var int|null
     */
    private $id = null;

    /**
     * Username
     *
     * @var string
     */
    private $username = null;

    /**
     * DB name
     *
     * @var string
     */
    private $dbname = null;

    /**
     * Saved search name
     *
     * @var string
     */
    private $searchName = null;

    /**
     * Criterias
     *
     * @var array
     */
    private $criterias = null;

    /** @var Relation */
    private $relation;

    /**
     * @param array    $config   Global configuration
     * @param Relation $relation Relation instance
     */
    public function __construct(array $config, Relation $relation)
    {
        $this->setConfig($config);
        $this->relation = $relation;
    }

    /**
     * Setter of id
     *
     * @param int|null $searchId Id of search
     *
     * @return static
     */
    public function setId($searchId)
    {
        $searchId = (int) $searchId;
        if (empty($searchId)) {
            $searchId = null;
        }

        $this->id = $searchId;

        return $this;
    }

    /**
     * Getter of id
     *
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Setter of searchName
     *
     * @param string $searchName Saved search name
     *
     * @return static
     */
    public function setSearchName($searchName)
    {
        $this->searchName = $searchName;

        return $this;
    }

    /**
     * Getter of searchName
     *
     * @return string
     */
    public function getSearchName()
    {
        return $this->searchName;
    }

    /**
     * Setter of config
     *
     * @param array $config Global configuration
     *
     * @return static
     */
    public function setConfig(array $config)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * Getter of config
     *
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Setter for criterias
     *
     * @param array|string $criterias Criterias of saved searches
     * @param bool         $json      Criterias are in JSON format
     *
     * @return static
     */
    public function setCriterias($criterias, $json = false)
    {
        if ($json === true && is_string($criterias)) {
            $this->criterias = json_decode($criterias, true);

            return $this;
        }

        $aListFieldsToGet = [
            'criteriaColumn',
            'criteriaSort',
            'criteriaShow',
            'criteria',
            'criteriaAndOrRow',
            'criteriaAndOrColumn',
            'rows',
            'TableList',
        ];

        $data = [];

        $data['criteriaColumnCount'] = count($criterias['criteriaColumn']);

        foreach ($aListFieldsToGet as $field) {
            if (! isset($criterias[$field])) {
                continue;
            }

            $data[$field] = $criterias[$field];
        }

        /* Limit amount of rows */
        if (! isset($data['rows'])) {
            $data['rows'] = 0;
        } else {
            $data['rows'] = min(
                max(0, intval($data['rows'])),
                100
            );
        }

        for ($i = 0; $i <= $data['rows']; $i++) {
            $data['Or' . $i] = $criterias['Or' . $i];
        }

        $this->criterias = $data;

        return $this;
    }

    /**
     * Getter for criterias
     *
     * @return array
     */
    public function getCriterias()
    {
        return $this->criterias;
    }

    /**
     * Setter for username
     *
     * @param string $username Username
     *
     * @return static
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Getter for username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Setter for DB name
     *
     * @param string $dbname DB name
     *
     * @return static
     */
    public function setDbname($dbname)
    {
        $this->dbname = $dbname;

        return $this;
    }

    /**
     * Getter for DB name
     *
     * @return string
     */
    public function getDbname()
    {
        return $this->dbname;
    }

    /**
     * Save the search
     *
     * @return bool
     */
    public function save()
    {
        global $dbi;

        if ($this->getSearchName() == null) {
            $message = Message::error(
                __('Please provide a name for this bookmarked search.')
            );
            $response = Response::getInstance();
            $response->setRequestStatus($message->isSuccess());
            $response->addJSON('fieldWithError', 'searchName');
            $response->addJSON('message', $message);
            exit;
        }

        if ($this->getUsername() == null
            || $this->getDbname() == null
            || $this->getSearchName() == null
            || $this->getCriterias() == null
        ) {
            $message = Message::error(
                __('Missing information to save the bookmarked search.')
            );
            $response = Response::getInstance();
            $response->setRequestStatus($message->isSuccess());
            $response->addJSON('message', $message);
            exit;
        }

        $savedSearchesTbl
            = Util::backquote($this->config['cfgRelation']['db']) . '.'
            . Util::backquote($this->config['cfgRelation']['savedsearches']);

        //If it's an insert.
        if ($this->getId() === null) {
            $wheres = [
                "search_name = '" . $dbi->escapeString($this->getSearchName())
                . "'",
            ];
            $existingSearches = $this->getList($wheres);

            if (! empty($existingSearches)) {
                $message = Message::error(
                    __('An entry with this name already exists.')
                );
                $response = Response::getInstance();
                $response->setRequestStatus($message->isSuccess());
                $response->addJSON('fieldWithError', 'searchName');
                $response->addJSON('message', $message);
                exit;
            }

            $sqlQuery = 'INSERT INTO ' . $savedSearchesTbl
                . '(`username`, `db_name`, `search_name`, `search_data`)'
                . ' VALUES ('
                . "'" . $dbi->escapeString($this->getUsername()) . "',"
                . "'" . $dbi->escapeString($this->getDbname()) . "',"
                . "'" . $dbi->escapeString($this->getSearchName()) . "',"
                . "'" . $dbi->escapeString(json_encode($this->getCriterias()))
                . "')";

            $result = (bool) $this->relation->queryAsControlUser($sqlQuery);
            if (! $result) {
                return false;
            }

            $this->setId($dbi->insertId());

            return true;
        }

        //Else, it's an update.
        $wheres = [
            'id != ' . $this->getId(),
            "search_name = '" . $dbi->escapeString($this->getSearchName()) . "'",
        ];
        $existingSearches = $this->getList($wheres);

        if (! empty($existingSearches)) {
            $message = Message::error(
                __('An entry with this name already exists.')
            );
            $response = Response::getInstance();
            $response->setRequestStatus($message->isSuccess());
            $response->addJSON('fieldWithError', 'searchName');
            $response->addJSON('message', $message);
            exit;
        }

        $sqlQuery = 'UPDATE ' . $savedSearchesTbl
            . "SET `search_name` = '"
            . $dbi->escapeString($this->getSearchName()) . "', "
            . "`search_data` = '"
            . $dbi->escapeString(json_encode($this->getCriterias())) . "' "
            . 'WHERE id = ' . $this->getId();

        return (bool) $this->relation->queryAsControlUser($sqlQuery);
    }

    /**
     * Delete the search
     *
     * @return bool
     */
    public function delete()
    {
        global $dbi;

        if ($this->getId() == null) {
            $message = Message::error(
                __('Missing information to delete the search.')
            );
            $response = Response::getInstance();
            $response->setRequestStatus($message->isSuccess());
            $response->addJSON('fieldWithError', 'searchId');
            $response->addJSON('message', $message);
            exit;
        }

        $savedSearchesTbl
            = Util::backquote($this->config['cfgRelation']['db']) . '.'
            . Util::backquote($this->config['cfgRelation']['savedsearches']);

        $sqlQuery = 'DELETE FROM ' . $savedSearchesTbl
            . "WHERE id = '" . $dbi->escapeString((string) $this->getId()) . "'";

        return (bool) $this->relation->queryAsControlUser($sqlQuery);
    }

    /**
     * Load the current search from an id.
     *
     * @return bool Success
     */
    public function load()
    {
        global $dbi;

        if ($this->getId() == null) {
            $message = Message::error(
                __('Missing information to load the search.')
            );
            $response = Response::getInstance();
            $response->setRequestStatus($message->isSuccess());
            $response->addJSON('fieldWithError', 'searchId');
            $response->addJSON('message', $message);
            exit;
        }

        $savedSearchesTbl = Util::backquote($this->config['cfgRelation']['db'])
            . '.'
            . Util::backquote($this->config['cfgRelation']['savedsearches']);
        $sqlQuery = 'SELECT id, search_name, search_data '
            . 'FROM ' . $savedSearchesTbl . ' '
            . "WHERE id = '" . $dbi->escapeString((string) $this->getId()) . "' ";

        $resList = $this->relation->queryAsControlUser($sqlQuery);
        $oneResult = $dbi->fetchArray($resList);

        if ($oneResult === false) {
            $message = Message::error(__('Error while loading the search.'));
            $response = Response::getInstance();
            $response->setRequestStatus($message->isSuccess());
            $response->addJSON('fieldWithError', 'searchId');
            $response->addJSON('message', $message);
            exit;
        }

        $this->setSearchName($oneResult['search_name'])
            ->setCriterias($oneResult['search_data'], true);

        return true;
    }

    /**
     * Get the list of saved searches of a user on a DB
     *
     * @param string[] $wheres List of filters
     *
     * @return array List of saved searches or empty array on failure
     */
    public function getList(array $wheres = [])
    {
        global $dbi;

        if ($this->getUsername() == null
            || $this->getDbname() == null
        ) {
            return [];
        }

        $savedSearchesTbl = Util::backquote($this->config['cfgRelation']['db'])
            . '.'
            . Util::backquote($this->config['cfgRelation']['savedsearches']);
        $sqlQuery = 'SELECT id, search_name '
            . 'FROM ' . $savedSearchesTbl . ' '
            . 'WHERE '
            . "username = '" . $dbi->escapeString($this->getUsername()) . "' "
            . "AND db_name = '" . $dbi->escapeString($this->getDbname()) . "' ";

        foreach ($wheres as $where) {
            $sqlQuery .= 'AND ' . $where . ' ';
        }

        $sqlQuery .= 'order by search_name ASC ';

        $resList = $this->relation->queryAsControlUser($sqlQuery);

        $list = [];
        while ($oneResult = $dbi->fetchArray($resList)) {
            $list[$oneResult['id']] = $oneResult['search_name'];
        }

        return $list;
    }
}

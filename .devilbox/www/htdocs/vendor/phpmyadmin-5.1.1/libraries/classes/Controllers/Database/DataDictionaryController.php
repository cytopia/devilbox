<?php

declare(strict_types=1);

namespace PhpMyAdmin\Controllers\Database;

use PhpMyAdmin\DatabaseInterface;
use PhpMyAdmin\Index;
use PhpMyAdmin\Relation;
use PhpMyAdmin\Response;
use PhpMyAdmin\Template;
use PhpMyAdmin\Transformations;
use PhpMyAdmin\Util;
use function is_array;
use function str_replace;

class DataDictionaryController extends AbstractController
{
    /** @var Relation */
    private $relation;

    /** @var Transformations */
    private $transformations;

    /** @var DatabaseInterface */
    private $dbi;

    /**
     * @param Response          $response
     * @param string            $db              Database name
     * @param Relation          $relation
     * @param Transformations   $transformations
     * @param DatabaseInterface $dbi
     */
    public function __construct($response, Template $template, $db, $relation, $transformations, $dbi)
    {
        parent::__construct($response, $template, $db);
        $this->relation = $relation;
        $this->transformations = $transformations;
        $this->dbi = $dbi;
    }

    public function index(): void
    {
        Util::checkParameters(['db'], true);

        $header = $this->response->getHeader();
        $header->enablePrintView();

        $cfgRelation = $this->relation->getRelationsParam();

        $comment = $this->relation->getDbComment($this->db);

        $this->dbi->selectDb($this->db);
        $tablesNames = $this->dbi->getTables($this->db);

        $tables = [];
        foreach ($tablesNames as $tableName) {
            $showComment = (string) $this->dbi->getTable(
                $this->db,
                $tableName
            )->getStatusInfo('TABLE_COMMENT');

            [, $primaryKeys] = Util::processIndexData(
                $this->dbi->getTableIndexes($this->db, $tableName)
            );

            [$foreigners, $hasRelation] = $this->relation->getRelationsAndStatus(
                ! empty($cfgRelation['relation']),
                $this->db,
                $tableName
            );

            $columnsComments = $this->relation->getComments($this->db, $tableName);

            $columns = $this->dbi->getColumns($this->db, $tableName);
            $rows = [];
            foreach ($columns as $row) {
                $extractedColumnSpec = Util::extractColumnSpec($row['Type']);

                $relation = '';
                if ($hasRelation) {
                    $foreigner = $this->relation->searchColumnInForeigners(
                        $foreigners,
                        $row['Field']
                    );
                    if (is_array($foreigner) && isset($foreigner['foreign_table'], $foreigner['foreign_field'])) {
                        $relation = $foreigner['foreign_table'];
                        $relation .= ' -> ';
                        $relation .= $foreigner['foreign_field'];
                    }
                }

                $mime = '';
                if ($cfgRelation['mimework']) {
                    $mimeMap = $this->transformations->getMime(
                        $this->db,
                        $tableName,
                        true
                    );
                    if (is_array($mimeMap) && isset($mimeMap[$row['Field']]['mimetype'])) {
                        $mime = str_replace(
                            '_',
                            '/',
                            $mimeMap[$row['Field']]['mimetype']
                        );
                    }
                }

                $rows[$row['Field']] = [
                    'name' => $row['Field'],
                    'has_primary_key' => isset($primaryKeys[$row['Field']]),
                    'type' => $extractedColumnSpec['type'],
                    'print_type' => $extractedColumnSpec['print_type'],
                    'is_nullable' => $row['Null'] !== '' && $row['Null'] !== 'NO',
                    'default' => $row['Default'] ?? null,
                    'comment' => $columnsComments[$row['Field']] ?? '',
                    'mime' => $mime,
                    'relation' => $relation,
                ];
            }

            $tables[$tableName] = [
                'name' => $tableName,
                'comment' => $showComment,
                'has_relation' => $hasRelation,
                'has_mime' => $cfgRelation['mimework'],
                'columns' => $rows,
                'indexes' => Index::getFromTable($tableName, $this->db),
            ];
        }

        $this->render('database/data_dictionary/index', [
            'database' => $this->db,
            'comment' => $comment,
            'tables' => $tables,
        ]);
    }
}

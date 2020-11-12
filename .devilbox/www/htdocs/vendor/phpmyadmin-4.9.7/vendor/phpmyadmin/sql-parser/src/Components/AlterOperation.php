<?php

/**
 * Parses an alter operation.
 */

namespace PhpMyAdmin\SqlParser\Components;

use PhpMyAdmin\SqlParser\Component;
use PhpMyAdmin\SqlParser\Parser;
use PhpMyAdmin\SqlParser\Token;
use PhpMyAdmin\SqlParser\TokensList;

/**
 * Parses an alter operation.
 *
 * @category   Components
 *
 * @license    https://www.gnu.org/licenses/gpl-2.0.txt GPL-2.0+
 */
class AlterOperation extends Component
{
    /**
     * All database options.
     *
     * @var array
     */
    public static $DB_OPTIONS = array(
        'CHARACTER SET' => array(
            1,
            'var'
        ),
        'CHARSET' => array(
            1,
            'var'
        ),
        'DEFAULT CHARACTER SET' => array(
            1,
            'var'
        ),
        'DEFAULT CHARSET' => array(
            1,
            'var'
        ),
        'UPGRADE' => array(
            1,
            'var'
        ),
        'COLLATE' => array(
            2,
            'var'
        ),
        'DEFAULT COLLATE' => array(
            2,
            'var'
        )
    );

    /**
     * All table options.
     *
     * @var array
     */
    public static $TABLE_OPTIONS = array(
        'ENGINE' => array(
            1,
            'var='
        ),
        'AUTO_INCREMENT' => array(
            1,
            'var='
        ),
        'AVG_ROW_LENGTH' => array(
            1,
            'var'
        ),
        'MAX_ROWS' => array(
            1,
            'var'
        ),
        'ROW_FORMAT' => array(
            1,
            'var'
        ),
        'COMMENT' => array(
            1,
            'var'
        ),
        'ADD' => 1,
        'ALTER' => 1,
        'ANALYZE' => 1,
        'CHANGE' => 1,
        'CHECK' => 1,
        'COALESCE' => 1,
        'CONVERT' => 1,
        'DISABLE' => 1,
        'DISCARD' => 1,
        'DROP' => 1,
        'ENABLE' => 1,
        'IMPORT' => 1,
        'MODIFY' => 1,
        'OPTIMIZE' => 1,
        'ORDER' => 1,
        'PARTITION' => 1,
        'REBUILD' => 1,
        'REMOVE' => 1,
        'RENAME' => 1,
        'REORGANIZE' => 1,
        'REPAIR' => 1,
        'UPGRADE' => 1,

        'COLUMN' => 2,
        'CONSTRAINT' => 2,
        'DEFAULT' => 2,
        'TO' => 2,
        'BY' => 2,
        'FOREIGN' => 2,
        'FULLTEXT' => 2,
        'KEY' => 2,
        'KEYS' => 2,
        'PARTITIONING' => 2,
        'PRIMARY KEY' => 2,
        'SPATIAL' => 2,
        'TABLESPACE' => 2,
        'INDEX' => 2
    );

    /**
     * All view options.
     *
     * @var array
     */
    public static $VIEW_OPTIONS = array(
        'AS' => 1,
    );

    /**
     * Options of this operation.
     *
     * @var OptionsArray
     */
    public $options;

    /**
     * The altered field.
     *
     * @var Expression
     */
    public $field;

    /**
     * Unparsed tokens.
     *
     * @var Token[]|string
     */
    public $unknown = array();

    /**
     * Constructor.
     *
     * @param OptionsArray $options options of alter operation
     * @param Expression   $field   altered field
     * @param array        $unknown unparsed tokens found at the end of operation
     */
    public function __construct(
        $options = null,
        $field = null,
        $unknown = array()
    ) {
        $this->options = $options;
        $this->field = $field;
        $this->unknown = $unknown;
    }

    /**
     * @param Parser     $parser  the parser that serves as context
     * @param TokensList $list    the list of tokens that are being parsed
     * @param array      $options parameters for parsing
     *
     * @return AlterOperation
     */
    public static function parse(Parser $parser, TokensList $list, array $options = array())
    {
        $ret = new self();

        /**
         * Counts brackets.
         *
         * @var int
         */
        $brackets = 0;

        /**
         * The state of the parser.
         *
         * Below are the states of the parser.
         *
         *      0 ---------------------[ options ]---------------------> 1
         *
         *      1 ----------------------[ field ]----------------------> 2
         *
         *      2 -------------------------[ , ]-----------------------> 0
         *
         * @var int
         */
        $state = 0;

        for (; $list->idx < $list->count; ++$list->idx) {
            /**
             * Token parsed at this moment.
             *
             * @var Token
             */
            $token = $list->tokens[$list->idx];

            // End of statement.
            if ($token->type === Token::TYPE_DELIMITER) {
                break;
            }

            // Skipping comments.
            if ($token->type === Token::TYPE_COMMENT) {
                continue;
            }

            // Skipping whitespaces.
            if ($token->type === Token::TYPE_WHITESPACE) {
                if ($state === 2) {
                    // When parsing the unknown part, the whitespaces are
                    // included to not break anything.
                    $ret->unknown[] = $token;
                }
                continue;
            }

            if ($state === 0) {
                $ret->options = OptionsArray::parse($parser, $list, $options);

                if ($ret->options->has('AS')) {
                    for (; $list->idx < $list->count; ++$list->idx) {
                        if ($list->tokens[$list->idx]->type === Token::TYPE_DELIMITER) {
                            break;
                        }
                        $ret->unknown[] = $list->tokens[$list->idx];
                    }
                    break;
                }

                $state = 1;
            } elseif ($state === 1) {
                $ret->field = Expression::parse(
                    $parser,
                    $list,
                    array(
                        'breakOnAlias' => true,
                        'parseField' => 'column'
                    )
                );
                if ($ret->field === null) {
                    // No field was read. We go back one token so the next
                    // iteration will parse the same token, but in state 2.
                    --$list->idx;
                }
                $state = 2;
            } elseif ($state === 2) {
                $array_key = '';
                if (is_string($token->value) || is_numeric($token->value)) {
                    $array_key = $token->value;
                } else {
                    $array_key = $token->token;
                }
                if ($token->type === Token::TYPE_OPERATOR) {
                    if ($token->value === '(') {
                        ++$brackets;
                    } elseif ($token->value === ')') {
                        --$brackets;
                    } elseif (($token->value === ',') && ($brackets === 0)) {
                        break;
                    }
                } elseif (! empty(Parser::$STATEMENT_PARSERS[$token->value])) {
                    // We have reached the end of ALTER operation and suddenly found
                    // a start to new statement, but have not find a delimiter between them

                    if (! ($token->value === 'SET' && $list->tokens[$list->idx - 1]->value === 'CHARACTER')) {
                        $parser->error(
                            'A new statement was found, but no delimiter between it and the previous one.',
                            $token
                        );
                        break;
                    }
                } elseif ((array_key_exists($array_key, self::$DB_OPTIONS)
                    || array_key_exists($array_key, self::$TABLE_OPTIONS))
                    && ! self::checkIfColumnDefinitionKeyword($array_key)
                ) {
                    // This alter operation has finished, which means a comma was missing before start of new alter operation
                    $parser->error(
                        'Missing comma before start of a new alter operation.',
                        $token
                    );
                    break;
                }
                $ret->unknown[] = $token;
            }
        }

        if ($ret->options->isEmpty()) {
            $parser->error(
                'Unrecognized alter operation.',
                $list->tokens[$list->idx]
            );
        }

        --$list->idx;

        return $ret;
    }

    /**
     * @param AlterOperation $component the component to be built
     * @param array          $options   parameters for building
     *
     * @return string
     */
    public static function build($component, array $options = array())
    {
        $ret = $component->options . ' ';
        if ((isset($component->field)) && ($component->field !== '')) {
            $ret .= $component->field . ' ';
        }
        $ret .= TokensList::build($component->unknown);

        return $ret;
    }

    /**
     * Check if token's value is one of the common keywords
     * between column and table alteration
     *
     * @param string $tokenValue Value of current token
     * @return bool
     */
    private static function checkIfColumnDefinitionKeyword($tokenValue)
    {
        $common_options = array(
            'AUTO_INCREMENT',
            'COMMENT',
            'DEFAULT',
            'CHARACTER SET',
            'COLLATE',
            'PRIMARY',
            'UNIQUE',
            'PRIMARY KEY',
            'UNIQUE KEY'
        );
        // Since these options can be used for
        // both table as well as a specific column in the table
        return in_array($tokenValue, $common_options);
    }
}

<?php

/**
 * Token utilities.
 */

namespace PhpMyAdmin\SqlParser\Utils;

use PhpMyAdmin\SqlParser\Lexer;
use PhpMyAdmin\SqlParser\Token;
use PhpMyAdmin\SqlParser\TokensList;

/**
 * Token utilities.
 *
 * @category   Token
 *
 * @license    https://www.gnu.org/licenses/gpl-2.0.txt GPL-2.0+
 */
class Tokens
{
    /**
     * Checks if a pattern is a match for the specified token.
     *
     * @param Token $token   the token to be matched
     * @param array $pattern the pattern to be matches
     *
     * @return bool
     */
    public static function match(Token $token, array $pattern)
    {
        // Token.
        if ((isset($pattern['token']))
            && ($pattern['token'] !== $token->token)
        ) {
            return false;
        }

        // Value.
        if ((isset($pattern['value']))
            && ($pattern['value'] !== $token->value)
        ) {
            return false;
        }

        if ((isset($pattern['value_str']))
            && (strcasecmp($pattern['value_str'], $token->value))
        ) {
            return false;
        }

        // Type.
        if ((isset($pattern['type']))
            && ($pattern['type'] !== $token->type)
        ) {
            return false;
        }

        // Flags.
        if ((isset($pattern['flags']))
            && (($pattern['flags'] & $token->flags) === 0)
        ) {
            return false;
        }

        return true;
    }

    public static function replaceTokens($list, array $find, array $replace)
    {
        /**
         * Whether the first parameter is a list.
         *
         * @var bool
         */
        $isList = $list instanceof TokensList;

        // Parsing the tokens.
        if (!$isList) {
            $list = Lexer::getTokens($list);
        }

        /**
         * The list to be returned.
         *
         * @var array
         */
        $newList = array();

        /**
         * The length of the find pattern is calculated only once.
         *
         * @var int
         */
        $findCount = count($find);

        /**
         * The starting index of the pattern.
         *
         * @var int
         */
        $i = 0;

        while ($i < $list->count) {
            // A sequence may not start with a comment.
            if ($list->tokens[$i]->type === Token::TYPE_COMMENT) {
                $newList[] = $list->tokens[$i];
                ++$i;
                continue;
            }

            /**
             * The index used to parse `$list->tokens`.
             *
             * This index might be running faster than `$k` because some tokens
             * are skipped.
             *
             * @var int
             */
            $j = $i;

            /**
             * The index used to parse `$find`.
             *
             * This index might be running slower than `$j` because some tokens
             * are skipped.
             *
             * @var int
             */
            $k = 0;

            // Checking if the next tokens match the pattern described.
            while (($j < $list->count) && ($k < $findCount)) {
                // Comments are being skipped.
                if ($list->tokens[$j]->type === Token::TYPE_COMMENT) {
                    ++$j;
                }

                if (!static::match($list->tokens[$j], $find[$k])) {
                    // This token does not match the pattern.
                    break;
                }

                // Going to next token and segment of find pattern.
                ++$j;
                ++$k;
            }

            // Checking if the sequence was found.
            if ($k === $findCount) {
                // Inserting new tokens.
                foreach ($replace as $token) {
                    $newList[] = $token;
                }

                // Skipping next `$findCount` tokens.
                $i = $j;
            } else {
                // Adding the same token.
                $newList[] = $list->tokens[$i];
                ++$i;
            }
        }

        return $isList ?
            new TokensList($newList) : TokensList::build($newList);
    }
}

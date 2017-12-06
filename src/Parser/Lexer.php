<?php declare(strict_types=1);

namespace App\Parser;

class Lexer extends \Doctrine\Common\Lexer
{
    const T_NONE = 1;
    const T_INTEGER = 2;
    const T_STRING = 3;
    const T_ACTION_SUBJECT = 4;

    const T_INVALID_ACTION = 200;
    const T_GOTO_ACTION = 201;
    const T_LOOKAT_ACTION = 202;
    const T_PICKUP_ACTION = 203;
    const T_OPEN_ACTION = 204;

    const T_WITH = 205;



    /**
     * Lexical catchable patterns.
     *
     * @return array
     */
    protected function getCatchablePatterns()
    {
        return [
            '[a-zA-Z0-9_]{0,}'
        ];
    }

    /**
     * Lexical non-catchable patterns.
     *
     * @return array
     */
    protected function getNonCatchablePatterns()
    {
        return [
            '\s+',
        ];
    }

    /**
     * Retrieve token type. Also processes the token value if necessary.
     *
     * @param string $value
     *
     * @return integer
     */
    protected function getType(&$value)
    {
        if (strcasecmp($value, 'go') === 0 || strcasecmp($value, 'goto') === 0) {
            return self::T_GOTO_ACTION;
        }

        if (strcasecmp($value, 'look') === 0) {
            return self::T_LOOKAT_ACTION;
        }

        if (in_array(strtolower($value), ['get', 'pickup'])) {
            return self::T_PICKUP_ACTION;
        }

        if (strcasecmp($value, 'open') === 0) {
            return self::T_OPEN_ACTION;
        }
        if (ctype_alnum($value)) {
            return self::T_ACTION_SUBJECT;
        }

        return self::T_NONE;
    }
}

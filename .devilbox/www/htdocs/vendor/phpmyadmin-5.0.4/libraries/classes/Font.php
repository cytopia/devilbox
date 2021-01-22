<?php
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * Class with Font related methods.
 *
 * @package PhpMyAdmin
 */
declare(strict_types=1);

namespace PhpMyAdmin;

/**
 * Class with Font related methods.
 *
 * @package PhpMyAdmin
 */
class Font
{
    /**
     * Get list with characters and the corresponding width modifiers.
     *
     * @return array with characters and corresponding width modifier
     * @access public
     */
    public function getCharLists(): array
    {
        // list of characters and their width modifiers
        $charLists = [];

        //ijl
        $charLists[] = [
            "chars" => [
                "i",
                "j",
                "l",
            ],
            "modifier" => 0.23,
        ];
        //f
        $charLists[] = [
            "chars" => ["f"],
            "modifier" => 0.27,
        ];
        //tI
        $charLists[] = [
            "chars" => [
                "t",
                "I",
            ],
            "modifier" => 0.28,
        ];
        //r
        $charLists[] = [
            "chars" => ["r"],
            "modifier" => 0.34,
        ];
        //1
        $charLists[] = [
            "chars" => ["1"],
            "modifier" => 0.49,
        ];
        //cksvxyzJ
        $charLists[] = [
            "chars" => [
                "c",
                "k",
                "s",
                "v",
                "x",
                "y",
                "z",
                "J",
            ],
            "modifier" => 0.5,
        ];
        //abdeghnopquL023456789
        $charLists[] = [
            "chars" => [
                "a",
                "b",
                "d",
                "e",
                "g",
                "h",
                "n",
                "o",
                "p",
                "q",
                "u",
                "L",
                "0",
                "2",
                "3",
                "4",
                "5",
                "6",
                "7",
                "8",
                "9",
            ],
            "modifier" => 0.56,
        ];
        //FTZ
        $charLists[] = [
            "chars" => [
                "F",
                "T",
                "Z",
            ],
            "modifier" => 0.61,
        ];
        //ABEKPSVXY
        $charLists[] = [
            "chars" => [
                "A",
                "B",
                "E",
                "K",
                "P",
                "S",
                "V",
                "X",
                "Y",
            ],
            "modifier" => 0.67,
        ];
        //wCDHNRU
        $charLists[] = [
            "chars" => [
                "w",
                "C",
                "D",
                "H",
                "N",
                "R",
                "U",
            ],
            "modifier" => 0.73,
        ];
        //GOQ
        $charLists[] = [
            "chars" => [
                "G",
                "O",
                "Q",
            ],
            "modifier" => 0.78,
        ];
        //mM
        $charLists[] = [
            "chars" => [
                "m",
                "M",
            ],
            "modifier" => 0.84,
        ];
        //W
        $charLists[] = [
            "chars" => ["W"],
            "modifier" => 0.95,
        ];
        //" "
        $charLists[] = [
            "chars" => [" "],
            "modifier" => 0.28,
        ];

        return $charLists;
    }

    /**
     * Get width of string/text
     *
     * The text element width is calculated depending on font name
     * and font size.
     *
     * @param string     $text      string of which the width will be calculated
     * @param string     $font      name of the font like Arial,sans-serif etc
     * @param integer    $fontSize  size of font
     * @param array|null $charLists list of characters and their width modifiers
     *
     * @return integer width of the text
     * @access public
     */
    public function getStringWidth(
        string $text,
        string $font,
        int $fontSize,
        ?array $charLists = null
    ): int {
        if (empty($charLists)
            || ! isset($charLists[0]["chars"]) || ! is_array($charLists[0]["chars"])
            || ! isset($charLists[0]["modifier"])
        ) {
            $charLists = $this->getCharLists();
        }

        /*
         * Start by counting the width, giving each character a modifying value
         */
        $count = 0;

        foreach ($charLists as $charList) {
            $count += ((mb_strlen($text)
                - mb_strlen(str_replace($charList["chars"], "", $text))
                ) * $charList["modifier"]);
        }

        $text  = str_replace(" ", "", $text);//remove the " "'s
        //all other chars
        $count += (mb_strlen(preg_replace("/[a-z0-9]/i", "", $text)) * 0.3);

        $modifier = 1;
        $font = mb_strtolower($font);
        switch ($font) {
        /*
         * no modifier for arial and sans-serif
         */
            case 'arial':
            case 'sans-serif':
                break;
        /*
         * .92 modifier for time, serif, brushscriptstd, and californian fb
         */
            case 'times':
            case 'serif':
            case 'brushscriptstd':
            case 'californian fb':
                $modifier = .92;
                break;
        /*
         * 1.23 modifier for broadway
         */
            case 'broadway':
                $modifier = 1.23;
                break;
        }
        $textWidth = $count * $fontSize;
        return (int) ceil($textWidth * $modifier);
    }
}

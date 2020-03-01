<?php

/**
 * Custom character sets in collation select boxes.
 *
 * @link https://github.com/pematon/adminer-plugins
 *
 * @author Peter Knut
 * @copyright 2015-2018 Pematon, s.r.o. (http://www.pematon.com/)
 */
class AdminerCollations
{
    /** @var array */
    private $characterSets;

    /**
     * @param array $characterSets Array of allowed character sets.
     */
    public function __construct(array $characterSets = ["utf8_unicode_ci", "utf8mb4_general_ci"])
    {
        $this->characterSets = $characterSets;
    }

    /**
     * Prints HTML code inside <head>.
     */
    public function head()
    {
        if (empty($this->characterSets)) {
            return;
        }

        ?>

        <script <?php echo nonce(); ?>>
            (function(document) {
                "use strict";

                const characterSets = [
                    <?php
                        echo "'(" . lang('collation') . ")'";

                        foreach ($this->characterSets as $characterSet) {
                            echo ", '" . $characterSet . "'";
                        }
                    ?>
                ];

                document.addEventListener("DOMContentLoaded", init, false);

                function init() {
                    var selects = document.querySelectorAll("select[name='Collation'], select[name*='collation']");

                    for (var i = 0; i < selects.length; i++) {
                        replaceOptions(selects[i]);
                    }
                }

                function replaceOptions(select) {
                    var selectedSet = getSelectedSet(select);
                    var html = '';
                    var hasSelected = false;

                    for (var i = 0; i < characterSets.length; i++) {
                        if (characterSets[i] === selectedSet) {
                            hasSelected = true;
                            html += '<option selected="selected">' + characterSets[i] + '</option>';
                        } else {
                            html += '<option>' + characterSets[i] + '</option>';
                        }
                    }

                    if (!hasSelected && selectedSet !== "") {
                        html += '<option selected="selected">' + selectedSet + '</option>';
                    }

                    select.innerHTML = html;
                }

                function getSelectedSet(select) {
                    var options = select.getElementsByTagName("option");

                    for (var i = 0; i < options.length; i++) {
                        if (options[i].selected) {
                            return options[i].innerHTML.trim();
                        }
                    }

                    return "";
                }
            })(document);

        </script>

        <?php
    }
}

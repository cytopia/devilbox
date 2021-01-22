<?php
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * Functionality for the navigation tree
 *
 * @package PhpMyAdmin-Navigation
 */
namespace PhpMyAdmin\Navigation\Nodes;

use PhpMyAdmin\Navigation\NodeFactory;
use PhpMyAdmin\Util;

/**
 * Represents a container for functions nodes in the navigation tree
 *
 * @package PhpMyAdmin-Navigation
 */
class NodeFunctionContainer extends NodeDatabaseChildContainer
{
    /**
     * Initialises the class
     */
    public function __construct()
    {
        parent::__construct(__('Functions'), Node::CONTAINER);
        $this->icon = Util::getImage('b_routines', __('Functions'));
        $this->links = array(
            'text' => 'db_routines.php?server=' . $GLOBALS['server']
                . '&amp;db=%1$s&amp;type=FUNCTION',
            'icon' => 'db_routines.php?server=' . $GLOBALS['server']
                . '&amp;db=%1$s&amp;type=FUNCTION',
        );
        $this->real_name = 'functions';

        $new_label = _pgettext('Create new function', 'New');
        $new = NodeFactory::getInstance(
            'Node',
            $new_label
        );
        $new->isNew = true;
        $new->icon = Util::getImage('b_routine_add', $new_label);
        $new->links = array(
            'text' => 'db_routines.php?server=' . $GLOBALS['server']
                . '&amp;db=%2$s&add_item=1&amp;item_type=FUNCTION',
            'icon' => 'db_routines.php?server=' . $GLOBALS['server']
                . '&amp;db=%2$s&add_item=1&amp;item_type=FUNCTION',
        );
        $new->classes = 'new_function italics';
        $this->addChild($new);
    }
}

<?php

use Twig\Node\Expression\Test\ConstantTest;

class_exists('Twig\Node\Expression\Test\ConstantTest');

@trigger_error('Using the "Twig_Node_Expression_Test_Constant" class is deprecated since Twig version 2.7, use "Twig\Node\Expression\Test\ConstantTest" instead.', \E_USER_DEPRECATED);

if (false) {
    /** @deprecated since Twig 2.7, use "Twig\Node\Expression\Test\ConstantTest" instead */
    class Twig_Node_Expression_Test_Constant extends ConstantTest
    {
    }
}

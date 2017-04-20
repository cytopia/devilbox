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
namespace Gitonomy\Git\Tests;

use Gitonomy\Git\PushReference;

class PushReferenceTest extends AbstractTest
{
    const CREATE = 1;
    const DELETE = 2;
    const FORCE = 4;
    const FAST_FORWARD = 8;

    public function provideIsers()
    {
        // mask: force fastforward create delete
        return array(
            array('foo', PushReference::ZERO,          self::LONGFILE_COMMIT,        self::CREATE),
            array('foo', self::LONGFILE_COMMIT,        PushReference::ZERO,          self::DELETE),
            array('foo', self::LONGFILE_COMMIT,        self::BEFORE_LONGFILE_COMMIT, self::FORCE),
            array('foo', self::BEFORE_LONGFILE_COMMIT, self::LONGFILE_COMMIT,        self::FAST_FORWARD),
        );
    }

    /**
     * @dataProvider provideIsers
     */
    public function testIsers($reference, $before, $after, $mask)
    {
        $reference = new PushReference(self::createFoobarRepository(), $reference, $before, $after);
        $this->assertEquals($mask & self::CREATE,        $reference->isCreate(),       'Create value is correct.');
        $this->assertEquals($mask & self::DELETE,        $reference->isDelete(),       'Delete value is correct.');
        $this->assertEquals($mask & self::FORCE,         $reference->isForce(),        'Force value is correct.');
        $this->assertEquals($mask & self::FAST_FORWARD,  $reference->isFastForward(),  'FastForward value is correct.');
    }

    /**
     * @dataProvider provideFoobar
     */
    public function testLog($repository)
    {
        $ref = new PushReference($repository, 'foo', self::INITIAL_COMMIT, self::LONGFILE_COMMIT);

        $log = $ref->getLog()->getCommits();
        $this->assertEquals(7, count($log), '7 commits in log');
        $this->assertEquals('add a long file', $log[0]->getShortMessage(), 'First commit is correct');
    }

    /**
     * @dataProvider provideFoobar
     */
    public function testLogWithExclude($repository)
    {
        $ref = new PushReference($repository, 'foo', PushReference::ZERO, self::LONGFILE_COMMIT);

        $log = $ref->getLog(array(self::INITIAL_COMMIT))->getCommits();
        $this->assertEquals(7, count($log), '7 commits in log');
        $this->assertEquals('add a long file', $log[0]->getShortMessage(), 'First commit is correct');
    }
}

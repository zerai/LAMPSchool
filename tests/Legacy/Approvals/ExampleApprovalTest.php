<?php

namespace LampSchool\Tests\Legacy\Approvals;

use ApprovalTests\Approvals;
use PHPUnit\Framework\TestCase;

class ExampleApprovalTest extends TestCase
{
    public function testName()
    {
        $list = ['zero', 'one', 'two', 'three', 'four', 'five'];

        Approvals::verifyList($list);
    }


}
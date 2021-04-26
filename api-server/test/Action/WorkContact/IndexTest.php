<?php

namespace HyperfTest\Action\WorkContact;

use HyperfTest\AdminTestCase;
use PHPUnit\Framework\TestCase;

class IndexTest extends AdminTestCase
{

    public function testHandle()
    {
        /**
         * @see Index::handle();
         */
        $result = $this->doGet(
            '/workContact/index',
            [
            ]
        );
        echo $this->pretty($result);
    }
}

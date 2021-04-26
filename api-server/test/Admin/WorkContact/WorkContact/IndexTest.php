<?php

namespace HyperfTest\Admin\WorkContact\WorkContact;

use App\Action\Admin\WorkContact\WorkContact\Index;
use HyperfTest\AdminTestCase;

class IndexTest extends AdminTestCase
{

    public function testHandle()
    {
        /**
         * @see Index::handle();
         */
        $result = $this->doGet(
            '/admin/workContact/index',
            [
            ]
        );
        echo $this->pretty($result);
    }
}

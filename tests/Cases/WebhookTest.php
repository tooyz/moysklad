<?php

namespace Tests\Cases;

use MoySklad\Entities\Misc\Webhook;

require_once "TestCase.php";

class WebhookTest extends TestCase{
    public function setUp()
    {
        parent::setUp();
    }

    public function testWebhookList(){
        $webhooks = Webhook::query($this->sklad)->getList();
        $this->assertNotNull($webhooks);
    }

}

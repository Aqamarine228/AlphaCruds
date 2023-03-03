<?php

namespace Aqamarine\AlphaCruds\Tests;

class RoutesTest extends AlphaCrudsTestCase
{
    public function testItOpensRoutes(): void
    {
        $this->get(route('alphacruds.crud-generator'))->assertStatus(200);
        $this->get(route('alphacruds.translated-crud-generator'))->assertStatus(200);
        $this->get(route('alphacruds.api-crud-generator'))->assertStatus(200);
    }
}

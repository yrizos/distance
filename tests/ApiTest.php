<?php

class ApiTest extends PHPUnit_Framework_TestCase {

    public function testConstructorAcceptsApiKey() {
        $api = new \DistanceMatrix\Api('apikey');

        $this->assertEquals('apikey', $api->getApiKey());
    }

    /**
     * @expectedException RuntimeException
     */
    public function testEmptyOriginShouldThrowException() {
        $api = new \DistanceMatrix\Api('apikey');
        $api
            ->addDestination('Address 1')
            ->run();
    }

    /**
     * @expectedException RuntimeException
     */
    public function testEmptyDestinationShouldThrowException() {
        $api = new \DistanceMatrix\Api('apikey');
        $api
            ->addOrigin('Address 1')
            ->run();
    }

    public function testLanguageSetter() {
        $api = new \DistanceMatrix\Api('apikey');

        $api->setLanguage('en');

        $this->assertEquals('en', $api->getLanguage());
    }

    public function testMode() {
        $api = new \DistanceMatrix\Api('apikey');

        $api->setMode('walking');

        $this->assertEquals('walking', $api->getMode());
    }

    public function testUnits() {
        $api = new \DistanceMatrix\Api('apikey');

        $api->setUnits('imperial');

        $this->assertEquals('imperial', $api->getUnits());
    }

    /**
     *
     * @TODO Proper mocking of Google's API
     */
    public function testRun() {
        $api = new \DistanceMatrix\Api('apikey');

        $results = $api
            ->addDestination('Αγιου Γεωργίου 5, Θέρμη, Θεσσαλονίκη')
            ->addOrigin('Αριστοτέλους 35, Εύοσμος, Θεσσαλονίκη')
            ->run();

        $results = json_decode($results, true);

        $this->assertEquals('REQUEST_DENIED', $results['status']);
    }

}
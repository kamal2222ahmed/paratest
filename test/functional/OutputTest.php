<?php

declare(strict_types=1);

class OutputTest extends FunctionalTestBase
{
    /**
     * @var ParaTestInvoker
     */
    protected $paratest;

    public function setUp()
    {
        parent::setUp();
        $this->paratest = new ParaTestInvoker(
            $this->fixture('failing-tests/UnitTestWithClassAnnotationTest.php'),
            BOOTSTRAP
        );
    }

    public function testDefaultMessagesDisplayed()
    {
        $output = $this->paratest->execute(['p' => 5])->getOutput();
        $this->assertContains('Running phpunit in 5 processes with ' . PHPUNIT, $output);
        $this->assertContains('Configuration read from ' . getcwd() . DS . 'phpunit.xml.dist', $output);
        $this->assertRegExp('/[.F]{4}/', $output);
    }

    public function testMessagePrintedWhenInvalidConfigFileSupplied()
    {
        $output = $this->paratest
            ->execute(['configuration' => 'nope.xml'])
            ->getOutput()
        ;
        $this->assertContains('Could not read "nope.xml"', $output);
    }

    public function testMessagePrintedWhenFunctionalModeIsOn()
    {
        $output = $this->paratest
            ->execute(['functional', 'p' => 5])
            ->getOutput()
        ;
        $this->assertContains('Running phpunit in 5 processes with ' . PHPUNIT, $output);
        $this->assertContains('Functional mode is ON.', $output);
        $this->assertRegExp('/[.F]{4}/', $output);
    }

    public function testProcCountIsReportedWithProcOption()
    {
        $output = $this->paratest->execute(['p' => 1])
            ->getOutput()
        ;
        $this->assertContains('Running phpunit in 1 process with ' . PHPUNIT, $output);
        $this->assertRegExp('/[.F]{4}/', $output);
    }
}

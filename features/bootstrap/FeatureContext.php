<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use SixtyNine\Fibonacci\Fibonacci;
use Webmozart\Assert\Assert;

require_once __DIR__.'/../../vendor/autoload.php';

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    /** @var Fibonacci */
    private $calculator;
    /** @var int */
    private $lastResult;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    /**
     * @Given I have a Fibonacci number calculator
     */
    public function iHaveAFibonacciNumberCalculator()
    {
        $this->calculator = new Fibonacci();
    }

    /**
     * @When I calculate the Fibonacci number of :arg1
     */
    public function iCalculateTheFibonacciNumberOf($arg1)
    {
        $this->lastResult = $this->calculator->calc($arg1);
    }

    /**
     * @Then the result should be :arg1
     */
    public function theResultShouldBe($arg1)
    {
        Assert::eq($this->lastResult, $arg1);
    }

    /**
     * @When /^(?:I call|calling) Fibonacci\((-?\d+)\) the result (?:is|should be) (\d+)$/
     */
    public function iCallFibonacciTheResultIs($input, $output)
    {
        $result = $this->calculator->calc($input);
        Assert::eq($result, $output);
    }

}

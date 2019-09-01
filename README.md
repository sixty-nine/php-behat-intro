# Introduction to Behat

## Installation

```bash
composer require --dev behat/behat
```

## Start up

Behat can "self-initialize" with the following command:

```bash
vendor/bin/behat --init
```

This will create the `features` directory and a default feature context
in `features/bootstrap/FeatureContext.php`.

## Writing our first scenario

In BDD we test *Features*.

Features are written with Gherkin, a domain specific language, very
close to the natural language.

The structure of a feature looks like this:

```
# features/CalculateFibonacci.feature

Feature: Calculate Fibonacci numbers

  As a tester
  I want to calculate Fibonacci numbers
  In order to test the given code

  Background:
      Given I have a Fibonacci number calculator

  Scenario: The first number of the sequence must be 0
       When I calculate the Fibonacci number of 0
       Then the result should be 0
    
```

Remarks:

  * The feature has a title describing what we are testing.
  * On top, the feature is described in the "User story" format.
    * As an actor
    * I want to do something
    * So that I achieve a goal
  * An actual test is a `Scenario`.
  * A scenario is composed of *steps* starting with keywords: `Given`, `When`, `Then`, ...
  * The steps defined in `Background` are executed before each scenario.
    * That's similar to the `TestCase::setUp()` function of PHPUnit.

You can use the command `vendor/bin/behat --story-syntax` to see the full syntax:

```
[Business Need|Feature|Ability]: Internal operations
  In order to stay secret
  As a secret organization
  We need to be able to erase past agents' memory

  Background:
    [Given|*] there is agent A
    [And|*] there is agent B

  [Scenario|Example]: Erasing agent memory
    [Given|*] there is agent J
    [And|*] there is agent K
    [When|*] I erase agent K's memory
    [Then|*] there should be agent J
    [But|*] there should not be agent K

  [Scenario Template|Scenario Outline]: Erasing other agents' memory
    [Given|*] there is agent <agent1>
    [And|*] there is agent <agent2>
    [When|*] I erase agent <agent2>'s memory
    [Then|*] there should be agent <agent1>
    [But|*] there should not be agent <agent2>

    [Scenarios|Examples]:
      | agent1 | agent2 |
      | D      | M      |
```

## Running behat

Let's run behat now.

![](/images/behat1.gif)

Behat has detected one scenario with two undefined steps and proposes
us a stub for those steps.

Let's take a look at the first.

```php
<?php
/**
 * @When I calculate the Fibonacci number of :arg1
 */
public function iCalculateTheFibonacciNumberOf($arg1)
{
    throw new PendingException();
}
```

That look like a normal PHP function taking a single argument
and throwing an exception.

The magic happen in the annotation in the comment. 
`@When` defines the match between our step and the PHP code.

Let's add the two methods that Behat proposes to our FeatureContext class
and run Behat again.

![](/images/behat2.gif)

This time Behat does no longer complain about missing steps, but about 
pending steps.

A pending step is triggered by the `PendingException` we've seen above.
It means we now have to actually implement the test.

## Making the step pass

First thing to do, use the composer autoloader.
Add this line on top of the feature context:

```php
require_once __DIR__.'/../../vendor/autoload.php';
```

Then let's implement the first step which is used in the `Background`.

```php
<?php
/**
 * @Given I have a Fibonacci number calculator
 */
public function iHaveAFibonacciNumberCalculator()
{
    $this->calculator = new Fibonacci();
}
```

Now if we run Behat one more time:

![](/images/behat3.gif)

We finally have a green step :)

Let's implement the two remaining steps. 

```php
<?php
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
```

Behat not being a test framework, it does not supply assertions. It is up to 
you to use any assertion framework you want. Here I use `webmozart/assert`.

Throwing an exception in a step will make the step fail.

You start bein used to it, let's run Behat:

![](/images/behat4.gif)

All green \o/

Congratulation you are now a Behat user.

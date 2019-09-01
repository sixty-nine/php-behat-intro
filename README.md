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

### Features and scenarii

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

### The first execution

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

### Pending steps

Let's add the two methods that Behat proposes to our FeatureContext class
and run Behat again.

![](/images/behat2.gif)

This time Behat does no longer complain about missing steps, but about 
pending steps.

A pending step is triggered by the `PendingException` we've seen above.
It means we now have to actually implement the test.

### Passing steps

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

### Step by step

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

You start being used to it, let's run Behat:

![](/images/behat4.gif)

All green \o/

Congratulation you are now a Behat user.

### The magic of behat

Let's write some more scenarii.

```
  Scenario: The second number of the sequence must be 1
       When I calculate the Fibonacci number of 1
       Then the result should be 1

  Scenario: The following numbers must match
       When I calculate the Fibonacci number of 2
       Then the result should be 1
       When I calculate the Fibonacci number of 3
       Then the result should be 2
       When I calculate the Fibonacci number of 4
       Then the result should be 3
```

![](/images/behat5.gif)

We didn't need to write more PHP code because we use sentences matching
our steps definitions in the feature context and Behat recognise them.

## Going further

### Regexp step definitions

You can use regexp in the steps definitions so that only one step definition
is required for the following scenario.

```
Scenario: Different people use different language
   When I call Fibonacci(-1) the result is 0
   When I call Fibonacci(5) the result should be 5
   When calling Fibonacci(6) the result should be 8
```

```php
<?php
/**
 * @When /^(?:I call|calling) Fibonacci\((-?\d+)\) the result (?:is|should be) (\d+)$/
 */
public function iCallFibonacciTheResultIs($input, $output)
{
    $result = $this->calculator->calc($input);
    Assert::eq($result, $output);
}
```

### Transformers

Let's say we have a step to define the current date.

```
Background:
    Given we write the dates normally

Scenario:
    Given today is 26-06-1969
```

The following step would match this code. 

```php
<?php
/**
 * @Given /^today is (\d?\d-\d?\d-\d\d\d\d)$/
 */
public function todayIs(string $date)
{
    $today = $date;
}
```

But what if we want to manipulate dates in our code?

It is possible to define *transformers* that will match a given regexp and transform the input.

```php
<?php
/**
 * @Transform /^(\d?\d-\d?\d-\d\d\d\d)$/
 */
public function castDate(string $date)
{
    return new DateTimeImmutable($date);
}

/**
 * @Given /^today is (.*)$/
 */
public function todayIs(DateTimeImmutable $date)
{
    $today = $date;
}
```

Note how the matcher of `todayIs` was changed from `(\d?\d-\d?\d-\d\d\d\d)` to `(.*)`.
This is because a transformer only matches if it's more specific that what is in the step 
definition.

### Tables

Soon

### Localisation

Soon
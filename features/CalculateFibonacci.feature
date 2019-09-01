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

    Scenario: Different people use different language
       When I call Fibonacci(-1) the result is 0
       When I call Fibonacci(5) the result should be 5
       When calling Fibonacci(6) the result should be 8

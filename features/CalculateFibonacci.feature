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

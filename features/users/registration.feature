Feature: Registration
  In order to use application
  As a user
  I need to be able to register

  Scenario: Register
    When I fill in username with "john.doe"
    And I fill in email with "john.doe@example.com"
    And I fill in password with "123qwe"
    And I fill in firstName with "John"
    And I fill in lastName with "Doe"
    And I send RegisterUser message
    Then I should have been registered
    And email which contains confirmation token should be sent to me
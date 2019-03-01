Feature: Change Email
  In order to use different email
  As user
  I should be able to change email

  Scenario: Change Email
    Given registered user with "john.galt@example.com" email and "123qwe" password
    When I fill in email with "j.galt@example.com"
    When I fill in password with "123qwe"
    And I send ChangeEmail message
    Then my email should have been changed

  Scenario: Change Email Failure due to wrong password
    Given registered user with "john.galt@example.com" email and "123qwe" password
    When I fill in email with "j.galt@example.com"
    When I fill in password with "456qwe"
    And I send ChangeEmail message
    Then my email "john.galt@example.com" should not be changed
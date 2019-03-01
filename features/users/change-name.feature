Feature: Change name
  In order to fix typos in my name
  As user
  I should be able to change name

  Scenario: Change name
    Given registered user named Jane Doe
    When I fill in firstName with "Anna"
    And I fill in lastName with "Smith"
    And I send ChangeName message
    Then my name should have been changed
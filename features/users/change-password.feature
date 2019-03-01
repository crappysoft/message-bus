Feature: Change Password
  In order to change compromised password
  As user
  I should be able to change password

  Scenario:
    Given registered user with password "123qwe"
    When I fill in currentPassword with "123qwe"
    And I fill in newPassword with "321abc"
    And I send ChangePassword message
    Then my password should be changed
    And notification email should be sent to me

  Scenario: Change Password Failure due to wrong password
    Given registered user with password "123qwe"
    When I fill in currentPassword with "321qwe"
    And I fill in newPassword with "321abc"
    And I send ChangePassword message
    Then my password "123qwe" should not be changed
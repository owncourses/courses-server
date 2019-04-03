Feature:
  In order to work with user promote command
  As a terminal user
  I want to use users promote command

  Scenario: It adds new role to selected user
    Given the following Users:
      | firstName | lastName | email            | password     |
      | Test      | User     | test@example.com | testPassword |
    When I run the "app:user:promote" command with options:
      | user | test@example.com |
      | role | ROLE_ADMIN       |
    Then the command output should be "Role ROLE_ADMIN was successfully added to user test@example.com"

    When I run the "app:user:promote" command with options:
      | user | test@example.com |
      | role | ROLE_ADMIN       |
    Then the command output should be "User test@example.com already have role ROLE_ADMIN"

    When I run the "app:user:promote" command with options:
      | user | nonexisting@example.com |
      | role | ROLE_ADMIN       |
    Then the command exception message should be "User with provided email was not found!"

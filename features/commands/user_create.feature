Feature:
  In order to work with user create command
  As a terminal user
  I want to use users promote command

  Scenario: It creates new user
    When I run the "app:user:create" command with options:
      | email     | test@example.com |
      | password  | someTestPassword |
      | firstName | Test             |
      | lastName  | User             |
    Then the command output should be "User was created successfully."

  Scenario: It fails when parameters are missing
    When I run the "app:user:create" command with options:
      | email     | test@example.com |
      | password  | someTestPassword |
    Then the command exception message should be "Not enough arguments"

  Scenario: It fails when user already exists
    Given the following Users:
      | firstName | lastName | email            | password     |
      | Test      | User     | test@example.com | testPassword |

    When I run the "app:user:create" command with options:
      | email     | test@example.com |
      | password  | someTestPassword |
      | firstName | Test             |
      | lastName  | User             |
    Then the command exception message should be "User with provided email already exists!"

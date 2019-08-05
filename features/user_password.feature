Feature:
  In order to work with user passwords
  As a user
  I want to use users passwords reset API

  Scenario: It sends reset password link
    Given the following Users:
      | firstName | lastName | email            | password     |
      | Test      | User     | test@example.com | testPassword |
    When I add 'content-type' header equal to 'application/json'
    And I send a "POST" request to "/api/users/password/reset_request" with body:
    """
    {
      "email": "test@example.com"
    }
    """
    Then the response should be in JSON
    And the response status code should be 200

  Scenario: It handles reset password for not existing user
    When I add 'content-type' header equal to 'application/json'
    And I send a "POST" request to "/api/users/password/reset_request" with body:
    """
    {
      "email": "false@example.com"
    }
    """
    Then the response should be in JSON
    And the response status code should be 404
    And the JSON node message should be equal to "User was not found"


  Scenario: It resets user password
    Given the following Users:
      | firstName | lastName | email            | password     | passwordResetToken    |
      | Test      | User     | test@example.com | testPassword | es5676r76_ete45df6743 |
    When I add 'content-type' header equal to 'application/json'
    And I send a "POST" request to "/api/users/password/reset?token=es5676r76_ete45df6743" with body:
    """
    {
      "password": "newPassword",
      "repeatedPassword": "newPassword"
    }
    """
    Then the response should be in JSON
    And the response status code should be 200

    When I add 'content-type' header equal to 'application/json'
    And I send a "POST" request to "/api/login_check" with body:
    """
    {
      "username": "test@example.com",
      "password": "newPassword"
    }
    """
    Then the response should be in JSON
    And the response status code should be 200
    And the JSON node "token" should exist

    When I add 'content-type' header equal to 'application/json'
    And I send a "POST" request to "/api/login_check" with body:
    """
    {
      "username": "test@example.com",
      "password": "testPassword"
    }
    """
    And the response status code should be 401

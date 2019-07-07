Feature:
  In order to provide integration layer with multiple services
  As a developer
  I want to use zapier to integrate OwnCourses with other services

  Scenario: I want to authorize zapier integration
    When I add 'content-type' header equal to 'application/json'
    And I add 'x-api-key' header equal to 'wrong-api-key'
    When I send a "POST" request to "/api/integration/authorization"
    Then the response should be in JSON
    Then the response status code should be 403

    When I add 'content-type' header equal to 'application/json'
    And I add 'x-api-key' header equal to 'test-api-key'
    When I send a "POST" request to "/api/integration/authorization"
    Then the response should be in JSON
    Then the response status code should be 200

  Scenario: It should register new user
    And I add 'content-type' header equal to 'application/json'
    And I add 'x-api-key' header equal to 'test-api-key'
    And I send a "POST" request to "/api/integration/users/register" with body:
    """
    {
      "email": "newuser@example.com",
      "firstName": "New",
      "lastName": "User"
    }
    """
    Then the response should be in JSON
    And the response status code should be 201

Feature:
  In order to provide integration layer with multiple services
  As a developer
  I want to use zapier to integrate OwnCourses with other services

  Scenario: I want to authorize zapier integration
    When I add 'content-type' header equal to 'application/json'
    When I send a "POST" request to "/api/integration/authorization" with body:
    """
        {
          "api_key": "wrong-api-key"
        }
    """
    Then the response should be in JSON
    Then the response status code should be 403

    When I add 'content-type' header equal to 'application/json'
    When I send a "POST" request to "/api/integration/authorization" with body:
    """
        {
          "api_key": "test-api-key"
        }
    """
    Then the response should be in JSON
    Then the response status code should be 200

Feature:
  In order to provide integration layer with multiple services
  As a developer
  I want to use external system to integrate OwnCourses with other services

  Scenario: I want to authorize integration
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

  Scenario: It should register new user and assign course by title to him
    Given the following Courses:
      | title       | description             | coverImage       |
      | Test course | Test course description | course_cover.png |
    And I add 'content-type' header equal to 'application/json'
    And I add 'x-api-key' header equal to 'test-api-key'
    And I send a "POST" request to "/api/integration/users/register" with body:
    """
    {
      "email": "newuser@example.com",
      "firstName": "New",
      "lastName": "User",
      "course": "Test course"
    }
    """
    Then the response should be in JSON
    And the response status code should be 201
    And the JSON node "courses[0].title" should be equal to "Test course"
    And At least 1 email should be sent
    And Mail with title "Welcome in OwnCourses" should be sent

  Scenario: It should register new user and assign course by sku to him
    Given the following Courses:
      | title       | description             | coverImage       | sku |
      | Test course | Test course description | course_cover.png | 001 |
    And I add 'content-type' header equal to 'application/json'
    And I add 'x-api-key' header equal to 'test-api-key'
    And I send a "POST" request to "/api/integration/users/register" with body:
    """
    {
      "email": "newuser@example.com",
      "firstName": "New",
      "lastName": "User",
      "course": "001"
    }
    """
    Then the response should be in JSON
    And the response status code should be 201
    And the JSON node "courses[0].title" should be equal to "Test course"

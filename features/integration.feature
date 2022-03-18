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

  Scenario: It should register new user then assign and remove course by title for him
    Given the following Courses:
      | title              | description             | coverImage       |
      | Test course        | Test course description | course_cover.png |
      | Test second course | Test course description | course_cover.png |
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

    Given that "newuser@example.com" user have "Test second course" course

    Given Course "Test course" and module "Test Module" and id "07a2f327-103a-11e9-8025-00ff5d11a779"
    Given Lesson "Test lesson" in "Test Module" with description "Test lesson description" and id "e7f48f24-a5b7-4b8b-b491-258ad546f8bc" and embed code:
    """
    <iframe width='500px' height='294px' src='https://player.vimeo.com/video/225434434?'></iframe>
    """
    When I am authenticated as "newuser@example.com"
    And I add 'content-type' header equal to 'application/json'
    When I send a "PUT" request to "/api/lessons/e7f48f24-a5b7-4b8b-b491-258ad546f8bc/progress" with body:
    """
    {
      "completed": true
    }
    """
    Then the response should be in JSON
    Then the response status code should be 200

    And I add 'content-type' header equal to 'application/json'
    And I add 'x-api-key' header equal to 'test-api-key'
    And I send a "DELETE" request to "/api/integration/users/courses" with body:
    """
    {
      "email": "newuser@example.com",
      "course": "Test course"
    }
    """
    Then the response should be in JSON
    And the response status code should be 200
    And the JSON node "courses" should have 1 elements

    And I add 'content-type' header equal to 'application/json'
    And I add 'x-api-key' header equal to 'test-api-key'
    And I send a "DELETE" request to "/api/integration/users/courses" with body:
    """
    {
      "email": "newuser@example.com",
      "course": "Test second course"
    }
    """
    And the JSON node "courses" should have 0 elements

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

Feature:
  In order to work with users
  As a user
  I want to use users API

  Scenario: It gets token for login and password
    Given the following Users:
      | firstName | lastName | email            | password     |
      | Test      | User     | test@example.com | testPassword |
    When I add 'content-type' header equal to 'application/json'
    And I send a "POST" request to "/api/login_check" with body:
    """
    {
      "username": "test@example.com",
      "password": "testPassword"
    }
    """
    Then the response should be in JSON
    And the JSON node "token" should exist

  Scenario: It gets current user data
    Given the following Courses:
      | title       | description             | coverImage       |
      | Test course | Test course description | course_cover.png |
    Given the following Users:
      | firstName | lastName | email            | password     |
      | Test      | User     | test@example.com | testPassword |

    Given that "test@example.com" user have "Test course" course
    When I am authenticated as "test@example.com"
    And I send a "GET" request to "/api/users/me"

    Then the response should be in JSON
    And the JSON node "email" should be equal to the string "test@example.com"
    And the JSON node "first_name" should be equal to the string "Test"
    And the JSON node "last_name" should be equal to the string "User"
    And the JSON node "password" should not exist
    And the JSON node "plain_password" should not exist
    And the JSON node "last_login_date" should exist
    And the JSON node "last_login_date" should not be null
    And the JSON node "roles[0]" should be equal to the string "ROLE_USER"
    And the JSON node "courses" should have 1 element
    And the JSON node "courses[0].title" should be equal to the string "Test course"
    And the JSON node "courses[0].description" should be equal to the string "Test course description"
    And the JSON node "courses[0].cover_image_name" should be equal to the string "test-course.png"
    And the JSON node "courses[0].href.cover_image_url" should be equal to the string "http://localhost/assets/images/course/covers/test-course.png"

  Scenario: It should register new user
    And I add 'content-type' header equal to 'application/json'
    And I send a "POST" request to "/api/users/register" with signed body:
    """
    {
      "email": "newuser@example.com",
      "firstName": "New",
      "lastName": "User"
    }
    """
    Then the response should be in JSON
    And the response status code should be 201
    And At least 1 email should be sent
    And Mail with title "Welcome in OwnCourses" should be sent

  Scenario: It should fail on not signed request
    And I add 'content-type' header equal to 'application/json'
    And I send a "POST" request to "/api/users/register" with body:
    """
    {
      "email": "newuser@example.com",
      "firstName": "New",
      "lastName": "User"
    }
    """
    Then the response should be in JSON
    And the response status code should be 401


  Scenario: It should fail on already existing email
    Given the following Users:
      | firstName | lastName | email            | password     |
      | Test      | User     | test@example.com | testPassword |
    And I add 'content-type' header equal to 'application/json'
    And I send a "POST" request to "/api/users/register" with signed body:
    """
    {
      "email": "test@example.com",
      "firstName": "Updated",
      "lastName": "User"
    }
    """
    Then the response should be in JSON
    And the response status code should be 201
    And the JSON node "first_name" should be equal to "Updated"

  Scenario: It should check if user with provided email have account in system
    Given the following Users:
      | firstName | lastName | email             | password     |
      | Test      | User     | admin@example.com | testPassword |
    When I run the "app:user:promote" command with options:
      | user | admin@example.com |
      | role | ROLE_ADMIN       |

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

    When I am authenticated as "admin@example.com"
    And I add 'content-type' header equal to 'application/json'
    And I send a "POST" request to "/api/users/check-email" with body:
    """
    {
      "email": "newuser@example.com"
    }
    """
    Then the response should be in JSON
    Then the response status code should be 200
    Then the JSON node "" should have 9 elements
    And the JSON node "email" should be equal to "newuser@example.com"
    And the JSON node "courses[0].title" should be equal to "Test course"

    When I am authenticated as "admin@example.com"
    And I add 'content-type' header equal to 'application/json'
    And I send a "POST" request to "/api/users/check-email" with body:
    """
    {
      "email": "notexisting@example.com"
    }
    """
    Then the response status code should be 404

    When I am authenticated as "admin@example.com"
    And I add 'content-type' header equal to 'application/json'
    And I send a "POST" request to "/api/users/check-email" with body:
    """
    {
      "wrongType": "notexisting@example.com"
    }
    """
    Then the response status code should be 400

    And I send a "POST" request to "/api/users/check-email" with body:
    """
    {
      "email": "notexisting@example.com"
    }
    """
    Then the response status code should be 401

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
    And the JSON node "roles[0]" should be equal to the string "ROLE_USER"
    And the JSON node "courses" should have 1 element
    And the JSON node "courses[0].title" should be equal to the string "Test course"
    And the JSON node "courses[0].description" should be equal to the string "Test course description"
    And the JSON node "courses[0].cover_image_name" should be equal to the string "test-course.png"
    And the JSON node "courses[0].href.coverImageUrl" should be equal to the string "http://localhost/assets/images/course/covers/test-course.png"

  Scenario: It should register new user
    And I add 'content-type' header equal to 'application/json'
    And I send a "POST" request to "/api/users/register" with signed body:
    """
    {
      "register_user": {
        "email": "newuser@example.com",
        "firstName": "New",
        "lastName": "User"
      }
    }
    """
    Then the response should be in JSON
    And the response status code should be 201

  Scenario: It should fail on already existing email
    Given the following Users:
      | firstName | lastName | email            | password     |
      | Test      | User     | test@example.com | testPassword |
    And I add 'content-type' header equal to 'application/json'
    And I send a "POST" request to "/api/users/register" with signed body:
    """
    {
      "register_user": {
        "email": "test@example.com",
        "firstName": "New",
        "lastName": "User"
      }
    }
    """
    Then the response should be in JSON
    And the response status code should be 400

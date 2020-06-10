Feature:
  In order to inform user about new course added to platform
  As a user
  I want to get email when new product is added to account

  Scenario: It sends email notifications
    Given the following Courses:
      | title           | description                 | coverImage       | visible | type     | parent      | purchaseUrl             |
      | Test course     | Test course description     | course_cover.png | true    | standard | null        | null                    |
      | Demo course     | Demo course description     | course_cover.png | true    | demo     | Test course | https://example.com/buy |
    Given Module "Test Module" with id "07a2f327-103a-11e9-8025-00ff5d11aabc" for course "Test course"
    Given the following Lessons:
      | id                                   | title       | module        | description                 | coverImage       | durationInMinutes | embedCode |
      | e7f48f24-a5b7-4b8b-b491-258ad546f8bc | Lesson f8bc | Test Module   | Test course description     | lesson_cover.png | 35                | noEmbed   |

    Given the following Demo Lessons:
      | id                                   | lesson                               |
      | 00f48f24-a5b7-4b8b-b491-258ad546f8bc | e7f48f24-a5b7-4b8b-b491-258ad546f8bc |

    And I add 'content-type' header equal to 'application/json'
    And I add 'x-api-key' header equal to 'test-api-key'
    And I send a "POST" request to "/api/integration/users/register" with body:
    """
    {
      "email": "newuser@example.com",
      "firstName": "New",
      "lastName": "User",
      "course": "Demo course"
    }
    """
    Then the response should be in JSON
    And the response status code should be 201
    And the JSON node "courses[0].title" should be equal to "Demo course"
    And Exactly 2 emails should be sent
    And Mail with title "Welcome in OwnCourses" should be sent

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
    And At least 1 email should be sent
    And Mail with title "New course was added to your account" should be sent

    When I am authenticated as "newuser@example.com"
    And I send a "GET" request to "/api/users/me"
    And the JSON node "courses" should have 1 element
    And the JSON node "courses[0].title" should be equal to "Test course"

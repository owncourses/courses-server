Feature:
  In order to work with lesson bookmarks
  As a user
  I want to use lesson bookmarks API

  Scenario: I want to list all lessons bookmarks
    Given the following Courses:
      | title       | description             | coverImage       |
      | Test course | Test course description | course_cover.png |
    Given Course "Test course" and module "Test Module" and id "07a2f327-103a-11e9-8025-00ff5d11a779"
    Given Lesson "Test lesson" in "Test Module" with description "Test lesson description" and id "e7f48f24-a5b7-4b8b-b491-258ad546f8bc" and coverImage "lesson_cover.png" and embed code:
    """
    <iframe width='500px' height='294px' src='https://player.vimeo.com/video/225434434?'></iframe>
    """
    Given the following Users:
      | firstName | lastName | email            | password     |
      | Test      | User     | test@example.com | testPassword |
    Given the following Bookmarks:
      | lesson      | timeInSeconds | user             | title                | id                                   |
      | Test lesson | 15            | null             | test system bookmark | 0dfc3e5e-b9d2-46a8-99a3-5dae31f2a143 |
      | Test lesson | 35            | test@example.com | test user bookmark   | 0dfc3e5e-b9d2-46a8-99a3-5dae31f2a1aa |
    Given that "test@example.com" user have "Test course" course
    When I am authenticated as "test@example.com"
    When I send a "GET" request to "/api/lessons/e7f48f24-a5b7-4b8b-b491-258ad546f8bc/bookmarks"
    Then the response should be in JSON
    Then the response status code should be 200
    And the JSON node "[0].title" should be equal to "test system bookmark"
    And the JSON node "[0].time_in_seconds" should be equal to "15"
    And the JSON node "[0].user" should be null
    And the JSON node "[0].id" should exist
    And the JSON node "[1].title" should be equal to "test user bookmark"
    And the JSON node "[1].time_in_seconds" should be equal to "35"
    And the JSON node "[1].user" should not be null
    And the JSON node "[1].id" should exist

  Scenario: I want to create new user bookmark
    Given the following Courses:
      | title       | description             | coverImage       |
      | Test course | Test course description | course_cover.png |
    Given Course "Test course" and module "Test Module" and id "07a2f327-103a-11e9-8025-00ff5d11a779"
    Given Lesson "Test lesson" in "Test Module" with description "Test lesson description" and id "e7f48f24-a5b7-4b8b-b491-258ad546f8bc" and coverImage "lesson_cover.png" and embed code:
    """
    <iframe width='500px' height='294px' src='https://player.vimeo.com/video/225434434?'></iframe>
    """
    Given the following Users:
      | firstName | lastName | email            | password     |
      | Test      | User     | test@example.com | testPassword |

    Given that "test@example.com" user have "Test course" course
    And I add 'content-type' header equal to 'application/json'
    When I am authenticated as "test@example.com"
    When I send a "POST" request to "/api/lessons/e7f48f24-a5b7-4b8b-b491-258ad546f8bc/bookmarks" with body:
    """
    {
      "title": "test bookmark",
      "timeInSeconds": 220
    }
    """
    Then the response should be in JSON
    Then the response status code should be 201

    And the JSON node "title" should be equal to "test bookmark"
    And the JSON node "time_in_seconds" should be equal to "220"
    And the JSON node "user" should not be null
    And the JSON node "id" should exist
    And the JSON node "user.id" should exist

  Scenario: I want to create and remove user bookmark
    Given the following Courses:
      | title       | description             | coverImage       |
      | Test course | Test course description | course_cover.png |
    Given Course "Test course" and module "Test Module" and id "07a2f327-103a-11e9-8025-00ff5d11a779"
    Given Lesson "Test lesson" in "Test Module" with description "Test lesson description" and id "e7f48f24-a5b7-4b8b-b491-258ad546f8bc" and coverImage "lesson_cover.png" and embed code:
    """
    <iframe width='500px' height='294px' src='https://player.vimeo.com/video/225434434?'></iframe>
    """
    Given the following Users:
      | firstName | lastName | email            | password     |
      | Test      | User     | test@example.com | testPassword |

    Given the following Bookmarks:
      | lesson      | timeInSeconds | user             | title                | id                                   |
      | Test lesson | 35            | test@example.com | test user bookmark   | 0dfc3e5e-b9d2-46a8-99a3-5dae31f2a143 |
      | Test lesson | 15            | null             | test user bookmark   | b90daf8b-3fa1-40bd-93f6-4880b17ce97d |

    And I add 'content-type' header equal to 'application/json'
    When I am authenticated as "test@example.com"
    When I send a "DELETE" request to "/api/bookmarks/0dfc3e5e-b9d2-46a8-99a3-5dae31f2a143"
    Then the response status code should be 204

    And I add 'content-type' header equal to 'application/json'
    When I am authenticated as "test@example.com"
    When I send a "DELETE" request to "/api/bookmarks/b90daf8b-3fa1-40bd-93f6-4880b17ce97d"
    Then the response status code should be 403


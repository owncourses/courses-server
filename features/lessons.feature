Feature:
  In order to work with module lessons
  As a user
  I want to use lessons API

  Scenario: I want to list all module lessons
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
    When I am authenticated as "test@example.com"
    When I send a "GET" request to "/api/lessons/e7f48f24-a5b7-4b8b-b491-258ad546f8bc"
    Then the response should be in JSON
    Then the response status code should be 200
    And the JSON node "title" should be equal to "Test lesson"
    And the JSON node "description" should be equal to "Test lesson description"
    And the JSON node "embed_type" should be equal to "code"
    And the JSON node "embed_code" should be equal to "<iframe width='500px' height='294px' src='https://player.vimeo.com/video/225434434?'></iframe>"
    And the JSON node "module.title" should be equal to "Test Module"
    And the JSON node "module.course.title" should be equal to "Test course"
    And the JSON node "href.cover_image_url" should be equal to the string "http://localhost/assets/images/course/covers/test-lesson.png"

  Scenario: I want to mark lesson as completed
    Given the following Courses:
      | title       | description             | coverImage       |
      | Test course | Test course description | course_cover.png |
    Given Course "Test course" and module "Test Module" and id "07a2f327-103a-11e9-8025-00ff5d11a779"
    Given Lesson "Test lesson" in "Test Module" with description "Test lesson description" and id "e7f48f24-a5b7-4b8b-b491-258ad546f8bc" and embed code:
    """
    <iframe width='500px' height='294px' src='https://player.vimeo.com/video/225434434?'></iframe>
    """
    Given the following Users:
      | firstName | lastName | email            | password     |
      | Test      | User     | test@example.com | testPassword |
    Given that "test@example.com" user have "Test course" course
    When I am authenticated as "test@example.com"
    And I add 'content-type' header equal to 'application/json'
    When I send a "PUT" request to "/api/lessons/e7f48f24-a5b7-4b8b-b491-258ad546f8bc/progress" with body:
    """
    {
      "completed": true
    }
    """
    Then the response should be in JSON
    Then the response status code should be 200
    When I am authenticated as "test@example.com"
    When I send a "GET" request to "/api/lessons/e7f48f24-a5b7-4b8b-b491-258ad546f8bc"
    Then the response should be in JSON
    Then the response status code should be 200
    And the JSON node "completed" should be true

#    clear progress

    When I am authenticated as "test@example.com"
    And I add 'content-type' header equal to 'application/json'
    When I send a "PUT" request to "/api/lessons/e7f48f24-a5b7-4b8b-b491-258ad546f8bc/progress" with body:
    """
    {
      "completed": false
    }
    """
    Then the response should be in JSON
    Then the response status code should be 200
    When I am authenticated as "test@example.com"
    When I send a "GET" request to "/api/lessons/e7f48f24-a5b7-4b8b-b491-258ad546f8bc"
    Then the response should be in JSON
    Then the response status code should be 200
    And the JSON node "completed" should be false

  Scenario: I want to list only lessons from active courses
    Given the following Courses:
      | title           | description                 | coverImage       | visible | startDate | endDate  |
      | Test course     | Test course description     | course_cover.png | true    | null      | null     |
      | Future course   | Test course description     | course_cover.png | true    | + 1 day   | +7 days  |
      | Past course     | Inactive course description | course_cover.png | true    | -30 days  | -1 day   |
    Given Course "Test course" and module "Test Module" and id "07a2f327-103a-11e9-8025-00ff5d11a779"
    Given Lesson "Test lesson" in "Test Module" with description "Test lesson description" and id "e7f48f24-a5b7-4b8b-b491-258ad546f8bc" and embed code:
    """
    <iframe width='500px' height='294px' src='https://player.vimeo.com/video/225434434?'></iframe>
    """

    Given Course "Future course" and module "Future Module" and id "07a2f327-103a-11e9-8025-00ff5d11a111"
    Given Lesson "Future lesson" in "Future Module" with description "Future lesson description" and id "e7f48f24-a5b7-4b8b-b491-258ad546f111" and embed code:
    """
    <iframe width='500px' height='294px' src='https://player.vimeo.com/video/225434434?'></iframe>
    """

    Given Course "Past course" and module "Past Module" and id "07a2f327-103a-11e9-8025-00ff5d11a999"
    Given Lesson "Past lesson" in "Past Module" with description "Past lesson description" and id "e7f48f24-a5b7-4b8b-b491-258ad546f999" and embed code:
    """
    <iframe width='500px' height='294px' src='https://player.vimeo.com/video/225434434?'></iframe>
    """

    Given the following Users:
      | firstName | lastName | email            | password     |
      | Test      | User     | test@example.com | testPassword |
    Given that "test@example.com" user have "Test course" course
    When I am authenticated as "test@example.com"
    When I send a "GET" request to "/api/lessons/e7f48f24-a5b7-4b8b-b491-258ad546f8bc"
    Then the response should be in JSON
    Then the response status code should be 200

    When I am authenticated as "test@example.com"
    When I send a "GET" request to "/api/lessons/e7f48f24-a5b7-4b8b-b491-258ad546f111"
    Then the response status code should be 404

    When I am authenticated as "test@example.com"
    When I send a "GET" request to "/api/lessons/e7f48f24-a5b7-4b8b-b491-258ad546f999"
    Then the response status code should be 404

  Scenario: I want to work with custom lesson duration
    Given the following Courses:
      | title           | description                 | coverImage       | visible | startDate | endDate  |
      | Test course     | Test course description     | course_cover.png | true    | null      | null     |
    Given Course "Test course" and module "Test Module" and id "07a2f327-103a-11e9-8025-00ff5d11aabc"
    Given the following Lessons:
      | id                                   | title       | module      | description                 | coverImage       | durationInMinutes | embedCode |
      | e7f48f24-a5b7-4b8b-b491-258ad546f8bc | Test lesson | Test Module | Test course description     | lesson_cover.png | 35                | noEmbed   |
    Given the following Users:
      | firstName | lastName | email            | password     |
      | Test      | User     | test@example.com | testPassword |
    Given that "test@example.com" user have "Test course" course
    When I am authenticated as "test@example.com"
    When I send a "GET" request to "/api/lessons/e7f48f24-a5b7-4b8b-b491-258ad546f8bc"
    Then the response should be in JSON
    Then the response status code should be 200
    And the JSON node "duration_in_minutes" should be equal to 35

  Scenario: I want to work with vimeo videos in lessons
    Given the following Courses:
      | title           | description                 | coverImage       | visible | startDate | endDate  |
      | Test course     | Test course description     | course_cover.png | true    | null      | null     |
    Given Course "Test course" and module "Test Module" and id "07a2f327-103a-11e9-8025-00ff5d11aabc"
    Given the following Lessons:
      | id                                   | title       | embedType | module      | description                 | coverImage       | durationInMinutes | embedCode |
      | e7f48f24-a5b7-4b8b-b491-258ad546f8bc | Test lesson | vimeo     | Test Module | Test course description     | lesson_cover.png | 35                | vimeoID   |
    Given the following Users:
      | firstName | lastName | email            | password     |
      | Test      | User     | test@example.com | testPassword |
    Given that "test@example.com" user have "Test course" course
    When I am authenticated as "test@example.com"
    When I send a "GET" request to "/api/lessons/e7f48f24-a5b7-4b8b-b491-258ad546f8bc"
    Then the response should be in JSON
    Then the response status code should be 200
    And the JSON node "embed_type" should be equal to vimeo
    And the JSON node "embed_code" should be equal to vimeoID


  Scenario: I want to work with next and previous lessons
    Given the following Courses:
      | title           | description                 | coverImage       | visible | startDate | endDate  |
      | Test course     | Test course description     | course_cover.png | true    | null      | null     |
    Given Course "Test course" and module "Test Module" and id "07a2f327-103a-11e9-8025-00ff5d11aabc"
    Given the following Lessons:
      | id                                   | title       | module        | description                 | coverImage       | durationInMinutes | embedCode |
      | e7f48f24-a5b7-4b8b-b491-258ad546f8bc | Lesson f8bc | Test Module   | Test course description     | lesson_cover.png | 35                | noEmbed   |
      | 56e3f44c-e16f-4a7a-8519-1d1e87cb32d5 | Lesson 32d5 | Test Module   | Test course description     | lesson_cover.png | 15                | noEmbed   |
      | d56b3c1c-1dbb-4aa2-bac6-7cf67527bbb6 | Lesson bbb6 | Test Module   | Test course description     | lesson_cover.png | 20                | noEmbed   |
    Given the following Users:
      | firstName | lastName | email            | password     |
      | Test      | User     | test@example.com | testPassword |
    Given that "test@example.com" user have "Test course" course
    When I am authenticated as "test@example.com"
    When I send a "GET" request to "/api/lessons/e7f48f24-a5b7-4b8b-b491-258ad546f8bc"
    Then the response should be in JSON
    Then the response status code should be 200
    And the JSON node "pagination.prev_lesson_id" should be null
    And the JSON node "pagination.next_lesson_id" should be equal to "56e3f44c-e16f-4a7a-8519-1d1e87cb32d5"

    Given that "test@example.com" user have "Test course" course
    When I am authenticated as "test@example.com"
    When I send a "GET" request to "/api/lessons/56e3f44c-e16f-4a7a-8519-1d1e87cb32d5"
    Then the response should be in JSON
    Then the response status code should be 200
    And the JSON node "pagination.prev_lesson_id" should be equal to "e7f48f24-a5b7-4b8b-b491-258ad546f8bc"
    And the JSON node "pagination.next_lesson_id" should be equal to "d56b3c1c-1dbb-4aa2-bac6-7cf67527bbb6"

    Given that "test@example.com" user have "Test course" course
    When I am authenticated as "test@example.com"
    When I send a "GET" request to "/api/lessons/d56b3c1c-1dbb-4aa2-bac6-7cf67527bbb6"
    Then the response should be in JSON
    Then the response status code should be 200
    And the JSON node "pagination.prev_lesson_id" should be equal to "56e3f44c-e16f-4a7a-8519-1d1e87cb32d5"
    And the JSON node "pagination.next_lesson_id" should be null

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
    When I am authenticated as "johndoe"
    When I send a "GET" request to "/api/lessons/e7f48f24-a5b7-4b8b-b491-258ad546f8bc"
    Then the response should be in JSON
    Then the response status code should be 200
    And the JSON node "title" should be equal to "Test lesson"
    And the JSON node "description" should be equal to "Test lesson description"
    And the JSON node "embed_code" should be equal to "<iframe width='500px' height='294px' src='https://player.vimeo.com/video/225434434?'></iframe>"
    And the JSON node "module.title" should be equal to "Test Module"
    And the JSON node "module.course.title" should be equal to "Test course"
    And the JSON node "href.coverImageUrl" should be equal to the string "http://localhost/assets/images/course/test-lesson.png"

  Scenario: I want to mark lesson as completed
    Given the following Courses:
      | title       | description             | coverImage       |
      | Test course | Test course description | course_cover.png |
    Given Course "Test course" and module "Test Module" and id "07a2f327-103a-11e9-8025-00ff5d11a779"
    Given Lesson "Test lesson" in "Test Module" with description "Test lesson description" and id "e7f48f24-a5b7-4b8b-b491-258ad546f8bc" and embed code:
    """
    <iframe width='500px' height='294px' src='https://player.vimeo.com/video/225434434?'></iframe>
    """
    When I am authenticated as "johndoe"
    And I add 'content-type' header equal to 'application/json'
    When I send a "PATCH" request to "/api/lessons/e7f48f24-a5b7-4b8b-b491-258ad546f8bc/progress" with body:
    """
    {
      "completed": true
    }
    """
    Then the response should be in JSON
    Then the response status code should be 200
    When I am authenticated as "johndoe"
    When I send a "GET" request to "/api/lessons/e7f48f24-a5b7-4b8b-b491-258ad546f8bc"
    Then the response should be in JSON
    Then the response status code should be 200
    And the JSON node "completed" should be true

#    clear progress

    When I am authenticated as "johndoe"
    And I add 'content-type' header equal to 'application/json'
    When I send a "PATCH" request to "/api/lessons/e7f48f24-a5b7-4b8b-b491-258ad546f8bc/progress" with body:
    """
    {
      "completed": null
    }
    """
    Then the response should be in JSON
    Then the response status code should be 200
    When I am authenticated as "johndoe"
    When I send a "GET" request to "/api/lessons/e7f48f24-a5b7-4b8b-b491-258ad546f8bc"
    Then the response should be in JSON
    Then the response status code should be 200
    And the JSON node "completed" should be null

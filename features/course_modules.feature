Feature:
  In order to work with course modules
  As a user
  I want to use course modules API

  Scenario: I want to list all course modules
    Given the following Courses:
      | title       | description             | coverImage       |
      | Test course | Test course description | course_cover.png |
    Given Course "Test course" module "Test Module" with description "Test module description"
    Given Course "Test course" module "Test Module 2" with description "Second module module description" and position 0
    Given Lesson "Test lesson" in "Test Module" with description "Test lesson description" and id "e7f48f24-a5b7-4b8b-b491-258ad546f8bc" and coverImage "lesson_cover.png" and embed code:
    """
    <iframe width='500px' height='294px' src='https://player.vimeo.com/video/225434434?'></iframe>
    """
    Given Lesson "Other test lesson" in "Test Module" with description "Other test lesson description" and id "e7f48f24-a5b7-4b8b-b491-258ad546f8bd" and embed code:
    """
    <iframe width='500px' height='294px' src='https://player.vimeo.com/video/225434434?'></iframe>
    """
    Given the following Users:
      | firstName | lastName | email            | password     |
      | Test      | User     | test@example.com | testPassword |
    Given that "test@example.com" user have "Test course" course
    When I am authenticated as "test@example.com"
    And I send a "GET" request to "/api/courses/1/modules"
    Then the response should be in JSON
    Then the response status code should be 200
    Then the JSON node "[1].title" should be equal to "Test Module"
    And the JSON node "[1].description" should be equal to "Test module description"
    And the JSON node "[1].course.description" should exist
    And the JSON node "[1].position" should be equal to "1"
    And the JSON node "[1].lessons" should have 2 elements
    And the JSON node "[1].lessons[0].title" should be equal to "Test lesson"
    And the JSON node "[1].lessons[0].embed_code" should not exist
    And the JSON node "[1].lessons[0].completed" should be null
    And the JSON node "[1].lessons[0].href.cover_image_url" should be equal to the string "http://localhost/assets/images/course/covers/test-lesson.png"
    And the JSON node "[0].title" should be equal to "Test Module 2"
    And the JSON node "[0].description" should be equal to "Second module module description"
    And the JSON node "[0].position" should be equal to "0"


  Scenario: I want to see modules progress for current user
    Given the following Courses:
      | title       | description             | coverImage       |
      | Test course | Test course description | course_cover.png |
    Given Course "Test course" module "Test Module" with description "Test module description"
    Given Course "Test course" module "Test Module 2" with description "Second module module description" and position 0
    Given the following Lessons:
      | id                                   | title       | module        | description                 | coverImage       | durationInMinutes | embedCode |
      | e7f48f24-a5b7-4b8b-b491-258ad546f8bc | Lesson f8bc | Test Module   | Test course description     | lesson_cover.png | 35                | noEmbed   |
      | 56e3f44c-e16f-4a7a-8519-1d1e87cb32d5 | Lesson 32d5 | Test Module   | Test course description     | lesson_cover.png | 15                | noEmbed   |
      | d56b3c1c-1dbb-4aa2-bac6-7cf67527bbb6 | Lesson bbb6 | Test Module   | Test course description     | lesson_cover.png | 20                | noEmbed   |
      | b80a35aa-da81-4a80-af2d-1580ff053212 | Lesson 3212 | Test Module 2 | Test course description     | lesson_cover.png | 10                | noEmbed   |
      | 97680c59-d2e3-411d-9b62-949026935313 | Lesson 5313 | Test Module 2 | Test course description     | lesson_cover.png | 13                | noEmbed   |
    Given the following Users:
      | firstName | lastName | email            | password     |
      | Test      | User     | test@example.com | testPassword |
    Given that "test@example.com" user have "Test course" course
    When I am authenticated as "test@example.com"
    And I send a "GET" request to "/api/courses/1/modules"
    Then the response should be in JSON
    Then the response status code should be 200
    Then the JSON node "" should have 2 elements
    And the JSON node "[1].title" should be equal to "Test Module"
    And the JSON node "[1].description" should be equal to "Test module description"
    And the JSON node "[1].progress.completed_lessons" should be equal to "0"
    And the JSON node "[1].progress.completed_percentage" should be equal to "0"
    And the JSON node "[1].progress.completed_time" should be equal to "0"
    And the JSON node "[1].progress.total_duration" should be equal to "70"

    When I am authenticated as "test@example.com"
    And I add 'content-type' header equal to 'application/json'
    When I send a "PUT" request to "/api/lessons/56e3f44c-e16f-4a7a-8519-1d1e87cb32d5/progress" with body:
    """
    {
      "completed": true
    }
    """
    Then the response should be in JSON
    Then the response status code should be 200

    When I am authenticated as "test@example.com"
    And I send a "GET" request to "/api/courses/1/modules"
    Then the response should be in JSON
    Then the response status code should be 200
    Then the JSON node "" should have 2 elements
    And the JSON node "[1].title" should be equal to "Test Module"
    And the JSON node "[1].description" should be equal to "Test module description"
    And the JSON node "[1].progress.completed_lessons" should be equal to "1"
    And the JSON node "[1].progress.completed_percentage" should be equal to 33
    And the JSON node "[1].progress.completed_time" should be equal to "15"
    And the JSON node "[1].progress.total_duration" should be equal to "70"

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
    And I send a "GET" request to "/api/courses/1/modules"
    Then the response should be in JSON
    Then the response status code should be 200
    And the JSON node "[1].progress.completed_lessons" should be equal to "2"
    And the JSON node "[1].progress.completed_percentage" should be equal to 67
    And the JSON node "[1].progress.completed_time" should be equal to "50"
    And the JSON node "[1].progress.total_duration" should be equal to "70"

    When I am authenticated as "test@example.com"
    And I add 'content-type' header equal to 'application/json'
    When I send a "PUT" request to "/api/lessons/d56b3c1c-1dbb-4aa2-bac6-7cf67527bbb6/progress" with body:
    """
    {
      "completed": true
    }
    """
    Then the response should be in JSON
    Then the response status code should be 200

    When I am authenticated as "test@example.com"
    And I send a "GET" request to "/api/courses/1/modules"
    Then the response should be in JSON
    Then the response status code should be 200
    And the JSON node "[1].progress.completed_lessons" should be equal to "3"
    And the JSON node "[1].progress.completed_percentage" should be equal to 100
    And the JSON node "[1].progress.completed_time" should be equal to "70"
    And the JSON node "[1].progress.total_duration" should be equal to "70"

    When I am authenticated as "test@example.com"
    And I add 'content-type' header equal to 'application/json'
    When I send a "PUT" request to "/api/lessons/d56b3c1c-1dbb-4aa2-bac6-7cf67527bbb6/progress" with body:
    """
    {
      "completed": false
    }
    """
    Then the response should be in JSON
    Then the response status code should be 200

    When I am authenticated as "test@example.com"
    And I send a "GET" request to "/api/courses/1/modules"
    Then the response should be in JSON
    Then the response status code should be 200
    And the JSON node "[1].progress.completed_lessons" should be equal to "2"
    And the JSON node "[1].progress.completed_percentage" should be equal to 67
    And the JSON node "[1].progress.completed_time" should be equal to "50"
    And the JSON node "[1].progress.total_duration" should be equal to "70"

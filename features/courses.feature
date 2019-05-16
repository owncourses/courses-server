Feature:
  In order to work with courses
  As a user
  I want to use courses API

  Scenario: It gets list of all courses
    Given the following Courses:
      | title           | description                 | coverImage       | visible | startDate | endDate  |
      | Test course     | Test course description     | course_cover.png | true    | null      | null     |
      | Future course   | Test course description     | course_cover.png | true    | + 1 day   | +7 days  |
      | Past course     | Inactive course description | course_cover.png | true    | -30 days  | -1 day   |
      | Inactive course | Inactive course description | course_cover.png | false   | -30 days  | +30 days |
    When I am authenticated as "johndoe"
    And I send a "GET" request to "/api/courses"
    Then the response should be in JSON
    And the JSON node "" should have 3 elements
    And the JSON node "[0].id" should be equal to "1"
    And the JSON node "[0].title" should be equal to "Test course"

  Scenario: I want to see course progress for current user
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
    And I send a "GET" request to "/api/courses"
    Then the response should be in JSON
    Then the response status code should be 200
    Then the JSON node "" should have 1 element
    And the JSON node "[0].title" should be equal to "Test course"
    And the JSON node "[0].progress.completed_lessons" should be equal to "0"
    And the JSON node "[0].progress.completed_percentage" should be equal to "0"
    And the JSON node "[0].progress.completed_time" should be equal to "0"
    And the JSON node "[0].progress.total_duration" should be equal to "93"

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
    And I send a "GET" request to "/api/courses"
    Then the response should be in JSON
    Then the response status code should be 200
    Then the JSON node "" should have 1 element
    And the JSON node "[0].title" should be equal to "Test course"
    And the JSON node "[0].progress.completed_lessons" should be equal to "1"
    And the JSON node "[0].progress.completed_percentage" should be equal to "20"
    And the JSON node "[0].progress.completed_time" should be equal to "35"
    And the JSON node "[0].progress.total_duration" should be equal to "93"


    When I am authenticated as "test@example.com"
    And I add 'content-type' header equal to 'application/json'
    When I send a "PUT" request to "/api/lessons/b80a35aa-da81-4a80-af2d-1580ff053212/progress" with body:
    """
    {
      "completed": true
    }
    """
    Then the response should be in JSON
    Then the response status code should be 200

    When I am authenticated as "test@example.com"
    And I send a "GET" request to "/api/courses"
    Then the response should be in JSON
    Then the response status code should be 200
    Then the JSON node "" should have 1 element
    And the JSON node "[0].title" should be equal to "Test course"
    And the JSON node "[0].progress.completed_lessons" should be equal to "2"
    And the JSON node "[0].progress.completed_percentage" should be equal to "40"
    And the JSON node "[0].progress.completed_time" should be equal to "45"
    And the JSON node "[0].progress.total_duration" should be equal to "93"

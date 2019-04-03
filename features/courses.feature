Feature:
  In order to work with courses
  As a user
  I want to use courses API

  Scenario: It gets list of all courses
    Given the following Courses:
      | title       | description             | coverImage       |
      | Test course | Test course description | course_cover.png |
    When I am authenticated as "johndoe"
    And I send a "GET" request to "/api/courses"
    Then the response should be in JSON
    And the JSON node "[0].id" should be equal to "1"
    And the JSON node "[0].title" should be equal to "Test course"

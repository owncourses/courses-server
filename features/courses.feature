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

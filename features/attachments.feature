Feature:
  In order to work with attachments
  As a user
  I want to use attachment in lessons API

  Scenario: It gets list of all lesson attachments
    Given the following Courses:
      | title           | description                 |
      | Test course     | Test course description     |
    Given Course "Test course" and module "Test Module" and id "07a2f327-103a-11e9-8025-00ff5d11aabc"
    Given the following Lessons:
      | id                                   | title       | module      | description                 | coverImage       | durationInMinutes | embedCode |
      | e7f48f24-a5b7-4b8b-b491-258ad546f8bc | Test lesson | Test Module | Test course description     | lesson_cover.png | 35                | noEmbed   |
    Given the following Attachments:
      | id                                   | lesson      | file             | name                  |
      | e0fafe41-67ab-4ae6-bc2a-2edb67a133ce | Test lesson | sample.pdf       | test pdf attachment   |
      | 68497f61-ccd4-449b-b27e-bb9d9bfaee14 | Test lesson | course_cover.png | test image attachment |
    Given the following Users:
      | firstName | lastName | email            | password     |
      | Test      | User     | test@example.com | testPassword |
    Given that "test@example.com" user have "Test course" course
    When I am authenticated as "test@example.com"
    And I send a "GET" request to "/api/lessons/e7f48f24-a5b7-4b8b-b491-258ad546f8bc"
    Then the response should be in JSON
    And the response status code should be 200
    And the JSON node "attachments" should have 2 elements
    And the JSON node "attachments[0].name" should be equal to "test image attachment"
    And the JSON node "attachments[0].file_name" should be equal to "test-image-attachment.png"
    And the JSON node "attachments[0].mime_type" should be equal to "image/png"
    And the JSON node "attachments[0].lesson" should not exist
    And the JSON node "attachments[0].href.download" should be equal to "http://localhost/assets/images/course/attachments/test-image-attachment.png"

  Scenario: It gets list of all course attachments
    Given the following Courses:
      | title           | description                 |
      | Test course     | Test course description     |
    Given Course "Test course" and module "Test Module" and id "07a2f327-103a-11e9-8025-00ff5d11aabc"
    Given the following Lessons:
      | id                                   | title       | module      | description                 | coverImage       | durationInMinutes | embedCode |
      | e7f48f24-a5b7-4b8b-b491-258ad546f8bc | Test lesson | Test Module | Test course description     | lesson_cover.png | 35                | noEmbed   |
    Given the following Attachments:
      | id                                   | lesson      | file             | name                  |
      | e0fafe41-67ab-4ae6-bc2a-2edb67a133ce | Test lesson | sample.pdf       | test pdf attachment   |
      | 68497f61-ccd4-449b-b27e-bb9d9bfaee14 | Test lesson | course_cover.png | test image attachment |
    Given the following Users:
      | firstName | lastName | email            | password     |
      | Test      | User     | test@example.com | testPassword |
    Given that "test@example.com" user have "Test course" course
    When I am authenticated as "test@example.com"
    And I send a "GET" request to "/api/courses/1/attachments"
    Then the response should be in JSON
    And the response status code should be 200
    And the JSON node "" should have 2 elements
    And the JSON node "[0].name" should be equal to "test image attachment"
    And the JSON node "[0].file_name" should be equal to "test-image-attachment.png"
    And the JSON node "[0].mime_type" should be equal to "image/png"
    And the JSON node "[0].href.download" should be equal to "http://localhost/assets/images/course/attachments/test-image-attachment.png"

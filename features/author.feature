Feature:
  In order to work with course authors
  As a user
  I want to see authors information in course API response

  Scenario: It gets course authors information's
    Given the following Courses:
      | title              | description             | coverImage       |
      | Test course        | Test course description | course_cover.png |
      | Second test course | Test course description | course_cover.png |
    Given the following Authors:
      | name       | picture    | bio                       | courses                         | gender |
      | Jesica Doe | jesica.png | Well known courses author | Test course, Second test course | female |

    Given the following Authors:
      | name       | bio                       | courses                         |
      | John Doe   | Well known courses author | Test course, Second test course |

    Given the following Users:
      | firstName | lastName | email            | password     |
      | Test      | User     | test@example.com | testPassword |
    Given that "test@example.com" user have "Test course" course
    Given that "test@example.com" user have "Second test course" course

    When I am authenticated as "test@example.com"
    And I send a "GET" request to "/api/courses"
    Then the response should be in JSON
    And the JSON node "" should have 2 elements
    And the JSON node "[0].id" should be equal to "1"
    And the JSON node "[0].title" should be equal to "Test course"
    And the JSON node "[0].authors" should exist
    And the JSON node "[0].authors[0].name" should be equal to "Jesica Doe"
    And the JSON node "[0].authors[0].gender" should be equal to "female"
    And the JSON node "[0].authors[0].href.picture" should be equal to "http://localhost/assets/images/course/pictures/jesica-doe.png"

    And the JSON node "[0].authors[1].name" should be equal to "John Doe"
    And the JSON node "[0].authors[1].href.picture" should be null

Feature:
  In order to work with courses
  As a user
  I want to use courses API

  Scenario: It gets list of all courses
    Given the following Courses:
      | title           | description                 | coverImage       | visible | type     | parent      | purchaseUrl             |
      | Test course     | Test course description     | course_cover.png | true    | standard | null        | null                    |
      | Demo course     | Demo course description     | course_cover.png | true    | demo     | Test course | https://example.com/buy |
    When I am authenticated as "johndoe"
    And I send a "GET" request to "/api/courses"
    Then the response should be in JSON
    And the JSON node "" should have 2 elements
    And the JSON node "[0].id" should be equal to "1"
    And the JSON node "[0].title" should be equal to "Test course"
    And the JSON node "[0].type" should be equal to "standard"
    And the JSON node "[1].id" should be equal to "2"
    And the JSON node "[1].title" should be equal to "Demo course"
    And the JSON node "[1].parent.id" should be equal to "1"
    And the JSON node "[1].type" should be equal to "demo"

    Given Module "Test Module" with id "07a2f327-103a-11e9-8025-00ff5d11aabc" for course "Test course"

    Given the following Lessons:
      | id                                   | title       | module        | description                 | coverImage       | durationInMinutes | embedCode |
      | e7f48f24-a5b7-4b8b-b491-258ad546f8bc | Lesson f8bc | Test Module   | Test course description     | lesson_cover.png | 35                | noEmbed   |
      | 56e3f44c-e16f-4a7a-8519-1d1e87cb32d5 | Lesson 32d5 | Test Module   | Test course description     | lesson_cover.png | 15                | noEmbed   |
      | d56b3c1c-1dbb-4aa2-bac6-7cf67527bbb6 | Lesson bbb6 | Test Module   | Test course description     | lesson_cover.png | 20                | noEmbed   |
      | b80a35aa-da81-4a80-af2d-1580ff053212 | Lesson 3212 | Test Module   | Test course description     | lesson_cover.png | 10                | noEmbed   |
      | 97680c59-d2e3-411d-9b62-949026935313 | Lesson 5313 | Test Module   | Test course description     | lesson_cover.png | 13                | noEmbed   |

    Given the following Demo Lessons:
      | id                                   | lesson                               |
      | 00f48f24-a5b7-4b8b-b491-258ad546f8bc | 56e3f44c-e16f-4a7a-8519-1d1e87cb32d5 |
      | 00e3f44c-e16f-4a7a-8519-1d1e87cb32d5 | d56b3c1c-1dbb-4aa2-bac6-7cf67527bbb6 |

    When I am authenticated as "johndoe"
    And I send a "GET" request to "/api/courses/2/modules"
    Then the response should be in JSON
    Then the response status code should be 200
    Then the JSON node "[0].title" should be equal to "Test Module"
    And the JSON node "[0].course.purchase_url" should be equal to "https://example.com/buy"
    And the JSON node "[0].lessons" should have 5 elements
    And the JSON node "[0].lessons[0].blocked" should be true
    And the JSON node "[0].lessons[1].blocked" should be false
    And the JSON node "[0].lessons[2].blocked" should be false
    And the JSON node "[0].lessons[3].blocked" should be true
    And the JSON node "[0].lessons[4].blocked" should be true

    Given the following Users:
      | firstName | lastName | email            | password     |
      | Test      | User     | test@example.com | testPassword |

    Given that "test@example.com" user have "Demo course" course
    When I am authenticated as "test@example.com"
    And I send a "GET" request to "/api/users/me"

    Then the response should be in JSON
    And the JSON node "email" should be equal to the string "test@example.com"
    And the JSON node "courses" should have 1 elements
    And the JSON node "courses[0].title" should be equal to "Demo course"

    Given that "test@example.com" user have "Test course" course
    When I am authenticated as "test@example.com"
    And I send a "GET" request to "/api/users/me"
    And the JSON node "courses" should have 1 elements
    And the JSON node "courses[0].title" should be equal to "Test course"

Feature:
  In order to work with notifications
  As a user
  I want to use notifications API

  Scenario: It gets list of all active notifications
    Given the following Notifications:
      | id                                   | title             | text                                     | url                               | urlTitle   | label     |
      | 2cbb5590-3bb6-40a4-b388-c60289bfa188 | Test notification | Test notification text. Can be longer... | https://example.com/notifications | test title | new       |
      | 2cbb5590-3bb6-40a4-b388-c60289bfa199 | notification 32d5 | Test notification text                   | https://example.com/              |            | important |
      | 2cbb5590-3bb6-40a4-b388-c60289bfa177 | notification 32d5 | Test notification text                   | null                              |            | important |

    Given the following Users:
      | firstName | lastName | email            | password     |
      | Test      | User     | test@example.com | testPassword |

    When I am authenticated as "test@example.com"
    And I send a "GET" request to "/api/notifications"
    Then the response status code should be 200
    And the response should be in JSON
    And the JSON node "" should have 2 elements
    And the JSON node "unread" should be equal to "3"
    And the JSON node "notifications" should have 3 elements
    And the JSON node "notifications[0].id" should be equal to "2cbb5590-3bb6-40a4-b388-c60289bfa188"
    And the JSON node "notifications[0].title" should be equal to "Test notification"
    And the JSON node "notifications[0].text" should be equal to "Test notification text. Can be longer..."
    And the JSON node "notifications[0].url" should be equal to "https://example.com/notifications"
    And the JSON node "notifications[0].url_title" should be equal to "test title"
    And the JSON node "notifications[0].created" should exist
    And the JSON node "notifications[0].label" should be equal to "new"
    And the JSON node "notifications[1].label" should be equal to "important"

    When I am authenticated as "test@example.com"
    And I send a "POST" request to "/api/notifications/2cbb5590-3bb6-40a4-b388-c60289bfa188"
    Then the response should be in JSON
    Then the response status code should be 201
    And the JSON node "" should have 2 elements
    And the JSON node "unread" should be equal to "2"

    When I am authenticated as "test@example.com"
    And I send a "GET" request to "/api/notifications"
    Then the response status code should be 200
    And the response should be in JSON
    And the JSON node "" should have 2 elements
    And the JSON node "unread" should be equal to "2"
    And the JSON node "notifications[0].read" should be true

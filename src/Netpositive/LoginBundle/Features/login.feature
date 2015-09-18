Feature: login and register tests
    Scenario: open main page and should be redirect to login
        Given I am on "/"
        Then I should be on "/login"
        But I should see "Login"
        But I should see "Email:"
        But I should see "Password:"
        But I should not see "This is login protected page!"
    Scenario: register try
        Given I am on "/"
        When I follow "Register"
        And I fill in "Email:" with "user@example.com"
        And I fill in "Password:" with "userpass"
        And I fill in "Repeat password:" with "userpass"
        And I fill in "Full name:" with "User User"
        And I fill in "Phone:" with "123456789"
        And I press "Register"
        Then I should see "Congrats User User, your account is now activated."
    Scenario: register try without fullname
        Given I am on "/"
        When I follow "Register"
        And I fill in "Email:" with "user1@example.com"
        And I fill in "Password:" with "user1pass"
        And I fill in "Repeat password:" with "user1pass"
        And I press "Register"
        Then I should see "Congrats user1@example.com, your account is now activated."
    Scenario: register try Repeat password don't match
        Given I am on "/"
        When I follow "Register"
        And I fill in "Email:" with "user2@example.com"
        And I fill in "Password:" with "user2pass"
        And I fill in "Repeat password:" with "sdfsdfsdf"
        And I press "Register"
        Then I should see "The entered passwords don't match"
    Scenario: register try already used email
        Given I am on "/"
        When I follow "Register"
        And I fill in "Email:" with "user1@example.com"
        And I fill in "Password:" with "user1pass"
        And I fill in "Repeat password:" with "user1pass"
        And I press "Register"
        Then I should see "The email is already used"
    Scenario: login try with setted full name
        Given I am on "/"
        When I fill in "Email:" with "user@example.com"
        And I fill in "Password:" with "userpass"
        And I press "Login"
        Then I should see "Hello User User"
        But My Ip stored in user table
    Scenario: login try without setted full name
        Given I am on "/"
        When I fill in "Email:" with "user1@example.com"
        And I fill in "Password:" with "user1pass"
        And I press "Login"
        Then I should see "Hello user1@example.com"
    Scenario: login try with Bad credentials
        Given I am on "/"
        When I fill in "Email:" with "notexsist@example.com"
        And I fill in "Password:" with "notexsist"
        And I press "Login"
        Then I should see "Bad credentials."
    #@mink:goutte
    Scenario: login with Facebook should be redirect to facebook.com
        Given I am on "/"
        #Then show last response
        When I follow "Sign in with Facebook"
        Then I should be on "/v2.0/dialog/oauth"

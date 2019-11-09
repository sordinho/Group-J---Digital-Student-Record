# Use case
- Story 1
- Check child's mark
### Scope
- System
### Level
- User-goal
### Intention in context
- Parent authenticate into the system, expects to see a table showing the marks of the selected child
### Primary actor
- Parent
### Support Actors
- None
### Stakeholder's interests
- The parent expects the table showing the date, grade,  topic and teacher's surname for each mark
- The principal, teacher and class coordinator expect that the tables show consistent informations and correctly load the marks from the server
### Precondition
- Parent successfully authenticated
### Minimum Guarantees
- The table is printed
### Success Guarantees
- The page shows a table with all the marks
### Trigger
- The parent authenticate and access the dashboard page
### Main success scenario

|Step|Description|
|---|---|
|1|The parent click the Login tab|
|2|The system asks for username (email) and password|
|3|The parent enters the credentials and hit the Login button|
|4|The system verifies the credentials|
|5|The system shows an authentication success page|
|6|The system redirects the parent to the dashboard|
|7|The system retrieve all the marks information from the database|
|8|The system prints a table with all the marks for the selected child|

The use case terminates with success

### Extensions

|Step|Extension|Extension step|Description|
|---|---|---|---|
|1|a|1|The login modal doesn't show up|
|3|a|1|The login button does not start any action|
|3|b|1|The parent hit the Cancel button|
| | |2|The login modal disappears|
|4|a|1|The database isn't responding|
| | |2|The system notifies that the database is not reachable|
|4|b|1|The parent enters invalid credentials|
| | |2|The system notifies that the credentials are not valid|
| | |3| The use case continues at step 2|
|7|a|1|The system cannot reach the database|
| | |2|The system notifies that the database is not reachable|
|8|a|1|The table prints no rows|

### Use case diagram

![](story%231_use_case_diagram.png)




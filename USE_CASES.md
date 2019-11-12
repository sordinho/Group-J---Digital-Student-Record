# Use cases
## Use case 1
- Check child's mark
### Scope
- System
### Level
- User-goal
### Intention in context
- Parent authenticates into the system, expects to see a table showing the marks of the selected child
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
- The parent authenticates and accesses the dashboard page
### Main success scenario

|Step|Description|
|---|---|
|1|The parent clicks the Login tab|
|2|The system asks for username (email) and password|
|3|The parent enters the credentials and hits the Login button|
|4|The system verifies the credentials|
|5|The system shows an authentication success page|
|6|The system redirects the parent to the dashboard|
|7|The system retrieves all the marks information from the database|
|8|The system prints a table with all the marks for the selected child|

The use case terminates with success

### Extensions

|Step|Extension|Extension step|Description|
|---|---|---|---|
|1|a|1|The login modal doesn't show up|
|3|a|1|The login button does not start any action|
|3|b|1|The parent hits the Cancel button|
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

![](UseCases/story%231_use_case_diagram.png)

## Use Case 2

- Story 2

### Scope

- Allow teachers to record daily lecture topics 

### Level

- user-goal

### Intention in context

- a teacher must be able to record the daily lecture topics 

### Primary actor

- teacher

#### Support Actors

- none

### Stakeholder's interests

- let all parents and students have track of the daily lecture topics

### Precondition

- Teacher authenticated 
- Teacher had a class in which he teaches

### Minimum Guarantees

- Teacher can insert a text in which explains the daily lecture topics

### Success Guarantees

- the daily lesson topics are correctly registered, so that all students (of the class) and parents can read them

### Trigger

- insertion of a daily lecture topic by the teacher

### Main Succes Scenario

| Step # | Description |
|--------|-------------|
| 1.     | Teacher access the page to record daily lecture topics |
| 2.     | Teacher writes the arguments in the text box |
| 3.     | Teacher confirms the topic of the lesson with the confirm button |
| 4.     | The system inserts the text in the DataBase so that parents and students can access it |


### Extensions
- 4.a teacher refreshes the page before clicking confirm button
- 5.a the DB is busy
- 5.a.1 there's a error message and the topics are not registered

## Use Case 3

- Grant access to parents

### Scope

- Access enable system

### Level

- User-goal

### Intention in context

- Parent credentials generation performed by an administrative officer while authenticated

### Primary actor

- Administrative officer

#### Support Actors

- SMTP server

### Stakeholder's interests

- Let all parents access the Digital Student Record system

### Precondition

- Administrative officer authenticated.

### Minimum Guarantees

- The administrative officer performing the operation receives a feedback concerning the status of the operation.

### Success Guarantees

- A parent has valid credentials to access the system.

### Trigger

- An administrative officer clicks on the "Generate Credentials" button.

### Main Succes Scenario

| Step # | Description |
|--------|-------------|
| 1.     | Administrative officer accesses the "generate parent credential page" |
| 2.     | Administrative officer clicks the button to start the generation of credentials |
| 3.a    | The system computes the credentials for the parents without them |
| 3.b    | The system registers the credentials for each parent |
| 3.c    | The system generates a mail for each parent containing his credentials and forwards it to the SMTP server |
| 4      | The SMTP server sends the email to the parent |  

### Extensions

- 3.a1 the selected parent already has access credentials.
- 4.1 the e-mail address of the parent is not a valid/existing one

## Use Case 4

- Administrative officer performing student enrollment.

### Scope
Enrollment system
### Level
User-goal
### Intention in context
Student enrollment performed by administrative officer while authenticated.
### Primary actor
Administrative officer
#### Support Actors
None
### Stakeholder's interests
Principal: wants that Administrative officers to enroll each student.
### Precondition
The administrative officer has a valid account and is authenticated.
There is at least one student not enrolled yet.

### Minimum Guarantees
A feedback is shown to the user to show the status of the performed operation.
### Success Guarantees
A given user is now enrolled in the school. The anagraphic data is stored in the database.
### Trigger
The administrative officer clicks the "Enroll student" button.
### Main Succes Scenario

- <1> Administrative officer visits the *Enrollment* page
- <2> the system retrieves the data for the student that needs to be enrolled
- <3> the system shows the form to insert data for the given student
- <4> administrative officer checks the fields 
- <5> user clicks on the *Enroll student* button
- <6> the system shows the success feedback message
- <7> the system saves the enrollment and update its status

### Extensions
In step 4, if there is any issue (i.e. the student was already enrolled by another administrative officer), a warning message of failure is shown to the user and a log is saved
- <4a> DB not responding
- <4a.1 > An error message of failure is shown to the user and a log is saved
- <4a.2> user is redirected to the homepage
- <4b> Student already enrolled
- <4b.1> a warning message of failure is shown to the user
- <4b.2> user is redirected to the homepage

## Use Case 5

Give classes its composition

### Scope

School administration system

### Level

User-goal

### Intention in context

Administrative officer is authenticated and wants to perform the insertion of classes composition

### Primary actor

Administrative officer

#### Support Actors

None 

### Stakeholder's interests

Define classes composition: which student are in each class. 

### Precondition

- Administrative officer is authenticated
- Students needs are already enrolled 

### Minimum Guarantees

A feedback is shown to the user

### Success Guarantees

Information about the chosen class composition is saved
A class composition is defined for a class, following this constraint:
- number of student is at maximum 30

### Trigger

None

### Main Success Scenario

- <1> Administrative officer visits the *Class Composition* page
- <2> Administrative officer selects a specific class for which the composition is to be chosen
- <3> The system shows the information about the class chosen (if already present) and data about students needing to be collocated
- <4> The system displays which are the constraints to follow 
- <5> Administrative officer selects which students need be inserted in that class
- <6> Administrative officer clicks on the *Submit Composition* button
- <7> The system saves the composition and updates its status
- <8> The system shows a success feedback message

### Extensions
|Step#| Description|
|----|---------------------------------------------|
|1a1 | Login for administrative officer is expired |
|1a2 | An error message is shown, detailing the issue |
|1a3 | Administrative officer is redirected to the login page |
|6a1 | The number of student inserted is < 15 or > 30 |
|6a2 | An error message is shown |
|6a3 | Scenario restart from 3 |
|7a1 | DB is not responding |
|7a2 | An alert is shown |

## Use Case 6

Teacher assign a grade to a student

### Scope

Mark assignment system

### Level

User-goal

### Intention in context

Mark's registration performed by authenticated teacher to an enrolled student

### Primary actor

Teacher

#### Support Actors

None

### Stakeholder's interests

Parent: want the marks to be registered on the system so they can keep track of their children performances

### Precondition

- The teacher has an account and is already authenticated by the system
- The theacher teachs in the class where the student is enrolled

### Minimum Guarantees

The mark goes from 0 to 10 with 0.25 steps

### Success Guarantees

The mark is registered so that the new average is caclulated for the student.

### Trigger

None

### Main Succes Scenario

1. Teacher authenticates (see UC-1)
2. Teacher access the pages to give marks
3. System retrieve the students enrolled in the classes where the teacher is teaching in the current year
4. Teacher choose the student between the enrolled ones
5. System shows up a list of possibles grades(see minimum guarantees)
6. Teacher choose a mark in the given list
7. Teacher confirms the choosen mark by clicking the confirm button
8. The system register the new marks for the given course to the specific student career
9. The system shows the success feedback message

### Extensions

- 3.a teacher has no teaching classes
- 6.a teacher cancel the operation
- 6.b teacher refresh the page before the mark is sent to the server.
- 7.a the DB is busy
- 8.a an error message is shown and mark is not registered

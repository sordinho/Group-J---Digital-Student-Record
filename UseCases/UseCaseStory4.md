# Use Case 4

## Use Case

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
A given user is now enrolled in the school. All the anagraphic datas are stored in the database.
### Trigger
None
### Main Succes Scenario

- <1> Administrative officer visit the *Enrollment* page
- <2> the system retrieves all the data for the student that needs to be enrolled
- <3> the system shows the form to insert data for the given student
- <4> administrative officer checks the fields 
- <5> user clicks on the *Enroll student* button
- <6> the system shows the success feedback message
- <7> the system save the enrollment and update its status.
### Extensions
In step 4, if there is any issue (i.e. the student was already enrolled by another administrative officer), a warning message of failure is shown to the user and a log is saved.
- <4a> DB not responding
- <4a.1 > An error message of failure is shown to the user and a log is saved.
- <4a.2> user is redirected to the homepage.
- <4b> Student already enrolled
- <4b.1> a warning message of failure is shown to the user.
- <4b.2> user is redirected to the homepage.

## Use Case Story #6

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

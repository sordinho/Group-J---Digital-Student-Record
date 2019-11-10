# Use cases template

## Use Case

- Story 2

### Scope

- Allow teachers to record daily lecture topics 

### Level

- user-goal
- parents and students can be informed about lecture topics and have an official 
  record for institutional purposes 

### Intention in context

- a teacher must be able to record the daily lecture topics 

### Primary actor

- teacher

#### Support Actors

- none

### Stakeholder's interests

- let all parents and students have a track of the daily lecture topics

### Precondition

- Teacher authenticated 
- Teacher had a class in which he teaches

### Minimum Guarantees

- Teacher can insert a text in which explains the daily lecture topics

### Success Guarantees

- the daily lesson topics are registered correctly, so that all students (of the class) and parents can read them

### Trigger

- insertion of a daily lecture topic by the teacher

### Main Succes Scenario

| Step # | Description |
|--------|-------------|
| 1.     | Teacher authenticates to the personal teacher page|
| 2.     | Teacher access the page to record daily lecture topics |
| 3.     | Teacher writes the arguments in the text box |
| 4.     | Teacher confirms the topic of the lesson with the confirm button |
| 5.     | The system inserts the text in the DataBase so that parents and students can access it |


### Extensions
- 1.a Teacher has access credentials.
- 4.a teacher refreshes the page before clicking confirm button
- 4.b teacher doesn't click on confirm button
- 5.a the DB is busy
- 5.a.1 there's a error message and the topics are not registered
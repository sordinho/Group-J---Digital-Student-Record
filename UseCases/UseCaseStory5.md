# Use cases template

## Use Case

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

Administrative officer is authenticated

### Minimum Guarantees

A feedback is shown to the user

### Success Guarantees

Information about the chosen class composition is saved
A class composition is defined for a class, following these constraints:
- number of student is at minimum 15 for the years after first
- no minimum number of student is defined for the first year
- number of student is at maximum 30

### Trigger

The administrative officer select a class and clicks on "Define class composition"

### Main Success Scenario

- <1> Administrative officer visits the *Class Composition* page
- <2> Administrative officer selects a specific class for which the composition is to be chosen
- <3> The system shows the information about the class chosen (if already present) and data about students needing to be collocated
- <4> The system displays which are the constraints to follow 
- <5> Administrative officer selects which students need be inserted in that class
- <6> Administrative officer clicks on the *Submit Composition* button
- <7> The system shows the success feedback message
- <8> The system saves the composition and updates its status

### Extensions

|1a1 | Login for administrative officer is expired |
|1a2 | An error message is shown, detailing the issue |
|1a3 | Administrative officer is redirected to the login page |
|6a1 | The number of student inserted is < 15 or > 30 |
|6a2 | An error message is shown |
|6a3 | Scenario restart from 3 |
|7a1 | DB is not responding |
|7a2 | An alert is shown |

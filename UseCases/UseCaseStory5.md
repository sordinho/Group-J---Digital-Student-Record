# Use cases template

## Use Case

Give classes its composition

### Scope

School administration system

### Level

User-goal

### Intention in context

Administrative officier is authenticated and wants to perform the insertion of classes composition

### Primary actor

Administrative officier

#### Support Actors

None 

### Stakeholder's interests

Define classes composition based on:
- which student are in each class
- what subjects are taught in each class
- weekly number of hours per subject

### Precondition

Administrative officier is authenticated

### Minimum Guarantees

A feedback is shown to the user

### Success Guarantees

Information about subjects for each class and students for each class are saved
A class composition is defined for a class, following these constraints:
- number of student is at minimum 15 for the years after first
- no minimum number of student is defined for the first year
- number of student is at maximum 30

### Trigger

The administrative officier select a class and clicks on "Define class composition"

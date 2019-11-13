# Use cases template

## Use Case

- Grant access to parents

### Scope

- Access enable system

### Level

- User-goal

### Intention in context

- Parent credentials generation performed by an administrative officier while authenticated

### Primary actor

- Administrative officier

#### Support Actors

- SMTP server

### Stakeholder's interests

- Let all parents access the Digital Student Record system

### Precondition

- Administrative officier authenticated.

### Minimum Guarantees

- The administrative officier performing the operation receives a feedback concerning the status of the operation.

### Success Guarantees

- A parent has valid credentials to access the system.

### Trigger

- An administrative officier clicks on the "Generate Credentials" button.

### Main Succes Scenario

| Step # | Description |
|--------|-------------|
| 1.     | Administrative officier access the "generate parent credential page". |
| 2.     | Administrative officier clicks on the button to start the generation of credentials |
| 3.a    | The system computes the credentials for the parents without them |
| 3.b    | The system registers the credentials for each parent |
| 3.c    | The system generates a mail for each parent containing his credentials and forwards it to the SMTP server |
| 4      | The SMTP server sends the email to the parent |  

### Extensions

- 3.a1 the selected parent already has access credentials.
- 4.1 the e-mail address of the pared is not a valid/existing one
- 4.2 there is no internet connection
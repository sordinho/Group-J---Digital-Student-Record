# Use cases template

## Use Case

- Name
- Grant access to parents

### Scope

### Level

### Intention in context

### Primary actor

- Administrative officier

#### Support Actors

- SMTP server

### Stakeholder's interests

- Let all parents access the Digital Student Record system

### Precondition

- Administrative officier authenticated.

### Minimum Guarantees

### Success Guarantees

### Trigger

### Main Succes Scenario

| Step # | Description |
|--------|-------------|
| 1.     | Administrative officier access the "generate parent credential page". |
| 2.     | Administrative officier enters parent's master data |
| 3.a    | The system computes the credentials for the selected parent |
| 3.b    | The system registers the input data and the credentials |
| 3.c    | The system generates a mail containing the computed credentials for the selected parent and forwards it to the SMTP server |
| 4      | The SMTP server sends the email to the parent |

### Extensions

- 3.a1 the selected parent already has access credentials.
- 4.1 the e-mail address of the pared is not a valid/existing one
- 4.2 there is no internet connection
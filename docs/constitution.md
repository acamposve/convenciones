# Convenciones — Constitution

**Version:** 1.0
**Status:** Active
**Scope:** Legacy modernization, migration, and future development of Convenciones

---

# 1. Purpose

Convenciones is a contract intelligence platform originally developed as a legacy PHP application and currently undergoing modernization.

This constitution defines the engineering principles and constraints that must govern any analysis, modernization, migration, replacement, or new development related to Convenciones.

The objective is not to rewrite the legacy system for the sake of using newer technology.

The objective is to:

* Preserve business value and operational continuity.
* Understand and document existing business behavior.
* Protect existing data.
* Reduce technical debt.
* Improve security.
* Improve maintainability.
* Enable automated testing.
* Establish clear architectural boundaries.
* Enable future contract intelligence and AI capabilities.
* Support multi-tenant operation.
* Progressively retire legacy components when appropriate.
* Minimize unnecessary migration risk.

Technology decisions must follow the business and technical requirements rather than the other way around.

---

# 2. Core Principle

> **Understand before replacing. Preserve business value, not legacy implementation.**

The legacy application is a source of business behavior and historical knowledge.

It is not automatically considered a valid architectural reference.

Existing code must not be reproduced merely because it already exists.

At the same time, undocumented behavior must not be arbitrarily removed or changed without understanding its business impact.

---

# 3. Modernization Is Not Automatically a Rewrite

Convenciones must not assume that the correct strategy is:

```text
Legacy PHP → New Application
```

The modernization analysis must consider multiple strategies, including:

1. Incremental modernization of the existing PHP application.
2. Migration of selected functionality to Laravel.
3. Progressive replacement using the existing/new .NET platform.
4. Hybrid architecture.
5. Strangler Fig migration.
6. Temporary coexistence between legacy and modern components.
7. Complete replacement, only when justified.

The selected strategy must be based on evidence from the existing system.

Technology preference alone is insufficient justification.

---

# 4. Architecture Decision Principles

Architecture decisions must optimize for:

1. Business continuity
2. Data integrity
3. Security
4. Correct business behavior
5. Maintainability
6. Testability
7. Operational simplicity
8. Extensibility
9. Performance
10. Development velocity

Architecture must remain proportional to the actual complexity of the problem.

---

# 5. Avoid Overengineering

Convenciones must not introduce architectural complexity without a concrete reason.

Avoid introducing:

* Microservices without a clear business or technical justification.
* Distributed systems where a modular monolith is sufficient.
* Excessive interfaces.
* Generic repositories without meaningful abstraction.
* Excessive CQRS.
* Event-driven architecture without a concrete use case.
* Unnecessary messaging infrastructure.
* Abstractions created only for theoretical flexibility.
* Multiple databases without a justified requirement.
* Infrastructure complexity that cannot be operated reliably.

The preferred solution is the simplest architecture that satisfies the actual requirements.

---

# 6. Domain Understanding

Convenciones is a contract intelligence system.

The modernization effort must explicitly identify and preserve the business concepts involved in:

* Contracts
* Companies
* Clients
* Documents
* Clauses
* Contract periods
* Contract status
* Users
* Roles
* Permissions
* Document versions
* Document processing
* Contract comparison
* Compliance analysis
* Classification
* Audit/history

The actual legacy system must be inspected before assuming that these concepts are implemented correctly or completely.

---

# 7. Legacy System Assessment

Before significant migration work begins, the legacy application must be analyzed.

The assessment must identify:

## Application

* PHP version
* Framework or custom architecture
* Entry points
* Routes
* Pages
* Controllers
* Includes/requires
* Global state
* Sessions
* Authentication
* Authorization
* Cron jobs
* CLI processes
* Background processes

## Business functionality

Identify:

* Major modules
* Major workflows
* Business rules
* Calculations
* Validations
* State transitions
* Notifications
* Reports
* Imports
* Exports
* File processing
* Integrations

## Database

Identify:

* Tables
* Primary keys
* Foreign keys
* Relationships
* Indexes
* Constraints
* Views
* Stored procedures
* Triggers
* Historical data
* Duplicate data
* Orphan records
* Data inconsistencies

## Infrastructure

Identify:

* Web server
* PHP runtime
* Database
* File storage
* Cache
* Queue mechanisms
* Cron
* External services
* Email infrastructure
* Deployment process
* Backups
* Monitoring
* Logging

---

# 8. Legacy Behavior Classification

Every significant legacy behavior discovered during analysis should be classified as one of:

* **Required business behavior**
* **Undocumented but relied-upon behavior**
* **Confirmed defect**
* **Security vulnerability**
* **Technical debt**
* **Dead code**
* **Deprecated functionality**
* **Unknown behavior**

Unknown behavior must remain explicitly marked as unknown until sufficient evidence exists.

The migration must not silently convert assumptions into requirements.

---

# 9. Data Is a First-Class Asset

Existing Convenciones data must be treated as a critical business asset.

No migration may assume that the database is clean.

The assessment must identify:

* Data quality issues
* Duplicate records
* Missing relationships
* Invalid values
* Legacy identifiers
* Inconsistent status values
* Historical records
* Orphan records
* Incomplete records
* Encoding issues
* Legacy assumptions encoded in the schema

Data cleanup and application migration must be treated as separate concerns unless there is a specific reason to combine them.

---

# 10. Database Migration

Any database migration must document:

* Schema changes
* Data transformations
* Backfill requirements
* Compatibility requirements
* Validation strategy
* Rollback strategy
* Production deployment considerations

Existing data must not be destroyed merely to simplify the migration.

If a legacy schema is inadequate, the migration must determine whether to:

* Modify it incrementally.
* Introduce compatibility structures.
* Create a new schema.
* Synchronize old and new models temporarily.
* Migrate data progressively.

---

# 11. Multi-Tenancy

Convenciones must be designed as a multi-tenant system.

The tenant boundary must be explicit.

Unless requirements establish otherwise:

> **Data belonging to one tenant must never be accessible to another tenant.**

Tenant isolation must be enforced at the application and authorization boundaries, not merely assumed from UI behavior.

Every migration involving business data must identify:

* Tenant ownership
* Tenant resolution
* Tenant authorization
* Cross-tenant risks
* Existing legacy assumptions

The legacy implementation must be inspected to determine whether tenant isolation is currently enforced correctly.

---

# 12. Security

Security is a mandatory architectural concern.

The modernization must explicitly assess:

* Authentication
* Authorization
* Password storage
* Session handling
* SQL injection
* XSS
* CSRF
* File upload security
* Access control
* Sensitive information exposure
* Secrets
* Hardcoded credentials
* Insecure dependencies
* Authorization bypasses
* Tenant isolation
* Data exposure
* Logging of sensitive information

New code must follow secure-by-default practices.

Known critical security vulnerabilities must not be carried forward merely for compatibility.

---

# 13. Authentication and Authorization

Authentication and authorization must be treated as separate concerns.

Authorization must be enforced server-side.

The migration must identify the existing:

* Users
* Roles
* Permissions
* Administrative privileges
* Resource-level permissions
* Tenant-level permissions

Visibility of a UI element must never be considered sufficient authorization.

---

# 14. Contract and Document Integrity

Contracts and documents are core business assets.

The system must preserve:

* Original documents
* Document metadata
* Document versions
* Relationships to contracts
* Processing status
* Extraction results
* Audit information
* Relevant historical information

A migration must not modify or overwrite original documents without explicit authorization and traceability.

Derived information must be distinguishable from source information.

---

# 15. AI and Document Intelligence

AI functionality must be introduced as a controlled capability rather than embedded indiscriminately throughout the system.

AI-generated or AI-assisted information must be distinguishable from authoritative source data.

The system should preserve, where applicable:

* Source document
* Extracted text
* Processing status
* Model/provider
* Processing version
* Confidence
* Generated result
* Relevant timestamps
* Error state

AI results must not silently replace source information.

The architecture must allow AI providers or models to evolve without unnecessarily coupling the core domain to a specific provider.

---

# 16. Processing Pipeline

Document processing should be treated as a pipeline with explicit stages where applicable.

Potential stages include:

```text
Ingestion
   ↓
Type Detection
   ↓
Text Extraction
   ↓
Text Normalization
   ↓
Classification
   ↓
Analysis
   ↓
Comparison / Compliance
```

The actual pipeline must be determined by the system requirements and existing implementation.

Each stage should have:

* Explicit input
* Explicit output
* Defined failure behavior
* Observable status
* Retry strategy where appropriate

A failed processing stage must not silently appear as a successful operation.

---

# 17. API-First Design

New functionality should expose well-defined application boundaries.

APIs must:

* Have explicit contracts.
* Validate input.
* Enforce authorization.
* Respect tenant boundaries.
* Return meaningful errors.
* Avoid leaking internal implementation details.

Internal implementation details must not become accidental API contracts.

---

# 18. Architecture for New Components

New functionality should favor clear boundaries between:

```text
Presentation
     ↓
Application
     ↓
Domain
     ↓
Infrastructure
```

The exact implementation may vary depending on the technology selected.

For Laravel, this may be implemented using appropriate Laravel conventions.

For .NET, existing Convenciones architecture principles may be used where applicable.

The architecture must remain pragmatic.

---

# 19. Technology-Neutral Migration Analysis

When comparing PHP, Laravel, .NET, or other technologies, the analysis must distinguish:

### Facts

What is demonstrably present in the current system.

### Requirements

What the future system actually needs.

### Assumptions

What has not yet been verified.

### Trade-offs

Benefits and costs associated with each option.

### Risks

Potential failure modes and migration risks.

Technology selection must not be based solely on familiarity or novelty.

---

# 20. Testing Strategy

The modernization must progressively establish automated tests.

Testing should include, where appropriate:

* Characterization tests
* Unit tests
* Feature tests
* Integration tests
* Database tests
* API tests
* End-to-end tests

Critical business workflows should receive priority.

Tests should validate behavior rather than implementation details.

Where legacy behavior is poorly understood, characterization tests should be used before modifying that behavior.

---

# 21. Code Quality

New code must:

* Use meaningful names.
* Have clear responsibilities.
* Avoid duplication.
* Minimize hidden side effects.
* Keep business logic explicit.
* Handle errors deliberately.
* Avoid unnecessary complexity.
* Follow the conventions of its chosen framework.

Legacy code should not be mechanically copied into the new architecture.

---

# 22. Observability

Modernized components must provide sufficient observability.

Where appropriate, use:

* Structured logging
* Request identifiers
* Correlation identifiers
* Health checks
* Metrics
* Processing status
* Audit logs

Sensitive information must not be logged.

For document processing, failures must be observable and diagnosable.

---

# 23. Backward Compatibility

During incremental migration, compatibility must be considered explicitly.

The migration plan must identify:

* Existing URLs
* Existing API contracts
* Existing integrations
* Existing authentication flows
* Existing database consumers
* Existing scheduled jobs
* Existing file formats
* Existing reports

Breaking changes must be deliberate and documented.

---

# 24. Deployment Strategy

Every migration phase must consider production deployment.

The plan must identify:

* Application changes
* Database changes
* Configuration changes
* Environment variables
* External services
* Deployment order
* Rollback strategy
* Backward compatibility

Migration phases should be independently deployable whenever practical.

---

# 25. Legacy Retirement

Migration is not complete merely because new code exists.

For every migrated component, the plan must identify:

* Legacy code being replaced
* Dependencies preventing removal
* Temporary compatibility mechanisms
* Conditions required for removal
* Verification required before removal

Legacy code should be removed once its replacement is proven and no legitimate dependency remains.

---

# 26. Migration Alternatives

The modernization plan must explicitly evaluate:

### Option A — Incremental PHP modernization

Continue improving the existing application while progressively reducing technical debt.

### Option B — Laravel migration

Move functionality progressively into a Laravel-based architecture.

### Option C — .NET modernization

Progressively replace legacy functionality using the modern Convenciones platform.

### Option D — Hybrid modernization

Use different technologies for different bounded areas while maintaining explicit integration boundaries.

### Option E — Full replacement

Replace the legacy application with a new platform.

This option requires strong justification because of its potentially higher business and migration risk.

The analysis must not select an option before understanding the actual characteristics of the legacy system.

---

# 27. Migration Decision Framework

Each alternative should be evaluated against:

| Criterion              | Consideration                                         |
| ---------------------- | ----------------------------------------------------- |
| Business continuity    | Can the business continue operating during migration? |
| Migration risk         | How much can go wrong?                                |
| Data risk              | How difficult is data preservation?                   |
| Security               | Does the option improve the security posture?         |
| Maintainability        | Does it reduce technical debt?                        |
| Testability            | Can automated testing be established?                 |
| Development velocity   | Can future features be delivered efficiently?         |
| Scalability            | Can the solution support future growth?               |
| AI integration         | Can document intelligence be incorporated cleanly?    |
| Multi-tenancy          | Can tenant isolation be enforced correctly?           |
| Operational complexity | Can the solution be operated reliably?                |
| Long-term cost         | What is the expected maintenance burden?              |
| Legacy retirement      | Can the legacy system eventually be removed?          |

The evaluation must provide evidence and trade-offs rather than technology advocacy.

---

# 28. Migration Phases

A migration plan should generally follow this logical progression:

```text
Discovery
   ↓
Characterization
   ↓
Risk Reduction
   ↓
Foundation
   ↓
Incremental Migration
   ↓
Validation
   ↓
Legacy Retirement
```

The exact phases must be determined from the actual system.

No phase should be created merely because it is architecturally fashionable.

---

# 29. Definition of Done

A migrated component is complete when:

* Its behavior is understood.
* Required functionality is implemented.
* Appropriate automated tests exist.
* Security requirements are satisfied.
* Tenant isolation is verified.
* Data integrity is verified.
* Deployment requirements are understood.
* Observability exists where necessary.
* Legacy dependencies are identified.
* Rollback considerations are documented.
* Legacy implementation can be removed or its continued existence is explicitly justified.

---

# 30. Required Analysis Before Implementation

Before modifying the legacy application or creating significant new implementation, Copilot must produce an analysis containing:

## 30.1 Current State

Describe how the existing Convenciones system actually works.

## 30.2 System Inventory

Identify:

* Modules
* Routes
* PHP files
* Database tables
* Integrations
* Jobs
* Users
* Roles
* Permissions
* Document workflows

## 30.3 Dependency Map

Identify dependencies between:

* Modules
* Business processes
* Database entities
* External systems
* Shared code

## 30.4 Business Capability Map

Map the existing functionality to business capabilities.

## 30.5 Data Model

Document the relevant current data model and identify data quality concerns.

## 30.6 Security Assessment

Identify security weaknesses and their potential impact.

## 30.7 Technical Debt Assessment

Identify the major sources of technical debt.

## 30.8 Migration Options

Evaluate PHP modernization, Laravel, .NET, hybrid, and replacement strategies.

## 30.9 Recommended Migration Strategy

Provide a recommendation based on evidence discovered during analysis.

## 30.10 Migration Roadmap

Produce incremental phases with:

* Scope
* Dependencies
* Risks
* Deliverables
* Tests
* Deployment considerations
* Rollback considerations

## 30.11 Open Questions

Explicitly list questions that cannot be answered from the repository.

---

# 31. Copilot Operating Rules

When analyzing Convenciones, Copilot must:

1. Inspect the repository before proposing implementation.
2. Use evidence from the existing code.
3. Avoid inventing business rules.
4. Clearly identify assumptions.
5. Mark unknown behavior as unknown.
6. Avoid modifying files during the initial analysis.
7. Prefer incremental migration.
8. Preserve data integrity.
9. Treat security findings as first-class concerns.
10. Avoid unnecessary architecture.
11. Avoid blindly translating legacy code.
12. Document significant architectural decisions.
13. Separate business requirements from implementation details.
14. Identify opportunities to remove legacy functionality rather than automatically migrating everything.
15. Consider whether functionality should be migrated, replaced, redesigned, or retired.

---

# 32. Prohibited Practices

The following are prohibited unless explicitly justified:

* Blind PHP-to-Laravel translation.
* Blind PHP-to-.NET translation.
* Big-bang rewrite without analysis.
* Microservices without demonstrated need.
* Reproducing known legacy defects.
* Copying legacy architecture into the new system.
* Deleting legacy functionality without understanding its usage.
* Destructive database migration without validated backups and rollback planning.
* Removing historical data to simplify implementation.
* Assuming undocumented behavior is irrelevant.
* Treating UI restrictions as authorization.
* Storing secrets in source code.
* Introducing AI without traceability.
* Allowing AI-generated data to silently overwrite authoritative source data.
* Adding infrastructure without operational justification.
* Introducing abstractions solely to satisfy architectural patterns.

---

# 33. Guiding Principles

The following principles summarize the constitution:

> **Business value over legacy implementation.**

> **Evidence over assumptions.**

> **Incremental migration over unnecessary big-bang rewrites.**

> **Data integrity over migration convenience.**

> **Security by default.**

> **Simple architecture over unnecessary complexity.**

> **Tests before changing poorly understood behavior.**

> **Explicit boundaries over hidden coupling.**

> **Observable processes over opaque automation.**

> **AI-assisted intelligence must remain traceable to its source.**

> **Technology serves the business; the business does not serve the technology.**

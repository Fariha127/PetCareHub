# PetCareHub – Pet Adoption & Veterinary Care Management System

PetCareHub is a small-to-medium DBMS-focused web project for a university course. It is structured around Oracle SQL concepts first, with a Laravel-style PHP backend and simple Bootstrap views.

## What is included

- ER diagram
- Oracle relational schema
- SQL tables, views, triggers, procedures, and sample queries
- Laravel-style migrations, models, controllers, and routes
- Basic Bootstrap pages for home, pets, login/register, and dashboards

## Core database concepts demonstrated

- Normalization and relationships
- Primary keys, foreign keys, constraints
- Joins and aggregate queries
- Views for dashboard/reporting
- Stored procedures and transactions
- Trigger-based status update after adoption approval
- Search, filter, and role-based operations

## Project structure

- `laravel-app/` - runnable Laravel application root
- `laravel-app/database/ER_DIAGRAM.md` - Mermaid ER diagram
- `laravel-app/database/schema_oracle.sql` - Oracle table schema and views
- `laravel-app/database/procedures_triggers.sql` - Oracle triggers and procedures
- `laravel-app/database/sample_queries.sql` - Example joins, filters, and reports
- `laravel-app/database/migrations/` - Laravel migrations
- `app/`, `routes/`, `resources/` - legacy duplicate scaffold removed from the repo root

## Quick setup idea

1. Import the Oracle schema from `laravel-app/database/schema_oracle.sql`.
2. Run the trigger and procedure script in `laravel-app/database/procedures_triggers.sql`.
3. Use the sample queries for reports and testing.
4. Run the Laravel app from `laravel-app/`.

## Main modules

- User management with roles: Admin, Shelter Staff, Veterinarian, User/Adopter
- Pet management with search, filters, and sorting
- Adoption request workflow with approve/reject logic
- Veterinary care records and appointments
- Shelter capacity tracking
- Dashboard metrics for administration and reporting

-- PetCareHub - Sample SQL Queries for Oracle Database

-- ==========================================
-- I. BASIC QUERIES & FILTERING
-- ==========================================

-- 1. Pet Listing
-- Purpose: Retrieve a descending list of all registered pets.
SELECT pet_id, pet_name, species, breed, age, adoption_status
FROM pets
ORDER BY created_at DESC;

-- 2. Filter Available Dogs Younger than 5 Years
-- Purpose: Demonstrates filtering using multiple AND clauses and sorting.
SELECT pet_name, species, breed, age, vaccination_status
FROM pets
WHERE species = 'Dog'
  AND age < 5
  AND adoption_status = 'AVAILABLE'
ORDER BY age ASC;

-- 3. Search Pets by Breed Keyword (using View)
-- Purpose: Shows querying a database view and performing case-insensitive searches.
SELECT *
FROM vw_pet_listing
WHERE LOWER(breed) LIKE LOWER('%labrador%');


-- ==========================================
-- II. JOINS (INNER & OUTER)
-- ==========================================

-- 4. Pending Adoption Requests with Adopter and Pet Details
-- Purpose: Inner Join of three tables to resolve relationships.
SELECT ar.request_id, u.full_name AS adopter, p.pet_name, ar.request_date, ar.status
FROM adoption_requests ar
JOIN users u ON u.user_id = ar.user_id
JOIN pets p ON p.pet_id = ar.pet_id
WHERE ar.status = 'PENDING'
ORDER BY ar.request_date DESC;

-- 5. List All Veterinarians and their Appointment Counts (Outer Join)
-- Purpose: Left Join to ensure veterinarians with zero appointments are still displayed.
SELECT u.user_id, u.full_name AS veterinarian, COUNT(va.appointment_id) AS total_appointments
FROM users u
LEFT JOIN veterinary_appointments va ON va.vet_id = u.user_id
WHERE u.role = 'VETERINARIAN'
GROUP BY u.user_id, u.full_name
ORDER BY total_appointments DESC;

-- 6. List All Pets with Their Medical Records (Outer Join)
-- Purpose: Left Join to list all pets alongside their clinical history, including pets without records.
SELECT p.pet_id, p.pet_name, mr.diagnosis, mr.treatment, mr.prescription
FROM pets p
LEFT JOIN medical_records mr ON mr.pet_id = p.pet_id
ORDER BY p.pet_name;


-- ==========================================
-- III. AGGREGATIONS & GROUPING WITH HAVING
-- ==========================================

-- 7. Current Shelter Occupancy Count
-- Purpose: Simple aggregate query to count pets in the shelter.
SELECT COUNT(*) AS current_pet_count
FROM pets
WHERE adoption_status <> 'ADOPTED';

-- 8. Adoption Statistics by Month
-- Purpose: Grouping using date conversion functions.
SELECT TO_CHAR(request_date, 'YYYY-MM') AS month_key, COUNT(*) AS adopted_count
FROM adoption_requests
WHERE status = 'APPROVED'
GROUP BY TO_CHAR(request_date, 'YYYY-MM')
ORDER BY month_key;

-- 9. Species with More Than 2 Vaccinated Pets
-- Purpose: Demonstrates filtering aggregated groups using the HAVING clause.
SELECT species, COUNT(*) AS vaccinated_pets_count
FROM pets
WHERE vaccination_status = 'VACCINATED'
GROUP BY species
HAVING COUNT(*) >= 2;


-- ==========================================
-- IV. SUBQUERIES & CORRELATED QUERIES
-- ==========================================

-- 10. Find Pets with Above-Average Age
-- Purpose: Shows a nested subquery returning a single scalar value.
SELECT pet_id, pet_name, species, age
FROM pets
WHERE age > (SELECT AVG(age) FROM pets)
ORDER BY age DESC;

-- 11. Find Users who have Submitted at Least One Adoption Request (EXISTS)
-- Purpose: Demonstrates a correlated subquery using the EXISTS operator.
SELECT u.user_id, u.full_name, u.email
FROM users u
WHERE EXISTS (
    SELECT 1 
    FROM adoption_requests ar 
    WHERE ar.user_id = u.user_id
);

-- 12. Find Pets that have Never Had a Medical Record (NOT EXISTS)
-- Purpose: Identifies records with no matches in a child table.
SELECT p.pet_id, p.pet_name, p.species
FROM pets p
WHERE NOT EXISTS (
    SELECT 1 
    FROM medical_records mr 
    WHERE mr.pet_id = p.pet_id
);


-- ==========================================
-- V. DATE ARITHMETIC & SET OPERATIONS
-- ==========================================

-- 13. Find Pets Due for a Vaccination Checkup in the Next 30 Days
-- Purpose: Demonstrates date calculations and date comparisons.
SELECT p.pet_name, mr.diagnosis, mr.next_vaccine_date
FROM medical_records mr
JOIN pets p ON p.pet_id = mr.pet_id
WHERE mr.next_vaccine_date BETWEEN SYSDATE AND SYSDATE + 30;

-- 14. List Unique Emails of All Staff and Vets (UNION)
-- Purpose: Set operation combining emails of two specific user categories.
SELECT email, full_name, 'Staff' AS role_category FROM users WHERE role = 'SHELTER_STAFF'
UNION
SELECT email, full_name, 'Vet' AS role_category FROM users WHERE role = 'VETERINARIAN'
ORDER BY role_category, full_name;

-- 15. List Pets that have both an Appointment AND a Medical Record (INTERSECT)
-- Purpose: Set intersection finding pets matching both criteria.
SELECT pet_id, pet_name FROM pets WHERE pet_id IN (
    SELECT pet_id FROM veterinary_appointments
    INTERSECT
    SELECT pet_id FROM medical_records
);

-- ==========================================
-- VI. PL/SQL FUNCTIONS & LOOP ACTIONS
-- ==========================================

-- 16. Query Pets and Categorize Their Age Group (PL/SQL Function)
-- Purpose: Demonstrates using the user-defined scalar function `fn_get_pet_age_group`.
SELECT pet_name, age, fn_get_pet_age_group(age) AS age_group
FROM pets;

-- 17. List Adopters alongside their Total Request Count (PL/SQL Function)
-- Purpose: Demonstrates using the user-defined scalar function `fn_get_adopter_request_count`.
SELECT user_id, full_name, email, fn_get_adopter_request_count(user_id) AS total_requests
FROM users
WHERE role = 'USER';

-- 18. Execute and Print Vaccination Schedule (Cursor FOR Loop)
-- Purpose: Executes the stored procedure containing the implicit cursor FOR loop.
-- Note: Enable DBMS Output in SQL developer/SQL*Plus (SET SERVEROUTPUT ON) to view the printed output.
BEGIN
    sp_print_vaccination_schedule;
END;
/

-- 19. Run Occupancy Growth Simulation (WHILE Loop)
-- Purpose: Executes the stored procedure containing the WHILE loop simulation.
BEGIN
    sp_simulate_occupancy_growth(5); -- Simulate for 5 iterations
END;
/

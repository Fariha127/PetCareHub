-- PetCareHub sample SQL queries for a single large shelter

-- 1. Pet listing
SELECT pet_id, pet_name, species, breed, age, adoption_status
FROM pets
ORDER BY created_at DESC;

-- 2. Filter available dogs younger than 5 years
SELECT pet_name, species, breed, age, vaccination_status
FROM pets
WHERE species = 'Dog'
  AND age < 5
  AND adoption_status = 'AVAILABLE'
ORDER BY age ASC;

-- 3. Search pets by breed keyword
SELECT *
FROM vw_pet_listing
WHERE LOWER(breed) LIKE LOWER('%labrador%');

-- 4. Pending adoption requests with adopter and pet details
SELECT ar.request_id, u.full_name AS adopter, p.pet_name, ar.request_date, ar.status
FROM adoption_requests ar
JOIN users u ON u.user_id = ar.user_id
JOIN pets p ON p.pet_id = ar.pet_id
WHERE ar.status = 'PENDING'
ORDER BY ar.request_date DESC;

-- 5. Current shelter occupancy, assuming capacity is managed by the app
SELECT COUNT(*) AS current_pet_count
FROM pets
WHERE adoption_status <> 'ADOPTED';

-- 6. Adoption statistics by month
SELECT TO_CHAR(request_date, 'YYYY-MM') AS month_key, COUNT(*) AS adopted_count
FROM adoption_requests
WHERE status = 'APPROVED'
GROUP BY TO_CHAR(request_date, 'YYYY-MM')
ORDER BY month_key;

-- 7. Vaccination report
SELECT species, COUNT(*) AS vaccinated_pets
FROM pets
WHERE vaccination_status = 'VACCINATED'
GROUP BY species;

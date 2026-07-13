CREATE TABLE users (
    user_id VARCHAR2(32) DEFAULT RAWTOHEX(SYS_GUID()) PRIMARY KEY,
    full_name VARCHAR2(100) NOT NULL,
    email VARCHAR2(120) NOT NULL UNIQUE,
    password VARCHAR2(255) NOT NULL,
    phone VARCHAR2(20),
    role VARCHAR2(20) NOT NULL,
    address VARCHAR2(255),
    profile_photo VARCHAR2(255),
    created_at DATE DEFAULT SYSDATE,
    updated_at DATE DEFAULT SYSDATE,
    CONSTRAINT chk_users_role CHECK (role IN ('ADMIN', 'SHELTER_STAFF', 'VETERINARIAN', 'USER'))
);

CREATE TABLE pets (
    pet_id VARCHAR2(32) DEFAULT RAWTOHEX(SYS_GUID()) PRIMARY KEY,
    pet_name VARCHAR2(100) NOT NULL,
    species VARCHAR2(60) NOT NULL,
    breed VARCHAR2(80),
    age NUMBER(3) NOT NULL,
    gender VARCHAR2(10) NOT NULL,
    vaccination_status VARCHAR2(30) NOT NULL,
    health_condition VARCHAR2(255),
    adoption_status VARCHAR2(20) DEFAULT 'AVAILABLE' NOT NULL,
    image_path VARCHAR2(255),
    created_at DATE DEFAULT SYSDATE,
    updated_at DATE DEFAULT SYSDATE,
    CONSTRAINT chk_pet_gender CHECK (gender IN ('MALE', 'FEMALE', 'UNKNOWN')),
    CONSTRAINT chk_vaccination_status CHECK (vaccination_status IN ('VACCINATED', 'PARTIAL', 'NOT_VACCINATED')),
    CONSTRAINT chk_adoption_status CHECK (adoption_status IN ('AVAILABLE', 'PENDING', 'ADOPTED'))
);

CREATE TABLE adoption_requests (
    request_id VARCHAR2(32) DEFAULT RAWTOHEX(SYS_GUID()) PRIMARY KEY,
    user_id VARCHAR2(32) NOT NULL,
    pet_id VARCHAR2(32) NOT NULL,
    request_date DATE DEFAULT SYSDATE NOT NULL,
    status VARCHAR2(20) DEFAULT 'PENDING' NOT NULL,
    reviewed_by VARCHAR2(32),
    decision_date DATE,
    remarks VARCHAR2(255),
    CONSTRAINT fk_requests_user FOREIGN KEY (user_id) REFERENCES users (user_id) ON DELETE CASCADE,
    CONSTRAINT fk_requests_pet FOREIGN KEY (pet_id) REFERENCES pets (pet_id) ON DELETE CASCADE,
    CONSTRAINT fk_requests_reviewer FOREIGN KEY (reviewed_by) REFERENCES users (user_id) ON DELETE SET NULL,
    CONSTRAINT chk_request_status CHECK (status IN ('PENDING', 'APPROVED', 'REJECTED'))
);

CREATE TABLE veterinary_appointments (
    appointment_id VARCHAR2(32) DEFAULT RAWTOHEX(SYS_GUID()) PRIMARY KEY,
    pet_id VARCHAR2(32) NOT NULL,
    vet_id VARCHAR2(32) NOT NULL,
    requested_by VARCHAR2(32) NOT NULL,
    appointment_date DATE NOT NULL,
    reason VARCHAR2(255) NOT NULL,
    status VARCHAR2(20) DEFAULT 'SCHEDULED' NOT NULL,
    notes VARCHAR2(255),
    created_at DATE DEFAULT SYSDATE,
    updated_at DATE DEFAULT SYSDATE,
    CONSTRAINT fk_appointments_pet FOREIGN KEY (pet_id) REFERENCES pets (pet_id) ON DELETE CASCADE,
    CONSTRAINT fk_appointments_vet FOREIGN KEY (vet_id) REFERENCES users (user_id) ON DELETE CASCADE,
    CONSTRAINT fk_appointments_requester FOREIGN KEY (requested_by) REFERENCES users (user_id) ON DELETE CASCADE,
    CONSTRAINT chk_appointment_status CHECK (status IN ('SCHEDULED', 'COMPLETED', 'CANCELLED'))
);

CREATE TABLE medical_records (
    record_id VARCHAR2(32) DEFAULT RAWTOHEX(SYS_GUID()) PRIMARY KEY,
    pet_id VARCHAR2(32) NOT NULL,
    vet_id VARCHAR2(32) NOT NULL,
    diagnosis VARCHAR2(255) NOT NULL,
    treatment VARCHAR2(255) NOT NULL,
    vaccination_date DATE,
    next_vaccine_date DATE,
    prescription VARCHAR2(255),
    created_at DATE DEFAULT SYSDATE,
    updated_at DATE DEFAULT SYSDATE,
    CONSTRAINT fk_records_pet FOREIGN KEY (pet_id) REFERENCES pets (pet_id) ON DELETE CASCADE,
    CONSTRAINT fk_records_vet FOREIGN KEY (vet_id) REFERENCES users (user_id) ON DELETE CASCADE
);

CREATE INDEX idx_pets_species ON pets (species);
CREATE INDEX idx_pets_breed ON pets (breed);
CREATE INDEX idx_pets_status ON pets (adoption_status);
CREATE INDEX idx_requests_status ON adoption_requests (status);
CREATE INDEX idx_records_vet ON medical_records (vet_id);

CREATE OR REPLACE VIEW vw_pet_listing AS
SELECT
    pet_id,
    pet_name,
    species,
    breed,
    age,
    gender,
    vaccination_status,
    health_condition,
    adoption_status,
    image_path
FROM pets;

CREATE OR REPLACE VIEW vw_shelter_occupancy AS
SELECT
    COUNT(pet_id) AS current_pet_count
FROM pets
WHERE adoption_status <> 'ADOPTED';

CREATE OR REPLACE VIEW vw_dashboard_summary AS
SELECT
    (SELECT COUNT(*) FROM pets) AS total_pets,
    (SELECT COUNT(*) FROM pets WHERE adoption_status = 'ADOPTED') AS total_adopted_pets,
    (SELECT COUNT(*) FROM adoption_requests WHERE status = 'PENDING') AS pending_requests,
    (SELECT COUNT(*) FROM medical_records WHERE vaccination_date >= TRUNC(SYSDATE, 'MM')) AS vaccinations_this_month
FROM dual;

CREATE OR REPLACE VIEW vw_vaccination_statistics AS
SELECT
    vaccination_status,
    COUNT(*) AS pet_count
FROM pets
GROUP BY vaccination_status;


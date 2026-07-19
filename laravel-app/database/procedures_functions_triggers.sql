CREATE OR REPLACE TRIGGER trg_adoption_status_update
AFTER UPDATE OF status ON adoption_requests
FOR EACH ROW
WHEN (NEW.status = 'APPROVED')
BEGIN
    UPDATE pets
    SET adoption_status = 'ADOPTED',
        updated_at = SYSDATE
    WHERE pet_id = :NEW.pet_id;
END;
/


CREATE OR REPLACE TRIGGER trg_check_event_date
BEFORE INSERT ON event_enrollments
FOR EACH ROW
DECLARE
    v_event_date DATE;
BEGIN
    SELECT event_date INTO v_event_date
    FROM events
    WHERE event_id = :NEW.event_id;

    IF v_event_date < TRUNC(SYSDATE) THEN
        RAISE_APPLICATION_ERROR(-20003, 'Cannot enroll in a past event.');
    END IF;
END;
/


CREATE OR REPLACE PROCEDURE sp_process_adoption_request (
    p_request_id IN VARCHAR2,
    p_status IN VARCHAR2,
    p_reviewer_id IN VARCHAR2,
    p_remarks IN VARCHAR2
) AS
BEGIN
    UPDATE adoption_requests
    SET status = p_status,
        reviewed_by = p_reviewer_id,
        decision_date = SYSDATE,
        remarks = p_remarks
    WHERE request_id = p_request_id;

    COMMIT;
END;
/


CREATE OR REPLACE PROCEDURE sp_create_adoption_request (
    p_user_id IN VARCHAR2,
    p_pet_id IN VARCHAR2
) AS
BEGIN
    INSERT INTO adoption_requests (
        user_id,
        pet_id,
        request_date,
        status
    ) VALUES (
        p_user_id,
        p_pet_id,
        SYSDATE,
        'PENDING'
    );

    UPDATE pets
    SET adoption_status = 'PENDING',
        updated_at = SYSDATE
    WHERE pet_id = p_pet_id
      AND adoption_status = 'AVAILABLE';
    COMMIT;
END;
/


CREATE OR REPLACE PROCEDURE sp_schedule_appointment (
    p_pet_id IN VARCHAR2,
    p_vet_id IN VARCHAR2,
    p_requested_by IN VARCHAR2,
    p_appointment_date_str IN VARCHAR2,
    p_reason IN VARCHAR2
) AS
BEGIN
    INSERT INTO veterinary_appointments (
        pet_id,
        vet_id,
        requested_by,
        appointment_date,
        reason,
        status
    ) VALUES (
        p_pet_id,
        p_vet_id,
        p_requested_by,
        TO_DATE(p_appointment_date_str, 'YYYY-MM-DD'),
        p_reason,
        'SCHEDULED'
    );
    COMMIT;
END;
/


CREATE OR REPLACE PROCEDURE sp_update_appointment_status (
    p_appointment_id IN VARCHAR2,
    p_status IN VARCHAR2,
    p_notes IN VARCHAR2
) AS
BEGIN
    UPDATE veterinary_appointments
    SET status = p_status,
        notes = p_notes,
        updated_at = SYSDATE
    WHERE appointment_id = p_appointment_id;
    COMMIT;
END;
/


CREATE OR REPLACE PROCEDURE sp_add_medical_record (
    p_pet_id IN VARCHAR2,
    p_vet_id IN VARCHAR2,
    p_diagnosis IN VARCHAR2,
    p_treatment IN VARCHAR2,
    p_vaccination_date_str IN VARCHAR2,
    p_next_vaccine_date_str IN VARCHAR2,
    p_prescription IN VARCHAR2
) AS
BEGIN
    INSERT INTO medical_records (
        pet_id,
        vet_id,
        diagnosis,
        treatment,
        vaccination_date,
        next_vaccine_date,
        prescription
    ) VALUES (
        p_pet_id,
        p_vet_id,
        p_diagnosis,
        p_treatment,
        CASE WHEN p_vaccination_date_str IS NOT NULL THEN TO_DATE(p_vaccination_date_str, 'YYYY-MM-DD') ELSE NULL END,
        CASE WHEN p_next_vaccine_date_str IS NOT NULL THEN TO_DATE(p_next_vaccine_date_str, 'YYYY-MM-DD') ELSE NULL END,
        p_prescription
    );
    COMMIT;
END;
/


CREATE OR REPLACE PROCEDURE sp_create_event (
    p_title IN VARCHAR2,
    p_description IN VARCHAR2,
    p_event_date_str IN VARCHAR2,
    p_location IN VARCHAR2,
    p_created_by IN VARCHAR2
) AS
BEGIN
    INSERT INTO events (
        title,
        description,
        event_date,
        location,
        created_by
    ) VALUES (
        p_title,
        p_description,
        TO_DATE(p_event_date_str, 'YYYY-MM-DD'),
        p_location,
        p_created_by
    );
    COMMIT;
END;
/


CREATE OR REPLACE PROCEDURE sp_enroll_event (
    p_event_id IN VARCHAR2,
    p_user_id IN VARCHAR2,
    p_status IN VARCHAR2
) AS
    v_count NUMBER;
BEGIN
    SELECT COUNT(*) INTO v_count
    FROM event_enrollments
    WHERE event_id = p_event_id AND user_id = p_user_id;

    IF v_count > 0 THEN
        UPDATE event_enrollments
        SET status = p_status,
            updated_at = SYSDATE
        WHERE event_id = p_event_id AND user_id = p_user_id;
    ELSE
        INSERT INTO event_enrollments (
            event_id,
            user_id,
            status
        ) VALUES (
            p_event_id,
            p_user_id,
            p_status
        );
    END IF;
    COMMIT;
END;
/


CREATE OR REPLACE PROCEDURE sp_create_pet (
    p_pet_name IN VARCHAR2,
    p_species IN VARCHAR2,
    p_breed IN VARCHAR2,
    p_age IN NUMBER,
    p_gender IN VARCHAR2,
    p_vaccination_status IN VARCHAR2,
    p_health_condition IN VARCHAR2,
    p_adoption_status IN VARCHAR2,
    p_image_path IN VARCHAR2,
    p_food_preference IN VARCHAR2,
    p_distinct_habit IN VARCHAR2
) AS
BEGIN
    INSERT INTO pets (
        pet_name,
        species,
        breed,
        age,
        gender,
        vaccination_status,
        health_condition,
        adoption_status,
        image_path,
        food_preference,
        distinct_habit
    ) VALUES (
        p_pet_name,
        p_species,
        p_breed,
        p_age,
        p_gender,
        p_vaccination_status,
        p_health_condition,
        p_adoption_status,
        p_image_path,
        p_food_preference,
        p_distinct_habit
    );
    COMMIT;
END;
/


CREATE OR REPLACE PROCEDURE sp_update_pet (
    p_pet_id IN VARCHAR2,
    p_pet_name IN VARCHAR2,
    p_species IN VARCHAR2,
    p_breed IN VARCHAR2,
    p_age IN NUMBER,
    p_gender IN VARCHAR2,
    p_vaccination_status IN VARCHAR2,
    p_health_condition IN VARCHAR2,
    p_adoption_status IN VARCHAR2,
    p_image_path IN VARCHAR2,
    p_food_preference IN VARCHAR2,
    p_distinct_habit IN VARCHAR2
) AS
BEGIN
    UPDATE pets
    SET pet_name = p_pet_name,
        species = p_species,
        breed = p_breed,
        age = p_age,
        gender = p_gender,
        vaccination_status = p_vaccination_status,
        health_condition = p_health_condition,
        adoption_status = p_adoption_status,
        image_path = p_image_path,
        food_preference = p_food_preference,
        distinct_habit = p_distinct_habit,
        updated_at = SYSDATE
    WHERE pet_id = p_pet_id;
    COMMIT;
END;
/


CREATE OR REPLACE PROCEDURE sp_delete_pet (
    p_pet_id IN VARCHAR2
) AS
BEGIN
    DELETE FROM pets WHERE pet_id = p_pet_id;
    COMMIT;
END;
/


CREATE OR REPLACE FUNCTION fn_get_pet_age_group(p_age IN NUMBER)
RETURN VARCHAR2 IS
BEGIN
    IF p_age < 1 THEN
        RETURN 'Kitten/Puppy';
    ELSIF p_age <= 3 THEN
        RETURN 'Young Adult';
    ELSIF p_age <= 7 THEN
        RETURN 'Mature Adult';
    ELSE
        RETURN 'Senior';
    END IF;
END;
/


CREATE OR REPLACE FUNCTION fn_get_adopter_request_count(p_user_id IN VARCHAR2)
RETURN NUMBER IS
    v_count NUMBER := 0;
BEGIN
    SELECT COUNT(*) INTO v_count
    FROM adoption_requests
    WHERE user_id = p_user_id;
    RETURN v_count;
END;
/

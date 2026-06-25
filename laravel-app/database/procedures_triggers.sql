-- PetCareHub Oracle procedures and triggers

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

CREATE OR REPLACE TRIGGER trg_appointment_vet_role
BEFORE INSERT OR UPDATE OF vet_id ON veterinary_appointments
FOR EACH ROW
DECLARE
    v_role users.role%TYPE;
BEGIN
    SELECT role INTO v_role
    FROM users
    WHERE user_id = :NEW.vet_id;

    IF v_role <> 'VETERINARIAN' THEN
        RAISE_APPLICATION_ERROR(-20001, 'vet_id must reference a veterinarian account.');
    END IF;
END;
/

CREATE OR REPLACE TRIGGER trg_medical_record_vet_role
BEFORE INSERT OR UPDATE OF vet_id ON medical_records
FOR EACH ROW
DECLARE
    v_role users.role%TYPE;
BEGIN
    SELECT role INTO v_role
    FROM users
    WHERE user_id = :NEW.vet_id;

    IF v_role <> 'VETERINARIAN' THEN
        RAISE_APPLICATION_ERROR(-20002, 'vet_id must reference a veterinarian account.');
    END IF;
END;
/

CREATE OR REPLACE PROCEDURE sp_process_adoption_request (
    p_request_id IN NUMBER,
    p_status IN VARCHAR2,
    p_reviewer_id IN NUMBER,
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

CREATE OR REPLACE PROCEDURE sp_monthly_adoption_report (
    p_month IN NUMBER,
    p_year IN NUMBER,
    p_result OUT SYS_REFCURSOR
) AS
BEGIN
    OPEN p_result FOR
        SELECT
            ar.request_id,
            ar.request_date,
            u.full_name AS adopter_name,
            p.pet_name,
            p.species,
            p.breed,
            ar.status,
            ar.decision_date,
            ar.remarks
        FROM adoption_requests ar
        JOIN users u ON u.user_id = ar.user_id
        JOIN pets p ON p.pet_id = ar.pet_id
        WHERE ar.status = 'APPROVED'
          AND EXTRACT(MONTH FROM ar.request_date) = p_month
          AND EXTRACT(YEAR FROM ar.request_date) = p_year
        ORDER BY ar.request_date DESC;
END;
/

CREATE OR REPLACE PROCEDURE sp_dashboard_metrics (
    p_total_pets OUT NUMBER,
    p_total_adopted OUT NUMBER,
    p_pending_requests OUT NUMBER,
    p_vaccinations_this_month OUT NUMBER
) AS
BEGIN
    SELECT COUNT(*) INTO p_total_pets FROM pets;
    SELECT COUNT(*) INTO p_total_adopted FROM pets WHERE adoption_status = 'ADOPTED';
    SELECT COUNT(*) INTO p_pending_requests FROM adoption_requests WHERE status = 'PENDING';
    SELECT COUNT(*) INTO p_vaccinations_this_month
    FROM medical_records
    WHERE vaccination_date >= TRUNC(SYSDATE, 'MM');
END;
/
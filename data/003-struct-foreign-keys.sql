
ALTER TABLE taxonomy
    ADD CONSTRAINT taxonomy_fk_parent_exists
        FOREIGN KEY (parent_id)
        REFERENCES taxonomy(id)
        ON DELETE CASCADE;

ALTER TABLE training
    ADD CONSTRAINT training_fk_trainer_exists
        FOREIGN KEY (trainer_id)
        REFERENCES trainer(id)
        ON DELETE SET NULL,

    ADD CONSTRAINT fk_training_level
        FOREIGN KEY (level_id)
        REFERENCES taxonomy(id)
        ON DELETE RESTRICT;

ALTER TABLE training_program
    ADD CONSTRAINT training_program_fk_training_exists
        FOREIGN KEY (training_id)
        REFERENCES training(id)
        ON DELETE CASCADE;


ALTER TABLE booking
    ADD CONSTRAINT booking_fk_event_exists
        FOREIGN KEY (event_id)
        REFERENCES event(id)
        ON DELETE RESTRICT,

    ADD CONSTRAINT booking_fk_training_exists
        FOREIGN KEY (training_id)
        REFERENCES training(id)
        ON DELETE RESTRICT,

    ADD CONSTRAINT booking_fk_status_exists
        FOREIGN KEY (status_id)
        REFERENCES taxonomy(id)
        ON DELETE RESTRICT;

ALTER TABLE contact_request
    ADD CONSTRAINT fk_contact_request_status
        FOREIGN KEY (status_id) 
        REFERENCES taxonomy(id) 
        ON DELETE RESTRICT,

    ADD CONSTRAINT fk_contact_request_subject
        FOREIGN KEY (subject_id) 
        REFERENCES taxonomy(id) 
        ON DELETE RESTRICT;

ALTER TABLE operator_session
    ADD CONSTRAINT operator_session_fk_operator_exists
        FOREIGN KEY (operator_id) 
        REFERENCES operator(id) 
        ON DELETE CASCADE;
    
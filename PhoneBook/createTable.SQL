CREATE TABLE prc_db_phonebook (
    id INT AUTO_INCREMENT PRIMARY KEY,  -- Optional: Adds a unique ID for each record
    name VARCHAR(255) NOT NULL,         -- Stores the name (e.g., organization or person)
    number VARCHAR(255),                -- Stores a number (e.g., phone number or ID)
    dakheli VARCHAR(255),               -- Stores internal information
    semat VARCHAR(255),                 -- Stores job title or position
    contact_person VARCHAR(255),        -- Stores the name of the contact person
    email VARCHAR(255),                 -- Stores the email address
    vahed_zirabt VARCHAR(255),          -- Stores unit or department information
    shakhsiyat VARCHAR(255)             -- Specifies if the entity is legal or natural
);
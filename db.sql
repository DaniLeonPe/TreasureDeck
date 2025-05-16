CREATE TABLE users (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) UNIQUE NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    email_verified BOOLEAN DEFAULT FALSE,
    email_verification_token VARCHAR(255) UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. Expansiones
CREATE TABLE expansions (
    id BIGINT PRIMARY KEY, -- ID tomado de la API externa
    name VARCHAR(255) NOT NULL,
    abbr VARCHAR(50),
    release_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 3. Cartas base (blueprints)
CREATE TABLE cards (
    id BIGINT PRIMARY KEY, -- blueprint_id
    name VARCHAR(255) NOT NULL,
    collector_number VARCHAR(50),
    rarity VARCHAR(50),
    expansion_id BIGINT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (expansion_id) REFERENCES expansions(id) ON DELETE CASCADE
);

-- 4. Versiones de carta
CREATE TABLE card_versions (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    card_id BIGINT NOT NULL,
    version VARCHAR(100), -- "Normal", "Alternate Art", etc.
    image_url TEXT,
    min_price DECIMAL(8,2),
    avg_price DECIMAL(8,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (card_id) REFERENCES cards(id) ON DELETE CASCADE
);

-- 5. Colección del usuario (qué versiones tiene)
CREATE TABLE user_cards (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT NOT NULL,
    card_version_id BIGINT NOT NULL,
    quantity INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (card_version_id) REFERENCES card_versions(id) ON DELETE CASCADE
);

-- 6. Mazos creados por el usuario
CREATE TABLE decks (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT NOT NULL,
    name VARCHAR(255) NOT NULL,
    leader_card_version_id BIGINT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (leader_card_version_id) REFERENCES card_versions(id)
);

-- 7. Cartas dentro de cada mazo
CREATE TABLE deck_cards (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    deck_id BIGINT NOT NULL,
    card_version_id BIGINT NOT NULL,
    quantity INT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (deck_id) REFERENCES decks(id) ON DELETE CASCADE,
    FOREIGN KEY (card_version_id) REFERENCES card_versions(id) ON DELETE CASCADE
);

-- 8. Estadísticas de cada mazo
CREATE TABLE deck_stats (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    deck_id BIGINT NOT NULL,
    wins INT DEFAULT 0,
    losses INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (deck_id) REFERENCES decks(id) ON DELETE CASCADE
);
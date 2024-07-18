-- Create Database
CREATE DATABASE IF NOT EXISTS messaging_app;
USE messaging_app;

-- Create Users Table
CREATE TABLE IF NOT EXISTS Users (
                                     user_id INT AUTO_INCREMENT PRIMARY KEY,
                                     first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );

-- Create Messages Table with UUID and Ordered ID
CREATE TABLE IF NOT EXISTS Messages (
                                        message_id CHAR(36) PRIMARY KEY,
    user_id INT NOT NULL,
    message_text TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    ordered_id BIGINT AUTO_INCREMENT,
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_ordered_id (ordered_id)  -- Index for efficient querying
    );

-- Insert test data into Users
INSERT INTO Users (first_name, last_name, email) VALUES
                                                     ('John', 'Doe', 'john.doe@example.com'),
                                                     ('Jane', 'Smith', 'jane.smith@example.com'),
                                                     ('Alice', 'Johnson', 'alice.johnson@example.com'),
                                                     ('Bob', 'Williams', 'bob.williams@example.com');

-- Insert test data into Messages using UUIDs
INSERT INTO Messages (message_id, user_id, message_text) VALUES
                                                             (UUID(), 1, 'Hello, this is the first message from John!'),
                                                             (UUID(), 1, 'Another message from John about his day.'),
                                                             (UUID(), 2, 'Jane here, nice to meet you all!'),
                                                             (UUID(), 2, 'Just checking in with another message.'),
                                                             (UUID(), 3, 'Alice sending her greetings!'),
                                                             (UUID(), 3, 'Alice loves coding and sharing ideas.'),
                                                             (UUID(), 4, 'Bob is excited to join this chat!'),
                                                             (UUID(), 4, 'Here is Bob with another message about his projects.'),
                                                             (UUID(), 1, 'John shares an interesting article today.'),
                                                             (UUID(), 2, 'Jane replies with her thoughts on the article.'),
                                                             (UUID(), 3, 'Alice asks a question about the article.'),
                                                             (UUID(), 4, 'Bob offers insights into the discussion.'),
                                                             (UUID(), 1, 'John shares a funny meme.'),
                                                             (UUID(), 2, 'Jane laughs and responds with a similar meme.'),
                                                             (UUID(), 3, 'Alice shares her favorite meme too!'),
                                                             (UUID(), 4, 'Bob enjoys the memes shared in the chat.');
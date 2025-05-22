-- version: 006

CREATE TABLE user_todo_list (
    user_id INT NOT NULL,
    todo_list_id INT NOT NULL,
    is_owner BOOLEAN DEFAULT FALSE,
    PRIMARY KEY (user_id, todo_list_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (todo_list_id) REFERENCES todo_lists(id) ON DELETE CASCADE
);

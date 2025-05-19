<?php

/**
 * User data mapper functions
 */

/**
 * Get user by username
 * 
 * @param string $username Username
 * @return array|false User data or false if not found
 */
function user_get_by_username($username)
{
    return db_state(
        "SELECT * FROM users WHERE username = ? AND status = 'active'",
        [$username]
    )->fetch();
}

/**
 * Get user by ID
 * 
 * @param int $id User ID
 * @return array|false User data or false if not found
 */
function user_get_by_id($id)
{
    return db_state(
        "SELECT * FROM users WHERE id = ? AND status = 'active'",
        [$id]
    )->fetch();
}

/**
 * Get user by email
 * 
 * @param string $email Email address
 * @return array|false User data or false if not found
 */
function user_get_by_email($email)
{
    return db_state(
        "SELECT * FROM users WHERE email = ? AND status = 'active'",
        [$email]
    )->fetch();
}

/**
 * Create a new user
 * 
 * @param array $data User data
 * @return int|false New user ID or false on failure
 */
function user_create($data)
{
    // Hash password
    $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

    $stmt = db_create('users', [
        'username' => $data['username'],
        'password' => $data['password'],
        'email' => $data['email'],
        'full_name' => $data['full_name'],
        'role' => $data['role'] ?? 'user',
        'status' => $data['status'] ?? 'active',
    ]);

    return $stmt->rowCount() > 0 ? db()->lastInsertId() : false;
}

/**
 * Update an existing user
 * 
 * @param int $id User ID
 * @param array $data Updated user data
 * @return bool Success status
 */
function user_update($id, $data)
{
    $updateData = [];

    // Conditionally update password if provided
    if (isset($data['password']) && !empty($data['password'])) {
        $updateData['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
    }

    foreach (['email', 'full_name', 'role', 'status'] as $field) {
        if (isset($data[$field])) {
            $updateData[$field] = $data[$field];
        }
    }

    if (empty($updateData)) {
        return false;
    }

    $stmt = db_update('users', $updateData, 'id = ?', [$id]);
    return $stmt->rowCount() > 0;
}

/**
 * Verify user credentials
 * 
 * @param string $username Username
 * @param string $password Password
 * @return array|false User data or false if invalid credentials
 */
function user_verify($username, $password)
{
    $user = user_get_by_username($username);

    if (!$user) {
        return false;
    }

    if (!password_verify($password, $user['password'])) {
        return false;
    }

    return $user;
}

/**
 * Create an authentication token for the user
 * 
 * @param int $user_id User ID
 * @return string Generated token
 */
function user_create_token($user_id)
{
    $token = bin2hex(random_bytes(32));
    $expires = date('Y-m-d H:i:s', time() + 86400 * 30); // 30 days

    db_create('user_tokens', [
        'user_id' => $user_id,
        'token' => $token,
        'expires_at' => $expires
    ]);

    return $token;
}

/**
 * Verify a user token
 * 
 * @param string $token Authentication token
 * @return int|false User ID or false if invalid
 */
function user_verify_token($token)
{
    $token_data = db_state(
        "SELECT user_id FROM user_tokens 
         WHERE token = ? AND expires_at > NOW()",
        [$token]
    )->fetch();

    return $token_data ? $token_data['user_id'] : false;
}

<?php

/**
 * User data mapper functions
 */

/**
 * @return array|false User data or false if not found
 */
function user_get_by(string $field, $value): array|false
{
    return dbq(
        "SELECT * FROM users WHERE ? = ? AND status = 'active'",
        [$field, $value]
    )->fetch();
}
/**
 * Create a new user
 * 
 * @return int|false New user ID or false on failure
 */
function user_create(array $data)
{
    // Hash password
    $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
    $insert_data = [
        'username' => $data['username'],
        'password' => $data['password'],
        'email' => $data['email'],
        'full_name' => $data['full_name'],
        'role' => $data['role'] ?? 'user',
        'status' => $data['status'] ?? 'active',
    ];
    $stmt = dbq(...qb_create('users', $insert_data));

    return $stmt->rowCount() > 0 ? db()->lastInsertId() : false;
}

/**
 * Update an existing user
 * 
 * @param int $id User ID
 * @param array $data Updated user data
 * @return bool Success status
 */
function user_update($id, $data): bool
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
    $stmt = dbq(...qb_update('users', $updateData, 'id = ?', [$id]));
    return $stmt->rowCount() > 0;
}

/**
 * Verify user credentials
 * 
 * @param string $username Username
 * @param string $password Password
 * @return array|false User data or false if invalid credentials
 */
function user_verify(string $username, string $password)
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
 * @return string Generated token
 */
function user_create_token(int $user_id): string
{
    $token = bin2hex(random_bytes(32));
    $expires = date('Y-m-d H:i:s', time() + 86400 * 30); // 30 days

    $insert_data = [
        'user_id' => $user_id,
        'token' => $token,
        'expires_at' => $expires
    ];
    $stmt = dbq(...qb_create('user_tokens', $insert_data));


    return $token;
}

/**
 * Verify a user token
 * 
 * @return int|false User ID or false if invalid
 */
function user_verify_token(string $token)
{
    $token_data = dbq(
        "SELECT user_id FROM user_tokens 
         WHERE token = ? AND expires_at > NOW()",
        [$token]
    )->fetch();

    return $token_data ? $token_data['user_id'] : false;
}

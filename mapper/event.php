<?php

/**
 * Event data mapper functions
 */

/**
 * Get upcoming published events
 * 
 * @param int $limit Maximum number of events to return
 * @param int $offset Starting position
 * @return array Events data
 */
function events_get_upcoming(int $limit = 10, int $offset = 0)
{
    return dbq(
        "SELECT e.*, u.username as organizer
         FROM events e
         JOIN users u ON e.user_id = u.id
         WHERE e.status = 'published' AND e.end_datetime >= NOW()
         ORDER BY e.start_datetime ASC
         LIMIT $limit OFFSET $offset"
    )->fetchAll();
}

/**
 * Get past published events
 * 
 * @param int $limit Maximum number of events to return
 * @param int $offset Starting position
 * @return array Events data
 */
function events_get_past(int $limit = 10, int $offset = 0)
{
    return dbq(
        "SELECT e.*, u.username as organizer
         FROM events e
         JOIN users u ON e.user_id = u.id
         WHERE e.status = 'published' AND e.end_datetime < NOW()
         ORDER BY e.start_datetime DESC
        LIMIT $limit OFFSET $offset"
    )->fetchAll();
}

/**
 * Get published event by slug
 * 
 * @param string $slug Event slug
 * @return array|false Event data or false if not found
 */
function event_get_by_slug($slug)
{
    return dbq(
        "SELECT e.*, u.username as organizer, u.full_name as organizer_name
         FROM events e
         JOIN users u ON e.user_id = u.id
         WHERE e.slug = ? AND e.status = 'published'",
        [$slug]
    )->fetch();
}

/**
 * Create a new event
 * 
 * @param array $data Event data
 * @return int|false New event ID or false on failure
 */
function event_create($data)
{
    $insert_data =  [
        'title' => $data['title'],
        'slug' => $data['slug'],
        'description' => $data['description'],
        'location' => $data['location'] ?? null,
        'start_datetime' => $data['start_datetime'],
        'end_datetime' => $data['end_datetime'],
        'image_url' => $data['image_url'] ?? null,
        'status' => $data['status'] ?? 'draft',
        'user_id' => $data['user_id'],
    ];
    $stmt = dbq(...qb_create('events', $insert_data));

    return $stmt->rowCount() > 0 ? db()->lastInsertId() : false;
}

/**
 * Update an existing event
 * 
 * @param int $id Event ID
 * @param array $data Updated event data
 * @return bool Success status
 */
function event_update($id, $data)
{
    $updateData = [];

    foreach (
        [
            'title',
            'slug',
            'description',
            'location',
            'start_datetime',
            'end_datetime',
            'image_url',
            'status'
        ] as $field
    ) {
        if (isset($data[$field])) {
            $updateData[$field] = $data[$field];
        }
    }

    if (empty($updateData)) {
        return false;
    }

    $stmt = dbq(...qb_update('events', $updateData, 'id = ?', [$id]));
    return $stmt->rowCount() > 0;
}

/**
 * Delete an event
 * 
 * @param int $id Event ID
 * @return bool Success status
 */
function event_delete($id)
{
    $stmt = dbq("DELETE FROM events WHERE id = ?", [$id]);
    return $stmt->rowCount() > 0;
}

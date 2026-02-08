<?php
class RoomRepository {
    private $pdo;
    
    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }
    
    public function getAll() {
        $stmt = $this->pdo->query("SELECT DISTINCT title, type, capacity, description FROM rooms ORDER BY title ASC");
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Room');
    }
    
    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM rooms WHERE id = ?");
        $stmt->execute([$id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Room');
        return $stmt->fetch();
    }
    
    public function create(Room $room) {
        $stmt = $this->pdo->prepare(
            "INSERT INTO rooms (title, description, capacity, type) 
             VALUES (?, ?, ?, ?)"
        );
        
        $stmt->execute([
            $room->title,
            $room->description,
            $room->capacity,
            $room->type
        ]);
        
        return $this->pdo->lastInsertId();
    }
    
    public function update(Room $room) {
        $stmt = $this->pdo->prepare(
            "UPDATE rooms 
             SET title = ?, description = ?, capacity = ?, type = ?
             WHERE id = ?"
        );
        
        return $stmt->execute([
            $room->title,
            $room->description,
            $room->capacity,
            $room->type,
            $room->id
        ]);
    }
    
    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM rooms WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    public function getAvailable($date) {
        $stmt = $this->pdo->prepare(
            "SELECT r.* FROM rooms r
             WHERE r.id NOT IN (
                 SELECT room_id FROM screenings 
                 WHERE screening_date = ?
             )
             ORDER BY r.title ASC"
        );
        $stmt->execute([$date]);
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Room');
    }
    
}

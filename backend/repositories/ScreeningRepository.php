<?php
class ScreeningRepository {
    private $pdo;
    
    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }
    
    public function getAll() {
        $stmt = $this->pdo->query(
            "SELECT s.*, m.title as movie_title, r.title as room_title
             FROM screenings s
             JOIN movies m ON s.movie_id = m.id
             JOIN rooms r ON s.room_id = r.id
             ORDER BY s.screening_date DESC"
        );
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Screening');
    }
    
    public function getById($id) {
        $stmt = $this->pdo->prepare(
            "SELECT s.*, m.title as movie_title, r.title as room_title
             FROM screenings s
             JOIN movies m ON s.movie_id = m.id
             JOIN rooms r ON s.room_id = r.id
             WHERE s.id = ?"
        );
        $stmt->execute([$id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Screening');
        return $stmt->fetch();
    }
    
    public function create(Screening $screening) {
        if (!$this->isRoomAvailable($screening->room_id, $screening->screening_date, null)) {
            throw new Exception("La salle est déjà occupée à cette date et heure");
        }
        
        $stmt = $this->pdo->prepare(
            "INSERT INTO screenings (movie_id, room_id, screening_date, price) 
             VALUES (?, ?, ?, ?)"
        );
        
        $stmt->execute([
            $screening->movie_id,
            $screening->room_id,
            $screening->screening_date,
            $screening->price ?? 10.00
        ]);
        
        return $this->pdo->lastInsertId();
    }
    
    public function update(Screening $screening) {
        if (!$this->isRoomAvailable($screening->room_id, $screening->screening_date, $screening->id)) {
            throw new Exception("La salle est déjà occupée à cette date et heure");
        }
        
        $stmt = $this->pdo->prepare(
            "UPDATE screenings 
             SET movie_id = ?, room_id = ?, screening_date = ?, price = ?
             WHERE id = ?"
        );
        
        return $stmt->execute([
            $screening->movie_id,
            $screening->room_id,
            $screening->screening_date,
            $screening->price,
            $screening->id
        ]);
    }
    
    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM screenings WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    public function getByDate($date) {
        $stmt = $this->pdo->prepare(
            "SELECT s.*, m.title as movie_title, r.title as room_title
             FROM screenings s
             JOIN movies m ON s.movie_id = m.id
             JOIN rooms r ON s.room_id = r.id
             WHERE DATE(s.screening_date) = ?
             ORDER BY s.screening_date ASC"
        );
        $stmt->execute([$date]);
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Screening');
    }
    
    public function getByMovie($movieId) {
        $stmt = $this->pdo->prepare(
            "SELECT s.*, m.title as movie_title, r.title as room_title
             FROM screenings s
             JOIN movies m ON s.movie_id = m.id
             JOIN rooms r ON s.room_id = r.id
             WHERE s.movie_id = ?
             ORDER BY s.screening_date ASC"
        );
        $stmt->execute([$movieId]);
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Screening');
    }
    
    private function isRoomAvailable($roomId, $dateTime, $excludeId = null) {
        $sql = "SELECT COUNT(*) as count FROM screenings 
                WHERE room_id = ? 
                AND screening_date BETWEEN DATE_SUB(?, INTERVAL 2 HOUR) AND DATE_ADD(?, INTERVAL 2 HOUR)";
        
        if ($excludeId !== null) {
            $sql .= " AND id != ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$roomId, $dateTime, $dateTime, $excludeId]);
        } else {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$roomId, $dateTime, $dateTime]);
        }
        
        $result = $stmt->fetch();
        return $result['count'] == 0;
    }
}
